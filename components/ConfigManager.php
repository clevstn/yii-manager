<?php
/**
 * @copyright Copyright (c) 2020 cleverstone
 */

namespace app\components;

use yii\base\Component;
use yii\db\Connection;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * 应用配置管理器
 * @author cleverstone
 * @since ym1.0
 */
class ConfigManager extends Component
{
    /**
     * @var Connection|array|string the DB connection object or the application component ID of the DB connection.
     * After the DbManager object is created, if you want to change this property, you should only assign it
     * with a DB connection object.
     * Starting from version 2.0.2, this can also be a configuration array for creating the object.
     */
    public $db = 'db';

    /**
     * 缓冲器
     * @var null|\yii\caching\CacheInterface
     */
    public $cache;

    /**
     * 缓存key
     * @var string
     */
    public $cacheKey = 'app-config';

    /**
     * 缓存项
     * @var array
     */
    public $items;

    /**
     * 数据模型
     * @var string
     */
    public $model = 'app\\models\\SystemConfig';

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
     * 获取一个：
     * - WEBSITE_GROUP.WEBSITE_SWITCH
     *
     * 获取多个:
     * - WEBSITE_GROUP.*
     *
     * 获取多个:
     * - WEBSITE_GROUP
     *
     * 获取全部:
     * - null
     *
     * @param string|null $key 一个配置的key
     * @param null $default
     * @return null|string|array
     * @throws
     */
    public function get($key = null, $default = null)
    {
        list($group, $code) = $this->formatKey($key);

        $this->loadFromCache();

        if ($this->items !== null) {
            $items = $this->items;
        } else {
            $items = $this->getFromDb();
            $this->items = $items;
        }

        if (empty($group) && empty($code)) {
            return $items ?: $default;
        } elseif (empty($code)) {
            return ArrayHelper::getValue($items, $group) ?: $default;
        } else {
            $result = ArrayHelper::getValue($items, "{$group}.{$code}.value");
            if (
                $result === ''
                || $result === null
                || $result === '[]'
                || $result === '{}'
            ) {
                return $default;
            }

            return $result;
        }
    }

    /**
     * 更改配置
     * @param array $config 配置项
     * @return bool|string
     */
    public function set(array $config)
    {
        $trans = $this->db->beginTransaction();
        /* @var \app\models\SystemConfig $class */
        $class = $this->model;
        try {
            foreach ($config as $code => $value) {
                $one = $class::findOne(['code' => $code]);
                if (empty($one)) {
                    throw new \Exception(t('the parameter {param} is not defined', 'app.admin', ['param' => $code]));
                }

                $one->value = $value;
                if (!$one->save()) {
                    throw new \Exception($one->error);
                }
            }

            $trans->commit();
            // 清除缓存
            $this->invalidateCache();

            return true;
        } catch (\Exception $e) {
            $trans->rollBack();
            return $e->getMessage();
        }
    }

    /**
     * 清除所有权限缓存
     */
    public function invalidateCache()
    {
        if ($this->cache !== null) {
            $this->cache->delete($this->cacheKey);
        }

        $this->items = null;
    }

    /**
     * 格式key
     * @param string $key
     * ```php
     *  - WEBSITE_GROUP.WEBSITE_SWITCH
     *  - WEBSITE_GROUP.*
     *  - WEBSITE_GROUP
     *  - null
     * ```
     * @return array
     */
    protected function formatKey($key)
    {
        if ($key && ($pos = strrpos($key, '.')) !== false) {
            $a = substr($key, 0, $pos);
            $b = substr($key, $pos + 1);
            if ($b == '*' || $b === false || trim($b) === '') {
                $b = null;
            }
        } elseif ($key) {
            $a = $key;
            $b = null;
        } else {
            $a = null;
            $b = null;
        }

        return [$a, $b];
    }

    /**
     * 从DB获取
     * @return array
     */
    public function getFromDb()
    {
        /* @var \app\models\SystemConfig $class */
        $class = $this->model;

        $all = $class::find()->select('*')->where(['type' => $class::TYPE_CONFIG])->all();

        $items = ArrayHelper::index($all, 'code', 'group');

        return $items;
    }

    /**
     * 从缓存中加载项
     */
    protected function loadFromCache()
    {
        if ($this->items !== null || !$this->cache instanceof \yii\caching\CacheInterface) {
            return;
        }

        $data = $this->cache->get($this->cacheKey);
        if (!empty($data)) {
            $this->items = $data;
            return;
        }

        $items = $this->getFromDb();
        $this->items = $items;

        $this->cache->set($this->cacheKey, $items);
    }
}