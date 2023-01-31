<?php
/**
 * @copyright Copyright (c) 2020 cleverstone
 */

namespace app\components;

use yii\db\Connection;
use yii\rbac\BaseManager;
use yii\caching\CacheInterface;

/**
 * rbac组件
 * @author cleverstone
 * @since ym1.0
 */
class RbacManager extends BaseManager
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
     * {@inheritdoc}
     */
    protected function getItem($name)
    {

    }

    /**
     * {@inheritdoc}
     */
    protected function getItems($type)
    {

    }

    /**
     * {@inheritdoc}
     */
    protected function addItem($item)
    {

    }

    /**
     * {@inheritdoc}
     */
    protected function addRule($rule)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function removeRule($rule)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function updateRule($name, $rule)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getRule($name)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getRules()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function removeAllRules()
    {

    }

    /**
     * {@inheritdoc}
     */
    protected function removeItem($item)
    {

    }

    /**
     * {@inheritdoc}
     */
    protected function updateItem($name, $item)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function createRole($name)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function createPermission($name)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getRolesByUser($userId)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getChildRoles($roleName)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getPermissionsByRole($roleName)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getPermissionsByUser($userId)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function canAddChild($parent, $child)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function addChild($parent, $child)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function removeChild($parent, $child)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function removeChildren($parent)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function hasChild($parent, $child)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getChildren($name)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function assign($role, $userId)
    {

    }

    /**
     * 清除缓存
     * @return bool
     */
    public function invalidateCache()
    {
        if ($this->cache !== null) {
            $this->cache->delete($this->cacheKey);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function revoke($role, $userId)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function revokeAll($userId)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getAssignment($roleName, $userId)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getAssignments($userId)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getUserIdsByRole($roleName)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function removeAll()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function removeAllPermissions()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function removeAllRoles()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function removeAllAssignments()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function checkAccess($userId, $permissionName, $params = [])
    {

    }
}