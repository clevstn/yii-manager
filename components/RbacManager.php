<?php
/**
 * @copyright Copyright (c) 2020 cleverstone
 */

namespace app\components;

use yii\base\Component;
use yii\db\Connection;
use yii\caching\CacheInterface;
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
     * @var string 用户表
     */
    public $adminUserTable = '{{%admin_user}}';

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
     * 加载本地菜单项
     * @return array
     */
    public function loadMenuItems()
    {
        return load_file(dirname(__DIR__) . '/admin/config/menu.php', true, false, true);
    }

    /**
     * 格式化本地菜单项
     * @param array $items 菜单项
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
    public function formatItem($items)
    {
        $defaultItem = [
            'label' => '--',
            'src' => '--',
            'icon' => '--',
            'label_type' => 1,
            'link_type' => 1,
            'dump_way' => '_self',
            'desc' => '',
            'sort' => 0,
            'items' => [],
        ];

        return array_merge($defaultItem, $items);
    }

    /**
     * {@inheritdoc}
     */
    public function checkAccess($userId, $permissionName, $params = [])
    {
        return true;
    }
}