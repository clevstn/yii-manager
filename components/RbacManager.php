<?php
/**
 * @copyright Copyright (c) 2020 cleverstone
 */

namespace app\components;

use Yii;
use yii\db\Query;
use yii\di\Instance;
use yii\db\Connection;
use yii\base\Component;
use yii\caching\CacheInterface;
use yii\rbac\CheckAccessInterface;

/**
 * rbac组件
 * @author cleverstone
 * @since ym1.0
 */
class RbacManager extends Component implements CheckAccessInterface
{
    // 超管组ID
    const ADMINISTRATOR_GROUP = 0;

    // 菜单类型：1、菜单 2、功能
    const LABEL_TYPE_MENU = 1;
    const LABEL_TYPE_FUNCTION = 2;

    // 链接类型：1、路由；2、外链
    const LINK_TYPE_ROUTE = 1;
    const LINK_TYPE_URL = 2;

    /**
     * @var Connection|array|string the DB connection object or the application component ID of the DB connection.
     * After the DbManager object is created, if you want to change this property, you should only assign it
     * with a DB connection object.
     * Starting from version 2.0.2, this can also be a configuration array for creating the object.
     */
    public $db = 'db';
    /**
     * @var string 权限菜单表
     */
    public $menuTable = '{{%auth_menu}}';
    /**
     * @var string 管理组（角色）表
     */
    public $groupsTable = '{{%auth_groups}}';

    /**
     * @var string 权限角色关系表
     */
    public $relationsTable = '{{%auth_relations}}';
    /**
     * @var string 用户表
     */
    public $adminUserTable = '{{%admin_user}}';

    /**
     * @var array 用户权限
     */
    public $assignment;

