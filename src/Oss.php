<?php
/**
 * @user: thanatos
 */

namespace thanatos\oss;

use OSS\Core\OssException;
use OSS\Http\ResponseCore;
use OSS\Model\CorsConfig;
use OSS\Model\GetLiveChannelHistory;
use OSS\Model\GetLiveChannelInfo;
use OSS\Model\GetLiveChannelStatus;
use OSS\Model\LifecycleConfig;
use OSS\Model\ListMultipartUploadInfo;
use OSS\Model\ListPartsInfo;
use OSS\Model\LiveChannelInfo;
use OSS\Model\LiveChannelListInfo;
use OSS\Model\LoggingConfig;
use OSS\Model\ObjectListInfo;
use OSS\Model\RefererConfig;
use OSS\Model\WebsiteConfig;
use OSS\OssClient;
use thanatos\oss\result\PutObjectResult;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\validators\UrlValidator;

/**
 * Class Oss
 * @property string $bucket
 * @property OssClient $client
 * @method string getObjectAcl(string $object) see [[OssClient::getObjectAcl]] for more info
 * @method PutObjectResult putObjectAcl(string $object, string $acl) see [[OssClient::putObjectAcl]] for more info
 * @method LoggingConfig getBucketLogging(array $options = NULL) see [[OssClient::getBucketLogging]] for more info
 * @method PutObjectResult putBucketLogging(string $targetBucket, string $targetPrefix, array $options = NULL) see [[OssClient::putBucketLogging]] for more info
 * @method PutObjectResult putBucketWebsite(WebsiteConfig $websiteConfig, array $options = NULL) see [[OssClient::putBucketWebsite]] for more info
 * @method WebsiteConfig getBucketWebsite(array $options = NULL) see [[OssClient::getBucketWebsite]] for more info
 * @method PutObjectResult deleteBucketWebsite(array $options = NULL) see [[OssClient::deleteBucketWebsite]] for more info
 * @method PutObjectResult putBucketCors(CorsConfig $corsConfig, array $options = NULL) see [[OssClient::putBucketCors]] for more info
 * @method CorsConfig getBucketCors(array $options = NULL) see [[OssClient::getBucketCors]] for more info
 * @method PutObjectResult deleteBucketCors(array $options = NULL) see [[OssClient::deleteBucketCors]] for more info
 * @method null addBucketCname(string $cname, array $options = NULL) see [[OssClient::addBucketCname]] for more info
 * @method CnameConfig getBucketCname(array $options = NULL) see [[OssClient::getBucketCname]] for more info
 * @method PutObjectResult deleteBucketCname(string $cname, array $options = NULL) see [[OssClient::deleteBucketCname]] for more info
 * @method LiveChannelInfo putBucketLiveChannel(string $channelName, LiveChannelConfig $channelConfig, array $options = NULL) see [[OssClient::putBucketLiveChannel]] for more info
 * @method PutObjectResult putLiveChannelStatus(string $channelName, string $channelStatus, array $options = NULL) see [[OssClient::putLiveChannelStatus]] for more info
 * @method GetLiveChannelInfo getLiveChannelInfo(string $channelName, array $options = NULL) see [[OssClient::getLiveChannelInfo]] for more info
 * @method GetLiveChannelStatus getLiveChannelStatus(string $channelName, array $options = NULL) see [[OssClient::getLiveChannelStatus]] for more info
 * @method GetLiveChannelHistory getLiveChannelHistory(string $channelName, array $options = NULL) see [[OssClient::getLiveChannelHistory]] for more info
 * @method LiveChannelListInfo listBucketLiveChannels(array $options = NULL) see [[OssClient::listBucketLiveChannels]] for more info
 * @method null postVodPlaylist(string $channelName, string $playlistName, array $setTime) see [[OssClient::postVodPlaylist]] for more info
 * @method PutObjectResult deleteBucketLiveChannel(string $channelName, array $options = NULL) see [[OssClient::deleteBucketLiveChannel]] for more info
 * @method string signRtmpUrl(string $channelName, string $timeout = 60, array $options = NULL) see [[OssClient::signRtmpUrl]] for more info
 * @method array optionsObject(string $object, string $origin, string $request_method, string $request_headers, array $options = NULL) see [[OssClient::optionsObject]] for more info
 * @method PutObjectResult putBucketLifecycle(LifecycleConfig $lifecycleConfig, array $options = NULL) see [[OssClient::putBucketLifecycle]] for more info
 * @method LifecycleConfig getBucketLifecycle(array $options = NULL) see [[OssClient::getBucketLifecycle]] for more info
 * @method PutObjectResult deleteBucketLifecycle(array $options = NULL) see [[OssClient::deleteBucketLifecycle]] for more info
 * @method PutObjectResult putBucketReferer(RefererConfig $refererConfig, array $options = NULL) see [[OssClient::putBucketReferer]] for more info
 * @method RefererConfig getBucketReferer(array $options = NULL) see [[OssClient::getBucketReferer]] for more info
 * @method PutObjectResult putBucketStorageCapacity(int $storageCapacity, array $options = NULL) see [[OssClient::putBucketStorageCapacity]] for more info
 * @method int getBucketStorageCapacity(array $options = NULL) see [[OssClient::getBucketStorageCapacity]] for more info
 * @method ObjectListInfo listObjects(array $options = NULL) see [[OssClient::listObjects]] for more info
 * @method null createObjectDir(string $object, array $options = NULL) see [[OssClient::createObjectDir]] for more info
 * @method PutObjectResult putObject(string $object, string $content, array $options = NULL) see [[OssClient::putObject]] for more info
 * @method PutObjectResult putSymlink(string $symlink, string $targetObject, array $options = NULL) see [[OssClient::putSymlink]] for more info
 * @method null getSymlink(string $symlink) see [[OssClient::getSymlink]] for more info
 * @method null uploadFile(string $object, string $file, array $options = NULL) see [[OssClient::uploadFile]] for more info
 * @method int appendObject(string $object, string $content, int $position, array $options = NULL) see [[OssClient::appendObject]] for more info
 * @method int appendFile(string $object, string $file, int $position, array $options = NULL) see [[OssClient::appendFile]] for more info
 * @method array|PutObjectResult getObjectMeta(string $object, string $options = NULL) see [[OssClient::getObjectMeta]] for more info
 * @method PutObjectResult deleteObject(string $object, array $options = NULL) see [[OssClient::deleteObject]] for more info
 * @method array deleteObjects(string $objects, array $options = null) see [[OssClient::deleteObjects]] for more info
 * @method string getObject(string $object, array $options = NULL) see [[OssClient::getObject]] for more info
 * @method bool doesObjectExist(string $object, array $options = NULL) see [[OssClient::doesObjectExist]] for more info
 * @method null restoreObject(string $object, array $options = NULL) see [[OssClient::restoreObject]] for more info
 * @method int computePartSize(int $partSize) see [[OssClient::computePartSize]] for more info
 * @method array generateMultiuploadParts(int $file_size, int $partSize = 5242880) see [[OssClient::generateMultiuploadParts]] for more info
 * @method string initiateMultipartUpload(string $object, array $options = NULL) see [[OssClient::initiateMultipartUpload]] for more info
 * @method string uploadPart(string $object, string $uploadId, string $options = null) see [[OssClient::uploadPart]] for more info
 * @method ListPartsInfo listParts(string $object, string $uploadId, array $options = null) see [[OssClient::listParts]] for more info
 * @method null abortMultipartUpload(string $object, string $uploadId, array $options = NULL) see [[OssClient::abortMultipartUpload]] for more info
 * @method null completeMultipartUpload(string $object, string $uploadId, array $listParts, array $options = NULL) see [[OssClient::completeMultipartUpload]] for more info
 * @method ListMultipartUploadInfo listMultipartUploads(array $options = null) see [[OssClient::listMultipartUploads]] for more info
 * @method null uploadPartCopy(string $fromObject, string $toBucket, string $toObject, int $partNumber, string $uploadId, array $options = NULL) see [[OssClient::uploadPartCopy]] for more info
 * @method null multiuploadFile(string $object, string $file, array $options = null) see [[OssClient::multiuploadFile]] for more info
 * @method array uploadDir(string $prefix, string $localDirectory, string $exclude = '.|..|.svn|.git', bool $recursive = false, bool $checkMd5 = true) see [[OssClient::uploadDir]] for more info
 * @method string signUrl(string $object, int $timeout = 60, string $method = self::OSS_HTTP_GET, array $options = NULL) see [[OssClient::signUrl]] for more info
 *
 * @package thanatos\oss
 */
