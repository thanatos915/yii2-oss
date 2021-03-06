<p align="center">
    </a>
    <h1 align="center">Aliyun Oss Sdk Extension for Yii 2</h1>
    <br>
</p>

 based on [aliyun/aliyun-oss-php-sdk](https://github.com/aliyun/aliyun-oss-php-sdk)

[![Latest Stable Version](https://poser.pugx.org/thanatosxia/yii2-oss/v/stable)](https://packagist.org/packages/thanatosxia/yii2-oss)
[![Total Downloads](https://poser.pugx.org/thanatosxia/yii2-oss/downloads)](https://packagist.org/packages/thanatosxia/yii2-oss)
[![Latest Unstable Version](https://poser.pugx.org/thanatosxia/yii2-oss/v/unstable)](https://packagist.org/packages/thanatosxia/yii2-oss)


安装
------------------
```bash
composer require thanatosxia/yii2-oss
```

使用
------------------
添加Oss组件
```php
'oss' => [
    'class' => 'thanatos\oss\Oss',
    'accessKeyId' => '', // Your accessKeyId
    'accessKeySecret' => '', // Your accessKeySecret
    'endpoint' => '', // endpoint address
    'bucket' => '', // Bucket Name
],
```
上传文件
```php
/**
 * $object 目标文件名
 * $content 二进制文件内容
 * $options 其它参数
 */
Yii::$app->oss->putObject($object, $content, $options);
```
删除文件
```php
/**
 * $object 目标文件名
 * $options 其它参数
 */
Yii::$app->oss->deleteObject($object, $options);
```
添加了可以上传远程文件, 可以直接使用，上传远程文件
```php
Yii::$app->oss->putObjectOrigin($object, $url, $options);
```
在WEB上传，php回调处理文件
```php
// 配置文件中增加一下选项
'oss' => [
    'accessKeyId' => 'xxxx',
    'accessKeySecret' => 'xxxx',
    'endpoint' => 'xxx',
    'bucket' => '',
    'callbackUrl' => '上传成功OSS请求的路径',
    // 自定义的上传回掉的参数
    'callbackParams' => [
        'user_id',
        'method',
        'folder_id'
    ]
],

// 获取OSS 签名 (dir 是允许前端上传的目录前缀)
return Yii::$app->oss->getSignature($dir);
```

说明
------
Oss 类重新分装了官方的类，官方类中一切方法均可使用，在中间加入了单例机制，和规范的返回值。
原使用方式
```php
$ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
$ossClient->putObject($bucket, $object, $content);
```
现在使用方式
```php
Yii::$app->oss->putObject($object, $content, $options);
```

如有需要操作多个Bucket的情况，重置Bucket后操作
```php
Yii::$app->oss->setBucket($bucket);
Yii::$app->oss->putObject($object, $content, $options);
```



Think you use this package!