    /**
     * @var CacheInterface|array|string|null the cache used to improve RBAC performance. This can be one of the following:
     *
     * - an application component ID (e.g. `cache`)
     * - a configuration array
     * - a [[\yii\caching\Cache]] object
     *
     * When this is not set, it means caching is not enabled.
     *
     * Note that by enabling RBAC cache, all auth items, rules and auth item parent-child relationships will
     * be cached and loaded into memory. This will improve the performance of RBAC permission check. However,
     * it does require extra memory and as a result may not be appropriate if your RBAC system contains too many
     * auth items. You should seek other RBAC implementations (e.g. RBAC based on Redis storage) in this case.
     *
     * Also note that if you modify RBAC items, rules or parent-child relationships from outside of this component,
     * you have to manually call [[invalidateCache()]] to ensure data consistency.
     *
     * @since 2.0.3
     */
    public $cache;
    /**
     * @var string the key used to store RBAC data in cache
     * @see cache
     * @since 2.0.3
     */
    public $cacheKey = 'rbac';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::class);
        if ($this->cache !== null) {
            $this->cache = Instance::ensure($this->cache, 'yii\caching\CacheInterface');
        }
    }

    /**
     * 通过组ID获取组权限
     * @param int $groupId 组ID
     * @return array
     */
    public function getAssignmentByGroup($groupId)
    {
        $this->loadFromCache($groupId);
        if ($this->assignment !== null) {
            return $this->assignment;
        }

        return $this->getAssignmentByGroupFromDb($groupId);
    }

    /**
     * 通过组ID从数据表中获取组权限
     * @param int $groupId 组ID
     * @return array
     */
    protected function getAssignmentByGroupFromDb($groupId)
    {
        if ($groupId === self::ADMINISTRATOR_GROUP) {
            $data = (new Query())->from($this->menuTable)->all($this->db);
        } else {
            $data = (new Query())->from(['r' => $this->relationsTable])
                ->leftJoin(['m' => $this->menuTable], 'r.menu_id=m.id')
                ->select('m.*')
                ->where(['group_id' => $groupId])
                ->all();
        }

        return $data;
    }


    /**
     * 从缓存中加载组权限
     * @param int $groupId 组ID
     */
    protected function loadFromCache($groupId)
    {
        if ($this->assignment !== null || !$this->cache instanceof CacheInterface) {
            return;
        }

        $data = $this->cache->get($this->cacheKey);
        $cacheValues = [];
        if (is_array($data)) {
            if (isset($data[$groupId])) {
                $this->assignment = $data[$groupId];
                return;
            }

            $cacheValues = $data;
        }

        $assignment = $this->getAssignmentByGroupFromDb($groupId);

        $this->assignment = $assignment;
        $cacheValues[$groupId] = $assignment;

        $this->cache->set($this->cacheKey, $cacheValues);
    }

    /**
     * 加载并更新菜单
     * @return true|string
     * @throws \Exception
     */
    public function updateMenuItems()
    {
        $menuItems = $this->loadLocalMenuItems();
        $transaction = $this->db->beginTransaction();

        try {
            $this->updateMenuItemsRecursive($menuItems);
            $transaction->commit();

            $this->invalidateCache();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $e->getMessage();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            return $e->getMessage();
        }
    }

    /**
     * 递归更新菜单项
     * @param array $menuItems
     * @param int $pid 父ID
     * @param int $sort 排序
     * @throws \yii\db\Exception
     */
    protected function updateMenuItemsRecursive($menuItems, $pid = 0, $sort = 1)
    {
        foreach ($menuItems as $i => $item) {
            if (empty($item['src'])) {
                throw new \InvalidArgumentException(t('the parameter {param} is not defined', 'app.admin', ['param' => 'src']));
            }

            $row = (new Query())->from($this->menuTable)
                ->where(['src' => $item['src']])
                ->select(['id', 'sort'])
                ->one($this->db);

            $childItems = $item['items'];
            unset($item['items']);

            if ($row === false) {
                $item['pid'] = $pid;
                $item['sort'] = $sort + $i;
                $this->db->createCommand()->insert($this->menuTable, $item)->execute();
            }

            if (!empty($childItems)) {
                if ($row === false) {
                    $row = (new Query())->from($this->menuTable)
                        ->where(['src' => $item['src']])
                        ->select(['id', 'sort'])
                        ->one($this->db);
                }

                $this->updateMenuItemsRecursive($childItems, $row['id'], ++$row['sort']);
            }
        }
    }

    /**
     * 缓存失效
     */
    protected function invalidateCache()
    {
        if ($this->cache !== null) {
            $this->cache->delete($this->cacheKey);
        }

        $this->assignment = null;
    }

    /**
     * 加载本地菜单项
     * @return array
     */
    public function loadLocalMenuItems()
    {
        $menuItems = load_file(Yii::getAlias('@app/admin/config/menu.php'), true, false, true);

        return $this->formatMenuItemsRecursive($menuItems['auth']);
    }

    /**
     * 递归格式化本地菜单项
     * @param array $menuItems 菜单项集合
     * @return array
     */
    protected function formatMenuItemsRecursive($menuItems)
    {
        foreach ($menuItems as &$item) {
            if (!empty($item['items'])) {
                $item['items'] = $this->formatMenuItemsRecursive($item['items']);
            }

            $item = $this->formatMenuItem($item);
        }

        return $menuItems;
    }

    /**
     * 格式化本地菜单项
     * @param array $item 菜单项
     * - label      菜单名称
     * - src        源
     * - icon       图标
     * - label_type 类型，1、菜单 2、功能
     * - link_type  链接类型：1、路由；2、外链
     * - dump_way   跳转方式：_self：内部，_blank：外部
     * - desc       备注
     * - sort       排序
     * @return array
     */
    protected function formatMenuItem($item)
    {
        $defaultItem = [
            'label' => '--',
            'src' => '',
            'icon' => '',
            'label_type' => 1,
            'link_type' => 1,
            'dump_way' => '_self',
            'desc' => '',
            'sort' => 0,
            'created_at' => now(),
            'updated_at' => now(),
            'items' => [],
        ];

        return array_merge($defaultItem, $item);
    }

    /**
     * 根据组ID获取菜单数据集合
     * @param int $groupId 组ID
     * @return array
     */
    public function getMenusByGroup($groupId)
    {
        $assignments = $this->getAssignmentByGroup($groupId);

        return $this->extractMenusForAssignments($assignments);
    }

    /**
     * 从指定权限中提取到菜单数据集合
     * @param array $assignments 权限集合
     * @param int $pid 父ID
     * @param array $menusMap 菜单数据集合
     * @return array
     */
    protected function extractMenusForAssignments($assignments, $pid = 0, $menusMap = [])
    {
        foreach ($assignments as $value) {
            if ($value['pid'] == $pid && $value['label_type'] == self::LABEL_TYPE_MENU) {
                $menusMap[] = [
                    'label' => $value['label'],
                    'url' => $value['link_type'] == self::LINK_TYPE_ROUTE ? ["/{$value['src']}"] : $value['src'],
                    'src' => $value['src'],
                    'icon' => $value['icon'],
                    'items' => $this->extractMenusForAssignments($assignments, $value['id']),
                ];
            }
        }

        return $menusMap;
    }

    /**
     * {@inheritdoc}
     */
    public function checkAccess($userId, $permissionName, $params = [])
    {
        return true;
    }
}