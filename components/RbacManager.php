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
use app\models\AuthMenu;
use app\models\AuthGroups;
use yii\caching\CacheInterface;
use yii\helpers\ArrayHelper;
use yii\rbac\CheckAccessInterface;

/**
 * rbac组件
 * @author cleverstone
 * @since ym1.0
 */
class RbacManager extends Component implements CheckAccessInterface
{
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
     * @var array 组权限缓存
     */
    public $permissions;

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
     * 通过组ID(从数据表或缓存中)获取组权限
     * @param int $groupId 组ID
     * @return array
     */
    public function getPermissionsByGroup($groupId)
    {
        $this->loadFromCache($groupId);
        if ($this->permissions !== null) {
            return $this->permissions;
        }

        return $this->getPermissionsByGroupFromDb($groupId);
    }

    /**
     * 通过组ID从数据表中获取组权限
     * @param int $groupId 组ID
     * @return array
     */
    protected function getPermissionsByGroupFromDb($groupId)
    {
        if ($groupId === AuthGroups::ADMINISTRATOR_GROUP) {
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
        if ($this->permissions !== null || !$this->cache instanceof CacheInterface) {
            return;
        }

        $data = $this->cache->get($this->cacheKey);
        $cacheValues = [];
        if (is_array($data)) {
            if (isset($data[$groupId])) {
                $this->permissions = $data[$groupId];
                return;
            }

            $cacheValues = $data;
        }

        $permissions = $this->getPermissionsByGroupFromDb($groupId);

        $this->permissions = $permissions;
        $cacheValues[$groupId] = $permissions;

        $this->cache->set($this->cacheKey, $cacheValues);
    }

    /**
     * 加载并更新菜单
     * @return true|string
     * @throws \Exception
     */
    public function updateMenuItems()
    {
        $menuItems = $this->getLocalMenuItems();
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

        $this->permissions = null;
    }

    /**
     * 获取本地菜单项
     * @return array
     */
    protected function getLocalMenuItems()
    {
        $menuItems = $this->loadLocalItems();
        return $this->formatMenuItemsRecursive($menuItems['auth']);
    }

    /**
     * 加载本地项
     * @return array
     */
    protected function loadLocalItems()
    {
        return load_file(Yii::getAlias('@app/admin/config/menu.php'), true, false, true);
    }

    /**
     * 获取本地白名单
     * @return array
     */
    protected function getLocalWhiteLists()
    {
        $menuItems = $this->loadLocalItems();
        return $menuItems['whiteLists'];
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
     * 根据组ID获取栏目数据集合
     * @param int $groupId 组ID
     * @return array
     */
    public function getBannerByGroup($groupId)
    {
        $permissions = $this->getPermissionsByGroup($groupId);

        return $this->extractBannerFromPermissions($permissions);
    }

    /**
     * 从指定权限中提取到栏目数据集合
     * @param array $permissions 权限集合
     * @param int $pid 父ID
     * @param array $bannerMap 栏目数据集合
     * @return array
     */
    protected function extractBannerFromPermissions($permissions, $pid = 0, $bannerMap = [])
    {
        foreach ($permissions as $value) {
            if ($value['pid'] == $pid && $value['label_type'] == AuthMenu::LABEL_TYPE_MENU) {
                $bannerMap[] = [
                    'label' => $value['label'],
                    'url' => $value['link_type'] == AuthMenu::LINK_TYPE_ROUTE ? ["/{$value['src']}"] : $value['src'],
                    'src' => $value['src'],
                    'icon' => $value['icon'],
                    'sort' => $value['sort'],
                    'items' => $this->extractBannerFromPermissions($permissions, $value['id']),
                ];
            }
        }

        ArrayHelper::multisort($bannerMap, 'sort', SORT_ASC, SORT_NUMERIC);

        return $bannerMap;
    }

    /**
     * 检查当前管理员是否允许视图渲染（用于方法调用）
     * @param string $permissionName 权限
     * @return array|false
     * @throws \Exception
     */
    public function checkAccessForViewRender($permissionName)
    {
        $permissionsMap = $this->getPermissionsByGroup(Yii::$app->adminUser->identity->group);
        $columnsMap = [];
        foreach ($permissionsMap as $value) {
            $columnsMap[$value['src']] = [
                'label' => $value['label'],
                'icon' => $value['icon'],
                'src' => $value['src'],
                'dump_way' => $value['dump_way'],
            ];
        }

        $whiteList = $this->getLocalWhiteLists();
        $whiteListColumnsMap = ArrayHelper::index($whiteList, 'src');

        if (ArrayHelper::keyExists($permissionName, $columnsMap)) {
            return ArrayHelper::getValue($columnsMap, $permissionName) ?: false;
        }

        if (ArrayHelper::keyExists($permissionName, $whiteListColumnsMap)) {
            return ArrayHelper::getValue($whiteListColumnsMap, $permissionName) ?: false;
        }

        return false;
    }

    /**
     * 该检查用于行为中间件
     * {@inheritdoc}
     */
    public function checkAccess($userId, $permissionName, $params = [])
    {
        $permissions = $this->getPermissionsByGroup(Yii::$app->adminUser->identity->group);
        if (in_array($permissionName, ArrayHelper::getColumn($permissions, 'src'), true)) {
            return true;
        }

        return false;
    }
}