class Oss extends Component
{
    /** @var string AccessKeyID */
    public $accessKeyId;

    /** @var string AccessKeySecret */
    public $accessKeySecret;

    /** @var string Endpoint */
    public $endpoint;

    /** @var string 内网的地址 */
    public $endpointInternal;

    /** @var string OSS上传回调Url */
    public $callbackUrl;

    /** @var bool 是否启用HTTPS */
    public $useSLL = true;

    /** @var bool 是否启用上传回调 */
    public $enableCallback = true;

    /** @var array oss回调的自定义参数 */
    public $callbackParams = [];

    /** @var array oss回调的默认参数 */
    public $defaultCallbackParams = [
        'filename' => 'object',
        'etag',
        'size',
        'mimeType',
        'height' => 'imageInfo.height',
        'width' => 'imageInfo.width',
        'format' => 'imageInfo.format',
    ];

    /** @var string 回调时格式 */
    public $callbackBodyType = 'application/x-www-form-urlencoded';

    /** @var string bucket */
    private $_bucket;

    private $_client;

    public function init()
    {
        parent::init();
        if (empty($this->endpoint) || !is_string($this->endpoint)) {
            throw new Exception('endpont is empty');
        }
        if (empty($this->endpointInternal) || !is_string($this->endpointInternal)) {
            throw new Exception('endpont is empty');
        }
        if (empty($this->accessKeySecret) || !is_string($this->accessKeySecret)) {
            throw new Exception('accessKeySecret is empty');
        }

        if (empty($this->accessKeyId) || !is_string($this->accessKeyId)) {
            throw new Exception('accessKeyId is empty');
        }

        if (empty($this->bucket) || !is_string($this->bucket)) {
            throw new Exception('bucket is empty');
        }
    }

