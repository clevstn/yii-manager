<?php
/**
 * @copyright Copyright (c) 2020 cleverstone
 */

namespace app\components;

use yii\rbac\Item;
use yii\rbac\Rule;
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
     * @var string the name of the table storing authorization items. Defaults to "auth_item".
     */
    public $itemTable = '{{%auth_item}}';
    /**
     * @var string the name of the table storing authorization item hierarchy. Defaults to "auth_item_child".
     */
    public $itemChildTable = '{{%auth_item_child}}';
    /**
     * @var string the name of the table storing authorization item assignments. Defaults to "auth_assignment".
     */
    public $assignmentTable = '{{%auth_assignment}}';

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
     * @var Item[] all auth items (name => Item)
     */
    protected $items;
    /**
     * @var Rule[] all auth rules (name => Rule)
     */
    protected $rules;
    /**
     * @var array auth item parent-child relationships (childName => list of parents)
     */
    protected $parents;
    /**
     * @var array user assignments (user id => Assignment[])
     * @since `protected` since 2.0.38
     */
    protected $checkAccessAssignments = [];

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
    protected function removeRule($rule)
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
    protected function updateRule($name, $rule)
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
    public function add($object)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function remove($object)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function update($name, $object)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getRole($name)
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
    public function getPermission($name)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getPermissions()
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
    public function getRule($name)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getRules()
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
    public function removeAllRules()
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