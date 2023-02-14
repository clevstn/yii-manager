<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\components;

use Yii;
use yii\base\Component;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\db\Exception;
use yii\di\Instance;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yii\base\DynamicModel;
use http\Exception\UnexpectedValueException;

/**
 * 附件上传组件
 *
 * ```php
 * //配置
 * components = [
 * // 文件上传组件
 *  'uploads' => [
 *      'class' => 'app\components\Uploads',
 *      'type' => \app\components\Uploads::LOCAL_UPLOAD_ENGINE_SYMBOL,
 *      'configs' => [
 *      'rootUrl' => '@web/upload', // 外链域名
 *      'rootPath' => '@webroot' . DIRECTORY_SEPARATOR . 'upload', // 上传地址
 *      // 其他配置，后期接云存储自行配置
 *      ],
 *      // 其他配置项参见当前类的公共属性定义。
 *  ],
 * ]
 * ```
 *
 * ```php
 *  // 调用
 *  \YII::$app->uploads->execute($name)
 * ```
 *
 * @author cleverstone
 * @since ym1.0
 */
class Uploads extends Component
{
    /* 上传场景：图片、文件、音频、视频 */
    const SCENARIO_IMAGE = 'image';
    const SCENARIO_FILE = 'file';
    const SCENARIO_AUDIO = 'audio';
    const SCENARIO_VIDEO = 'video';

    /* 图片存储空间名 */
    const IMAGE_STORAGE_BUCKET = 'image';
    /* 文件存储空间名 */
    const FILE_STORAGE_BUCKET = 'file';
    /* 音频存储空间名 */
    const AUDIO_STORAGE_BUCKET = 'audio';
    /* 视频存储空间名 */
    const VIDEO_STORAGE_BUCKET = 'video';

    /* 上传引擎标识 */
    const LOCAL_UPLOAD_ENGINE_SYMBOL = 'LOCAL';
    const QINIU_UPLOAD_ENGINE_SYMBOL = 'QINIU';

    // 图片验证规则
    private $_imageRules = [];
    // 文件验证规则
    private $_fileRules = [];
    // 音频验证规则
    private $_audioRules = [];
    // 视频验证规则
    private $_videoRules = [];

    // 图片支持的扩展名
    public $imageSupportExt = ['png', 'jpg', 'jpeg', 'gif', 'webp', 'svg',];
    // 文件支持的扩展名
    public $fileSupportExt = ['zip', 'rar', 'txt', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'exe', 'log', 'msi',];
    // 音频支持的扩展名
    public $audioSupportExt = ['cda', 'wav', 'mp3', 'aiff', 'aif', 'mid', 'wma', 'ra', 'vqf', 'ape',];
    // 视频支持的扩展名
    public $videoSupportExt = ['avi', 'mov', 'rmvb', 'rm', 'flv', 'mp4', '3gp',];

    // 图片大小最大值(字节)
    public $imageMaxSize = 1024 * 1024 * 10;
    // 文件大小最大值(字节)
    public $fileMaxSize = 1024 * 1024 * 50;
    // 音频大小最大值(字节)
    public $audioMaxSize = 1024 * 1024 * 50;
    // 视频大小最大值(字节)
    public $videoMaxSize = 1024 * 1024 * 50;

    // 同时上传最大数量
    public $attachMaxFiles = 10;

    /**
     * 上传引擎
     * - self::LOCAL_UPLOAD_ENGINE_SYMBOL 本地
     * - self::QINIU_UPLOAD_ENGINE_SYMBOL 七牛云
     * @var string
     */
    public $type = self::LOCAL_UPLOAD_ENGINE_SYMBOL;

    /**
     * 配置项
     * @var array
     * 当`type`是`self::LOCAL_UPLOAD_ENGINE_SYMBOL`
     * - `rootUrl` string 文件的根域名
     * - `rootPath` string 文件的根路径
     *
     * 当`type`是`self::QINIU_UPLOAD_ENGINE_SYMBOL`
     *
     */
    public $configs;

    /**
     * 附件数据模型
     * @var string|ActiveRecord|\app\builder\contract\AttachmentModelInterface
     */
    public $attachmentModel = 'app\models\Attachment';

