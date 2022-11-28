# Inspur Cloud SDK for PHP

## 安装

* 推荐使用 `composer` 进行安装。可以使用 composer.json 声明依赖，或者运行下面的命令。SDK 包已经放到这里 [`Inspur/php-sdk`][install-packagist] 。
```bash
$ composer require inspur/php-oss-sdk
```
* 直接下载安装，SDK 没有依赖其他第三方库，但需要参照 composer 的 autoloader，增加一个自己的 autoloader 程序。

## 运行环境

| Inspur SDK版本 | PHP 版本 |
|:--------------------:|:---------------------------:|
|          7.x         |  cURL extension,   5.3 - 5.6,7.0 |
|          6.x         |  cURL extension,   5.2 - 5.6 |

## 使用方法

### 上传

```php
use Obs\ObsClient;
...
$ak = '*** Provide your Access Key ***';
$sk = '*** Provide your Secret Key ***';
$endpoint = 'https://your-endpoint:443';
$bucketName = 'my-obs-bucket-demo';

$objectKey = 'my-obs-object-key-demo';
$obsClient = ObsClient::factory ([
    'key' => $ak,
    'secret' => $sk,
    'endpoint' => $endpoint,
    'socket_timeout' => 30,
    'connect_timeout' => 10
]);

$metadata['meta1'] = 'value1';
$metadata['meta2'] = 'value2';
$obsClient -> putObject(['Bucket' => $bucketName, 'Key' => $objectKey, 'Body' => $content, 'Metadata' => $metadata]);
...
```

[packagist]: http://packagist.org

[install-packagist]: https://packagist.org/packages/Inspur/php-oss-sdk