    /**
     * Get Bucket
     * @return string
     */
    public function getBucket()
    {
        return $this->_bucket;
    }

    /**
     * Set Bucket
     * @param $value
     */
    public function setBucket($value)
    {
        $this->_bucket = $value;
    }

    /**
     * Get Client
     * @return OssClient
     * @throws \OSS\Core\OssException
     */
    public function getClient()
    {
        if ($this->_client === null) {
            $this->_client = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpointInternal);
        }
        return $this->_client;
    }

    /**
     * Set Client
     * @param OssClient $client
     */
    public function setClient(OssClient $client)
    {
        if ($client instanceof OssClient) {
            $this->_client = $client;
        }
    }

    /**
     * 上传远程URL的文件
     * @param string $object objcet名称
     * @param string $content 上传的内容
     * @param array $options
     * @return PutObjectResult
     * @link https://github.com/yiisoft/yii2-httpclient
     */
    public function putObjectOrigin($object, $url, $options = null)
    {
        $validator = new UrlValidator();

        if ($validator->validate($url)) {
            $content = '';
            if (class_exists('yii\httpclient\Client')) {
                $client = new Client(['transport' => 'yii\httpclient\CurlTransport']);
                $response = $client->createRequest()
                    ->setMethod('GET')->setUrl($url)->send();
                if (!$response->isOk) {
                    return $this->throwError('file is not exist');
                }
                $content = $response->content;
            } else {
                $content = file_get_contents($url);
            }
            if (empty($content)) {
                $this->throwError('File not exist');
            }
            return $this->putObject($object, $content, $options);
        } else {
            return $this->copyObject($url, $object);
        }
    }


    /**
     * 获取OSS Object 的宽高信息
     * @param string $object OSS Object 路径
     * @return bool|array
     * @author thanatos <thanatos915@163.com>
     */
    public function getObjectSize($object)
    {
        try {
            $result = $this->getObject($object, [OssClient::OSS_PROCESS => 'image/info']);
            $result = Json::decode($result);
            $width = $result['ImageWidth']['value'];
            $height = $result['ImageHeight']['value'];
            if ($width && $height) {
                return ['width' => $width, 'height' => $height];
            }
        } catch (\Throwable $e) {
            return [];
        }

    }

    /**
     * 获取OSS JS 的签名
     * @param int $expire
     * @return string
     * @author thanatos <thanatos915@163.com>
     */
    public function getSignature($dir = 'uploads', $expire = 2592000)
    {
        $now = time();
        $end = $now + $expire;
        $expiration = $this->gmt_iso8601($end);
        $dir .= '/';

        if ($this->enableCallback) {
            $callback_param = array('callbackUrl' => $this->callbackUrl,
                'callbackBody' => $this->generateCallbackBody(),
                'callbackBodyType' => $this->callbackBodyType);
            $callback_string = json_encode($callback_param);

            $base64_callback_body = base64_encode($callback_string);
        }

        //最大文件大小.用户可以自己设置
        $condition = array(0 => 'content-length-range', 1 => 0, 2 => $expire);
        $conditions[] = $condition;

        //表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
        $start = array(0 => 'starts-with', 1 => '$key', 2 => $dir);
        $conditions[] = $start;


        $arr = array('expiration' => $expiration, 'conditions' => $conditions);

        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $this->accessKeySecret, true));

        $response = array();
        $response['accessid'] = $this->accessKeyId;
        $response['host'] = ($this->useSLL ? 'https' : 'http') . '://' . $this->bucket . '.' . $this->endpoint;
        $response['policy'] = $base64_policy;
        $response['signature'] = $signature;
        $response['expire'] = $end;
        if ($this->enableCallback) {
            $response['callback'] = $base64_callback_body;
        }
        //这个参数是设置用户上传指定的前缀
        $response['dir'] = $dir;
        return $response;
    }

    /**
     * 拷贝一个在OSS上已经存在的object成另外一个object
     *
     * @param string $fromObject 源object名称
     * @param string $toObject 目标object名称
     * @param array $options
     * @author thanatos <thanatos915@163.com>
     */
    public function copyObject($fromObject, $toObject, $options = null)
    {
        try {
            return $this->getClient()->copyObject($this->bucket, $fromObject, $this->bucket, $toObject, $options);
        } catch (\Throwable $e) {
            return $e;
        }
    }

    /**
     * 移动oss文件
     * @param string $fromObject 源object名称
     * @param string $toObject 目标object名称
     * @param array $options
     * @author thanatos <thanatos915@163.com>
     */
    public function moveObject($fromObject, $toObject, $options = null)
    {
        try {
            $result = $this->copyObject($fromObject, $toObject, $options);
            if ($result && $this->deleteObject($fromObject, $options)) {
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            return $false;
        }
    }

    /**
     * @param string $name
     * @param array $params
     * @return bool|mixed
     * @throws \OSS\Core\OssException
     * @internal
     */
    public function __call($name, $params)
    {
        if (method_exists($this->getClient(), $name)) {
            // DEV Throw Errors
            if (YII_ENV_DEV) {
                return $this->callClientFunc($name, $params);
            }
            try {
                return $this->callClientFunc($name, $params);
            } catch (\Exception $throwable) {
                return false;
            }

        }
        return parent::__call($name, $params);
    }

    /**
     * @param $name
     * @param $params
     * @return mixed
     * @throws \OSS\Core\OssException
     * @internal
     */
    private function callClientFunc($name, $params)
    {

        $result = $this->getClient()->$name($this->bucket, ...$params);
        // Put method Results
        if (
            (strpos($name, 'put') === 0 ||
                strpos($name, 'create') === 0 ||
                strpos($name, 'delete') === 0 ||
                $name == 'uploadFile' ||
                $name == 'getObjectMeta') &&
            $name != 'deleteObjects' &&
            $name != 'putBucketLiveChannel' &&
            is_array($result)
        ) {
            // filesize
            $result['download_content_length'] = $result['info']['download_content_length'];
            $result['size_upload'] = $result['info']['size_upload'];
            $result['size_download'] = $result['info']['size_download'];
            $result['etag'] && $result['etag'] = trim($result['etag'], '"');
            $result = new PutObjectResult($result);
        }
        return $result;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function throwError($string)
    {
        if (YII_ENV_DEV) {
            throw new Exception($string);
        } else {
            return false;
        }
    }

    private function gmt_iso8601($time)
    {
        $dtStr = date("c", $time);
        $mydatetime = new \DateTime($dtStr);
        $expiration = $mydatetime->format(\DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration . "Z";
    }

    /**
     * @return string
     * @author thanatos <thanatos915@163.com>
     * @internal
     */
    private function generateCallbackBody()
    {
        // 默认参数
        $str = '';
        foreach ($this->defaultCallbackParams as $key => $item) {
            if (is_string($key)) {
                $str .= '&' . $key . '=${' . $item . '}';
            } else {
                $str .= '&' . $item . '=${' . $item . '}';
            }
        }

        // 自定义参数
        foreach ($this->callbackParams as $key => $item) {
            $str .= '&' . $item . '=${x:' . $item . '}';
        }
        return trim($str, '&');
    }

}