    /**
     * @var Connection|array|string the DB connection object or the application component ID of the DB connection.
     * After the DbManager object is created, if you want to change this property, you should only assign it
     * with a DB connection object.
     * Starting from version 2.0.2, this can also be a configuration array for creating the object.
     */
    public $db = 'db';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        empty($this->_imageRules) && $this->setImageRules();
        empty($this->_fileRules) && $this->setFileRules();
        empty($this->_audioRules) && $this->setAudioRules();
        empty($this->_videoRules) && $this->setVideoRules();

        // 如果`type`是函数，则执行
        if (is_function($this->type)) {
            $this->type = call_user_func($this->type);
        }

        $this->db = Instance::ensure($this->db, Connection::class);
        $this->attachmentModel = Instance::ensure($this->attachmentModel, 'app\\builder\\contract\\AttachmentModelInterface');
    }

    /**
     * 上传附件
     * @param string $name 附件字段名，如：photo
     * @param array $config 配置项
     * - string type 分类名称
     * - string save_directory 保存目录，如：order
     * - string path_prefix 路径前缀，如：100.1.0
     * - string|null scenario 上传附件类型场景，如：self::SCENARIO_IMAGE
     * - boolean|int isBase64 是否是base64字符串
     *
     * @return string|array
     * @throws
     */
    public function execute($name, array $config = [])
    {
        $config = notset_set_default($config, [
            'type' => '未定义',
            'save_directory' => 'common',
            'path_prefix' => 'default',
            'scenario' => '',
            'isBase64' => 0,
        ]);

        switch ($this->type) {
            case self::LOCAL_UPLOAD_ENGINE_SYMBOL: // 本地
                if (!$config['isBase64']) {
                    return $this->uploadAttachmentLocalForBinary($name, $config);
                }

                return $this->uploadAttachmentLocalForBase64($name, $config);
            case self::QINIU_UPLOAD_ENGINE_SYMBOL: // 七牛
                if (!$config['isBase64']) {
                    return $this->uploadAttachmentQiniuForBinary($name, $config);
                }

                return $this->uploadAttachmentQiniuForBase64($name, $config);
            default:
                throw new UnexpectedValueException(t('the upload engine is not defined'));
        }
    }

    /**
     * 二进制上传附件(七牛云)
     * @param string $name 上传字段名
     * @param array $config 配置项
     * - string type 分类名称
     * - string save_directory 保存目录，如：order
     * - string path_prefix 路径前缀，如：100.1.0
     * - string|null scenario 上传附件类型场景，如：self::SCENARIO_IMAGE
     *
     * @return array|string
     */
    protected function uploadAttachmentQiniuForBinary($name, array $config)
    {
        return [];
    }

    /**
     * Base64上传附件(七牛云)
     * @param string $name 上传字段名
     * @param array $config 配置项
     * - string type 分类名称
     * - string save_directory 保存目录，如：order
     * - string path_prefix 路径前缀，如：100.1.0
     * - string|null scenario 上传附件类型场景，如：self::SCENARIO_IMAGE
     * @return array|string
     */
    protected function uploadAttachmentQiniuForBase64($name, array $config)
    {
        return [];
    }

    /**
     * Base64上传附件(本地)
     * @param string $name 附件字段名，如：photo
     * @param array $config 配置项
     * - string type 分类名称
     * - string save_directory 保存目录，如：order
     * - string path_prefix 路径前缀，如：100.1.0
     * - string|null scenario 上传附件类型场景，如：self::SCENARIO_IMAGE
     * - base64数据组成
     *   - `log`为客户端拼接上的文件扩展名。 拼接格式为：扩展名+英文逗号+base64字符，如下所示：
     *      log,data:application/octet-stream;base64,ICdodHRwczovL2x4LmRhbWFuemouY29tL2FkbWluLnBocD9zPS9vcmRlci9pbmRleC...
     *
     * @return array|string
     * @throws \Exception
     */
    protected function uploadAttachmentLocalForBase64($name, array $config)
    {
        $scenario = $config['scenario'];

        $fileBase64Map = (array)Yii::$app->request->post($name);
        $validateCountsResult = $this->validateFilesCountsForBase64($fileBase64Map);
        if ($validateCountsResult !== true) {
            return $validateCountsResult;
        }

        $saveMap = [];
        foreach ($fileBase64Map as $originalFileBase64) {
            $map = $this->base64DataSplit($originalFileBase64);
            if (is_string($map)) {
                return $map;
            }

            // 文件上传校验
            $ext = mb_strtolower($map['extension']);
            $data = $map['data'];
            if (empty($scenario)) {
                $scenario = $this->getScenarioByExtension($ext);
            }

            $size = strlen($data);
            $validateResult = $this->uploadValidateForBase64($ext, $size, $scenario);
            if ($validateResult !== true) {
                return $validateResult;
            }

            $bucket = $this->getBucketByScenario($scenario);
            // 获取文件保存路径
            $savePath = $this->generateAttachmentSavePath($bucket, $config['save_directory'], $config['path_prefix']);
            // 递归创建目录
            $ifSuccess = FileHelper::createDirectory($savePath);
            if (!$ifSuccess) {
                $this->unlinkFile(array_column($saveMap, 'filePath'));

                return "Failed to create directory {$savePath}.";
            }

            $fileSavePath = $this->getUniqueFilenamePath($savePath, $ext);
            $uploadResult = file_put_contents($fileSavePath, $data);
            if ($uploadResult === false) {
                $this->unlinkFile(array_column($saveMap, 'filePath'));

                return t('failed to upload the file for unknown reasons');
            }

            array_push($saveMap, [
                'filePath' => $fileSavePath,
                'type' => $config['type'],
                'bucket' => $bucket,
                'save_directory' => $config['save_directory'],
                'path_prefix' => $config['path_prefix'],
                'basename' => pathinfo($fileSavePath, PATHINFO_BASENAME),
                'size' => $size,
                'ext' => $ext,
                'mime' => FileHelper::getMimeTypeByExtension($ext) ?: '',
                'hash' => hash_file('md5', $fileSavePath),
            ]);
        }

        return $this->saveAttachmentToDb($saveMap);
    }

    /**
     * 保存附件到数据库中
     * @param array $data 附件数据
     * @return array|string
     * @throws \Exception
     */
    protected function saveAttachmentToDb(array $data)
    {
        $transaction = $this->db->beginTransaction();

        $id = [];
        try {
            foreach ($data as $item) {
                $model = $this->attachmentModel->saveData($item);
                if ($model->hasErrors()) {
                    throw new \Exception($model->error);
                }

                array_push($id, $model->id);
            }

            $transaction->commit();

            return $id;
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->unlinkFile(array_column($data, 'filePath'));

            return $e->getMessage();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            $this->unlinkFile(array_column($data, 'filePath'));

            return $e->getMessage();
        }
    }

    /**
     * 获取唯一的文件名路径
     * @param string $savePath 文件保存的路径
     * @param string $ext 文件扩展名
     * @return string
     * @throws \yii\base\Exception
     */
    protected function getUniqueFilenamePath($savePath, $ext)
    {
        $filename = $this->generateFilename();
        $fileSavePath = $savePath . $filename . '.' . $ext;
        if (is_file($fileSavePath)) {
            return $this->getUniqueFilenamePath($savePath, $ext);
        }

        return $fileSavePath;
    }

    /**
     * 校验文件最大数量是否合法
     * @param array $filesBase64Map 文件数据集合
     * @return true|string
     */
    protected function validateFilesCountsForBase64($filesBase64Map)
    {
        settype($filesBase64Map, 'array');

        if (count($filesBase64Map) == 0) {
            return t('{param} can not be empty', 'app.admin', ['param' => 'files']);
        }

        if (count($filesBase64Map) > $this->attachMaxFiles) {
            return t('The file "{file}" is too big. Its size cannot exceed {formattedLimit}.', 'yii', ['file' => '--', 'formattedLimit' => $this->attachMaxFiles]);
        }

        return true;
    }

    /**
     * base64文件数据校验
     * @param string $ext 文件扩展名
     * @param int $size 文件大小（b）
     * @param string $scenario 上传文件类型场景
     * @return true|string
     */
    protected function uploadValidateForBase64($ext, $size, $scenario)
    {
        // 校验扩展名是否支持
        if (empty($scenario)) {
            $scenario = $this->getScenarioByExtension($ext);
            $extValidateResult = $this->validateExtensionByExt($ext);
        } else {
            $extValidateResult = $this->validateExtensionByScenario($ext, $scenario);
        }

        if ($extValidateResult !== true) {
            return $extValidateResult;
        }

        // 校验文件大小
        $sizeValidateResult = $this->validateMaxSize($scenario, $size);
        if ($sizeValidateResult !== true) {
            return $sizeValidateResult;
        }

        return true;
    }

    /**
     * 通过场景校验文件扩展名
     * @param string $extension 文件扩展名
     * @param string $scenario 场景
     * @return true|string
     */
    protected function validateExtensionByScenario($extension, $scenario)
    {
        switch ($scenario) {
            case self::SCENARIO_IMAGE:
                $result = in_array($extension, array_map('mb_strtolower', $this->imageSupportExt));
                break;
            case self::SCENARIO_FILE:
                $result = in_array($extension, array_map('mb_strtolower', $this->fileSupportExt));
                break;
            case self::SCENARIO_AUDIO:
                $result = in_array($extension, array_map('mb_strtolower', $this->audioSupportExt));
                break;
            case self::SCENARIO_VIDEO:
                $result = in_array($extension, array_map('mb_strtolower', $this->videoSupportExt));
                break;
            default:
                throw new \InvalidArgumentException(t('the file upload scenario is incorrect'));
        }

        return $result ?: t('the file {filename} extension is not supported', 'app', ['filename' => '--']);
    }

    /**
     * 校验文件数据大小
     * @param string $scenario 上传类型场景
     * @param int $size 文件大小(B)
     * @return bool
     */
    protected function validateMaxSize($scenario, $size)
    {
        switch ($scenario) {
            case self::SCENARIO_FILE:
                $maxSize = $this->fileMaxSize;
                $result = $size <= $maxSize;
                break;
            case self::SCENARIO_AUDIO:
                $maxSize = $this->audioMaxSize;
                $result = $size <= $maxSize;
                break;
            case self::SCENARIO_VIDEO:
                $maxSize = $this->videoMaxSize;
                $result = $size <= $maxSize;
                break;
            case self::SCENARIO_IMAGE:
                $maxSize = $this->imageMaxSize;
                $result = $size <= $maxSize;
                break;
            default:
                throw new \InvalidArgumentException(t('the file upload scenario is incorrect'));
        }

        return $result?: t('The file "{file}" is too big. Its size cannot exceed {formattedLimit}.', 'yii', ['file' => '--', 'formattedLimit' => $maxSize]);
    }

    /**
     * 通过扩展名校验文件的扩展名是否合法
     * @param string $ext 文件扩展名
     * @return bool|string
     */
    protected function validateExtensionByExt($ext)
    {
        $scenario = $this->getScenarioByExtension($ext);
        return $scenario ? true : t('the file {filename} extension is not supported', 'app', ['filename' => '--']);
    }

    /**
     * base64数据分离
     * @param string $originalFileBase64 base64原始数据
     * - base64数据组成
     *   // `log`为客户端拼接上的文件扩展名。 拼接格式为：扩展名+英文逗号+base64字符
     *   log,data:application/octet-stream;base64,ICdodHRwczovL2x4LmRhbWFuemouY29tL2FkbWluLnBocD9zPS9vcmRlci9pbmRleC...
     *
     * @return array|string
     */
    protected function base64DataSplit($originalFileBase64)
    {
        $fileBase64 = str_replace([':', ';'], ',', $originalFileBase64);
        $fileBase64Array = explode(',', $fileBase64);
        $string = $fileBase64Array[2];
        if ($string === 'base64') {
            return t('concatenate the extension before the string');
        }

        $extension = $fileBase64Array[0];
        $mineType = $fileBase64Array[2];
        $fileData = $fileBase64Array[4];

        if (empty($extension)) {
            // //通过`mimeType`获取扩展名，会出现多个扩展名。
            // //有些文件上传后扩展名会不统一。
            // $extensionArray = FileHelper::getExtensionsByMimeType($mineType);
            // if (empty($extensionArray)) {
            //    return t('the file type cannot be uploaded');
            // }

            // reset($extensionArray);
            // //文件扩展名
            // $extension = current($extensionArray);
            return t('concatenate the extension before the string');
        }

        // 文件二进制数据
        $fileBinaryData = base64_decode($fileData);
        return ['extension' => $extension, 'data' => $fileBinaryData];
    }

    /**
     * 二进制上传附件(本地)
     * @param string $name 附件字段名，如：photo
     * @param array $config 配置项
     * - string type 分类名称
     * - string save_directory 保存目录，如：order
     * - string path_prefix 路径前缀，如：100.1.0
     * - string|null scenario 上传附件类型场景，如：self::SCENARIO_IMAGE
     * @return string|array
     * @throws \Exception
     * @throws \yii\base\InvalidConfigException
     */
    protected function uploadAttachmentLocalForBinary($name, array $config)
    {
        $scenario = $config['scenario'];
        // 文件实例集合
        $uploadedFileInstanceMap = UploadedFile::getInstancesByName($name);
        // 上传前校验
        $validateResult = $this->validateFiles($scenario, $uploadedFileInstanceMap);
        if ($validateResult !== true) {
            return $validateResult;
        }

        $data = [];
        /* @param UploadedFile $uploadedFileInstance */
        foreach ($uploadedFileInstanceMap as $uploadedFileInstance) {
            if (empty($scenario)) {
                $scenario = $this->getScenarioByInstance($uploadedFileInstance);
            }

            $bucket = $this->getBucketByScenario($scenario);
            // 获取文件保存路径
            $savePath = $this->generateAttachmentSavePath($bucket, $config['save_directory'], $config['path_prefix']);
            // 递归创建目录
            $ifSuccess = FileHelper::createDirectory($savePath);
            if (!$ifSuccess) {
                $this->unlinkFile(array_column($data, 'filePath'));

                return "Failed to create directory {$savePath}.";
            }

            $ext = $uploadedFileInstance->extension;
            $fileSavePath = $this->getUniqueFilenamePath($savePath, $ext);
            $uploadResult = $uploadedFileInstance->saveAs($fileSavePath);
            if (!$uploadResult) {
                $this->unlinkFile(array_column($data, 'filePath'));

                if ($uploadedFileInstance->hasError) {
                    return 'File upload error and status code ' . $uploadedFileInstance->error;
                }

                return t('failed to upload the file for unknown reasons');
            }

            array_push($data, [
                'filePath' => $fileSavePath,
                'type' => $config['type'],
                'bucket' => $bucket,
                'save_directory' => $config['save_directory'],
                'path_prefix' => $config['path_prefix'],
                'basename' => pathinfo($fileSavePath, PATHINFO_BASENAME),
                'size' => $uploadedFileInstance->size,
                'ext' => $uploadedFileInstance->extension,
                'mime' => $uploadedFileInstance->type,
                'hash' => hash_file('md5', $fileSavePath),
            ]);
        }

        return $this->saveAttachmentToDb($data);
    }

    /**
     * 上传校验
     * @param string|null $scenario 场景
     * @param UploadedFile[] $files
     * @return true|string
     * @throws \yii\base\InvalidConfigException
     */
    protected function validateFiles($scenario, $files)
    {
        if (empty($scenario)) {
            foreach ($files as $file) {
                $scenario = $this->getScenarioByInstance($file);
                if ($scenario === false) {
                    return t('the file {filename} extension is not supported', 'app', ['filename' => $file->baseName]);
                }

                $rule = $this->getRulesByScenario($scenario);
                $model = DynamicModel::validateData(compact('files'), $rule);
                if ($model->hasErrors()) {
                    return current($model->firstErrors) ?: '';
                }
            }
        } else {
            $rule = $this->getRulesByScenario($scenario);
            $model = DynamicModel::validateData(compact('files'), $rule);
            if ($model->hasErrors()) {
                return current($model->firstErrors) ?: '';
            }
        }

        return true;
    }

    /**
     * 根据上传附件类型场景获取验证规则
     * @param string $scenario 上传附件类型场景
     * @return array
     */
    protected function getRulesByScenario($scenario)
    {
        /* @param UploadedFile $uploadedFileInstance */
        switch ($scenario) {
            case self::SCENARIO_FILE:
                $rule = $this->getFileRules();
                break;
            case self::SCENARIO_AUDIO:
                $rule = $this->getAudioRules();
                break;
            case self::SCENARIO_VIDEO:
                $rule = $this->getVideoRules();
                break;
            case self::SCENARIO_IMAGE:
            default:
                $rule = $this->getImageRules();
        }

        return $rule;
    }

    /**
     * 获取图片验证规则
     * @return array
     */
    public function getImageRules()
    {
        return $this->_imageRules;
    }

    /**
     * 获取文件验证规则
     * @return array
     */
    public function getFileRules()
    {
        return $this->_fileRules;
    }

    /**
     * 获取音频验证规则
     * @return array
     */
    public function getAudioRules()
    {
        return $this->_audioRules;
    }

    /**
     * 获取视频验证规则
     * @return array
     */
    public function getVideoRules()
    {
        return $this->_videoRules;
    }

    /**
     * 设置图片验证规则
     * @param array $rules 验证规则
     * @return $this
     */
    public function setImageRules($rules = [])
    {
        if (!empty($rules)) {
            $this->_imageRules = $rules;
        }

        $this->_imageRules = [
            [
                'files',
                'image',
                'skipOnEmpty' => false,
                'extensions' => implode(',', $this->imageSupportExt),
                // 最大10M
                'maxSize' => $this->imageMaxSize,
                'maxFiles' => $this->attachMaxFiles,
            ],
        ];

        return $this;
    }

    /**
     * 设置文件验证规则
     * @param array $rules 验证规则
     * @return $this
     */
    public function setFileRules($rules = [])
    {
        if (!empty($rules)) {
            $this->_fileRules = $rules;
        }

        $this->_fileRules = [
            [
                'files',
                'file',
                'skipOnEmpty' => false,
                'extensions' => implode(',', $this->fileSupportExt),
                // 最大10M
                'maxSize' => $this->fileMaxSize,
                'maxFiles' => $this->attachMaxFiles,
            ],
        ];

        return $this;
    }

    /**
     * 设置音频验证规则
     * @param array $rules 验证规则
     * @return $this
     */
    public function setAudioRules($rules = [])
    {
        if (!empty($rules)) {
            $this->_audioRules = $rules;
        }

        $this->_audioRules = [
            [
                'files',
                'file',
                'skipOnEmpty' => false,
                'extensions' => implode(',', $this->audioSupportExt),
                // 最大10M
                'maxSize' => $this->audioMaxSize,
                'maxFiles' => $this->attachMaxFiles,
            ],
        ];

        return $this;
    }

    /**
     * 设置视频验证规则
     * @param array $rules 验证规则
     * @return $this
     */
    public function setVideoRules($rules = [])
    {
        if (!empty($rules)) {
            $this->_videoRules = $rules;
        }

        $this->_videoRules = [
            [
                'files',
                'file',
                'skipOnEmpty' => false,
                'extensions' => implode(',', $this->videoSupportExt),
                // 最大10M
                'maxSize' => $this->videoMaxSize,
                'maxFiles' => $this->attachMaxFiles,
            ],
        ];

        return $this;
    }

    /**
     * 附件外链
     * @param string $bucket 空间名
     * @param string $saveDirectory 保存目录
     * @param string $pathPrefix 路径前缀
     * @param string $basename 文件名
     * @return string
     */
    public function getAttachmentUrl($bucket, $saveDirectory, $pathPrefix, $basename)
    {
        $relativePath = $saveDirectory . '/' . $this->getPathPrefix($pathPrefix, '/') . '/' . $basename;
        $attachmentUrl = rtrim(Yii::getAlias($this->configs['rootUrl']), '/') . '/' . $bucket . '/' . $relativePath;

        return into_full_url($attachmentUrl);
    }

    /**
     * 根据mimeType获得附件上传类型场景
     * @param string $mimeType
     * @return string
     */
    public function getScenarioByMimeType($mimeType)
    {
        if (strpos($mimeType, 'image/') !== false) {
            return self::SCENARIO_IMAGE;
        }

        if (strpos($mimeType, 'video/') !== false) {
            return self::SCENARIO_VIDEO;
        }

        if (strpos($mimeType, 'audio/') !== false) {
            return self::SCENARIO_AUDIO;
        }

        return self::SCENARIO_FILE;
    }

    /**
     * 删除附件
     * @param string|array $filepath 文件路径
     * @return boolean
     * @throws \Exception
     */
    public function unlinkFile($filepath)
    {
        switch ($this->type) {
            case self::LOCAL_UPLOAD_ENGINE_SYMBOL:
                try {
                    if (is_array($filepath)) {
                        foreach ($filepath as $path) {
                            if (is_file($path)) {
                                @unlink($path);
                            }
                        }
                    } else {
                        if (is_file($filepath)) {
                            @unlink($filepath);
                        }
                    }
                } catch (\Throwable $e) {
                    if (!$e instanceof \Exception) {
                        throw new \Exception($e->getMessage());
                    }
                }

                return true;
            case self::QINIU_UPLOAD_ENGINE_SYMBOL:
                return true;
            default:
                throw new UnexpectedValueException(t('the upload engine is not defined'));
        }
    }

    /**
     * 根据上传文件实例对象获取文件上传的类型场景
     * @param UploadedFile $uploadedFileInstance 上传文件实例对象
     * @return false|string
     */
    protected function getScenarioByInstance(UploadedFile $uploadedFileInstance)
    {
        $ext = $uploadedFileInstance->extension;

        return $this->getScenarioByExtension($ext);
    }

    /**
     * 根据上传文件的扩展名获取上传类型场景
     * @param string $extension 扩展名
     * @return false|string
     */
    protected function getScenarioByExtension($extension)
    {
        if (in_array($extension, array_map('mb_strtolower', $this->imageSupportExt))) {
            return self::SCENARIO_IMAGE;
        }

        if (in_array($extension, array_map('mb_strtolower', $this->fileSupportExt))) {
            return self::SCENARIO_FILE;
        }

        if (in_array($extension, array_map('mb_strtolower', $this->audioSupportExt))) {
            return self::SCENARIO_AUDIO;
        }

        if (in_array($extension, array_map('mb_strtolower', $this->videoSupportExt))) {
            return self::SCENARIO_VIDEO;
        }

        return false;
    }

    /**
     * 生成文件名
     * @return string
     * @throws \yii\base\Exception
     */
    protected function generateFilename()
    {
        return date('YmdHis') . random_string(true, 10);
    }

    /**
     * 根据场景获取上传空间名
     * @param string $scenario 上传文件类型场景
     * @return string
     */
    protected function getBucketByScenario($scenario)
    {
        switch ($scenario) {
            case self::SCENARIO_IMAGE:
                $bucket = self::IMAGE_STORAGE_BUCKET;
                break;
            case self::SCENARIO_FILE:
                $bucket = self::FILE_STORAGE_BUCKET;
                break;
            case self::SCENARIO_AUDIO:
                $bucket = self::AUDIO_STORAGE_BUCKET;
                break;
            case self::SCENARIO_VIDEO:
                $bucket = self::VIDEO_STORAGE_BUCKET;
                break;
            default:
                throw new \InvalidArgumentException(t('the file upload scenario is incorrect'));
        }

        return $bucket;
    }

    /**
     * 获取文件路径前缀
     * @param string $pathPrefix 文件路径前缀（待处理）
     * @param string $separator 路径分隔符
     * @return array|string|string[]|null
     */
    protected function getPathPrefix($pathPrefix, $separator = DIRECTORY_SEPARATOR)
    {
        $_pathPrefix = '';
        if (!empty($pathPrefix)) {
            $_pathPrefix = preg_replace('%[.\-]%', $separator, trim($pathPrefix, '.-'));
        }

        return $_pathPrefix;
    }

    /**
     * 获取附件完整的保存路径
     * @param string $bucket 上传空间
     * @param string $saveDirectory 保存目录，如：order
     * @param string $pathPrefix 路径前缀（如：100.10.1）
     * @return false|string
     */
    protected function generateAttachmentSavePath($bucket, $saveDirectory, $pathPrefix)
    {
        $_pathPrefix = $this->getPathPrefix($pathPrefix);

        $savePath = '';
        // 拼接上传地址
        $savePath .= rtrim($this->configs['rootPath'], '\\/') . DIRECTORY_SEPARATOR;
        // 拼接上传空间
        $savePath .= $bucket . DIRECTORY_SEPARATOR;
        // 拼接保存目录
        $savePath .= $saveDirectory . DIRECTORY_SEPARATOR;
        if (!empty($_pathPrefix)) {
            // 拼接路径前缀
            $savePath .= $_pathPrefix . DIRECTORY_SEPARATOR;
        }

        return strtr(Yii::getAlias(str_replace('\\', '/', $savePath)), '/', DIRECTORY_SEPARATOR);
    }
}