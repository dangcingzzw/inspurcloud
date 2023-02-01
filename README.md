# Inspur Cloud SDK for PHP

## 安装

* 推荐使用 `composer` 进行安装。可以使用 composer.json 声明依赖，或者运行下面的命令。
```bash
$ composer require inspurcloud/php-oss-sdk
```
* 直接下载安装，但需要参照 composer 的 autoloader，增加一个自己的 autoloader 程序。

## 运行环境

| Inspur SDK版本 | PHP 版本 |
|:--------------------:|:---------------------------:|
|          1.x         |  cURL extension,   5.3 - 5.6,7.0+ |

## 使用方法

### 上传

```php
use OSS\OSSClient;
...
$ak = '*** Provide your Access Key ***';
$sk = '*** Provide your Secret Key ***';
$endpoint = 'https://your-endpoint:443';
$bucketName = 'my-OSS-bucket-demo';

$objectKey = 'my-OSS-object-key-demo';
$OSSClient = OSSClient::factory ([
    'key' => $ak,
    'secret' => $sk,
    'endpoint' => $endpoint,
    'socket_timeout' => 30,
    'connect_timeout' => 10
]);

$metadata['meta1'] = 'value1';
$metadata['meta2'] = 'value2';
$OSSClient -> putObject(['Bucket' => $bucketName, 'Key' => $objectKey, 'Body' => $content, 'Metadata' => $metadata]);
...
### 图片旋转
参数说明： value顺时针旋转度数 ，度数为整数类型，取值为 0到359。
- 代码示例：
...
 $OSSClient -> rotateOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
            'instruction'=>[
                'value' => '30',
            ]
        ]
    ]);
...
### 图片翻转
参数说明： value顺时针旋转度数 ，度数为整数类型，取值为 0到359。
- 代码示例：
...
 $OSSClient -> flipOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
            'instruction'=>[
                'value' => '30',
            ]
        ]
    ]);
...
...
### 图片缩放
参数说明：     
file:需要处理的文件全路径名称
type: 缩放类型，可选参数custom 自定义； rate 等比例缩放
model:缩放模式  type值为custom时必填】
        lfit：等比缩放，限制在指定长宽的矩形内的最大图片
        mfit：等比缩放，延伸出指定长宽的矩形外的最小图片
        fill：固定宽高，将mfit得到的图片进行居中裁剪
        pad：固定宽高，将lfit得到的图片置于指定宽高的矩形正中，然后将空白处进行填充
        fixed：	强制缩放到指定宽高
width:宽度 【type值为custom时必填】
height:高度 【type值为custom时必填】
limit: 限制大小 【type值为custom时必填】
value: 等比例缩放百分比值 0-100

- 代码示例：
...
    $OSSClient -> resizeOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
            'instruction'=>[
                'type' => 'rate',
                'value'=>50,
            ]
        ]
    ]);
...
...
### 图片自定义裁剪
参数说明：     
file:需要处理的文件全路径名称
type：可选参数 x,y 
value:裁剪宽度或高度区域  取值范围[1,图片宽度/高度]
saveArea: 默认为0，指定选择剪切后返回的图片区域，默认为0，表示第一块。

- 代码示例：
...
    $OSSClient -> indexcropOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
            'instruction'=>[
                'type' => 'x',
                'value'=> 50,
                'saveArea'=>0
            ]
        ]
    ]);
...

### 图片内切圆裁剪
参数说明：     
file:需要处理的文件全路径名称
radus:裁剪半径

- 代码示例：
...
    $OSSClient -> circleOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
            'instruction'=>[
                'radius' => 20,
            ]
        ]
    ]);
...

### 图片矩形裁剪
参数说明：     
file:需要处理的文件全路径名称
radus:矩形四角圆角的半径

- 代码示例：
...
    $OSSClient -> roundedCornersOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
            'instruction'=>[
                'radius' => 20,
            ]
        ]
    ]);
...

### 获取图片基本信息
参数说明：     
file:需要处理的文件全路径名称

- 代码示例：
...
     $OSSClient -> getInfoOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
        ]
    ]);
...

### 获取图片平均色调信息
参数说明：     
file:需要处理的文件全路径名称

- 代码示例：
...
     $OSSClient -> averageHueOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
        ]
    ]);
...

### 图片转换格式
参数说明：     
file:需要处理的文件全路径名称
type:可选参数 jpg、jpeg、png、bmp、gif、tiff
- 代码示例：
...
    $OSSClient -> formatConversionOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
            'instruction'=>[
                'type' => 'png',
            ]
        ]
    ]);
...

### 图片水印
参数说明：     
file:需要处理的文件全路径名称
type:可选参数 text,image
content: 文字【文字水印不允许超过16个字符】，图片内容 【图片内容为路径格式：桶名/文件名】
font:文字字体 思源宋体（5oCd5rqQ5a6L5L2T），思源黑体（5oCd5rqQ6buR5L2T），文泉微米黑（5paH5rOJ5b6u57Gz6buR）
size:字体大小
color:字体颜色
position: 水印位置  可选值tl，top，tr，left，center，right，bl，bottom，br
- 代码示例：
...
   $OSSClient -> watermarkOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
            'instruction'=>[
                'type' => 'text',
                'content'=>'testWaterMark',
                'font'=>'5oCd5rqQ5a6L5L2T',
                'color'=>'263d29',
                'size'=>'40',
                'position'=>'top',
                'x'=>'0',
                'y'=>'0',
                'transparency'=>100
            ]
        ]
    ]);
...
### 图片盲水印
参数说明：     
file:需要处理的文件全路径名称
type:可选参数 text,image
content: 文字【文字水印不允许超过16个字符】，图片内容 【图片内容为路径格式：桶名/文件名】
- 代码示例：
...
         $OSSClient->blindWatermarkOperation([
            'body' => [
                'file' => $filePath,
                'instruction' => [
                    'type' => 'text',
                    'content' => 'abcdef',
                ]
            ]
        ]);
...


### 图片管道处理 集成 格式转换、旋转、翻转、缩放、裁剪等功能
参数说明：     
file:需要处理的文件全路径名称
instructions:指令名称集合 可选参数：,format-conversion,rotate，flip，resize，indexcrop，circle，rounded-corners
        format-conversion:         
             type:可选参数 jpg、jpeg、png、bmp、gif、tiff
        rotate:
             value:顺时针旋转度数 ，度数为整数类型，取值为 0到359
        flip:
             value:horizontal（水平翻转） vertical（垂直翻转）
        resize:
            type: 缩放类型，可选参数custom 自定义； rate 等比例缩放
            model:缩放模式  type值为custom时必填】
                    lfit：等比缩放，限制在指定长宽的矩形内的最大图片
                    mfit：等比缩放，延伸出指定长宽的矩形外的最小图片
                    fill：固定宽高，将mfit得到的图片进行居中裁剪
                    pad：固定宽高，将lfit得到的图片置于指定宽高的矩形正中，然后将空白处进行填充
                    fixed：	强制缩放到指定宽高
            width:宽度 【type值为custom时必填】
            height:高度 【type值为custom时必填】
            limit: 限制大小 【type值为custom时必填】
            value: 等比例缩放百分比值 0-100
            content: 文字【文字水印不允许超过16个字符】，图片内容 【图片内容为路径格式：桶名/文件名】
      indexcrop:
            type：可选参数 x,y 
            value:裁剪宽度或高度区域  取值范围[1,图片宽度/高度]
            saveArea: 默认为0，指定选择剪切后返回的图片区域，默认为0，表示第一块。
      circle:
            radus:裁剪半径
      rounded-corners:
            radus:radus:矩形四角圆角的半径
      
### 图片管道处理示例
- 代码示例：
...
         $OSSClient -> pannelMogrOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
            'instructions'=>[
                'format-conversion'=>[
                    'type'=>'png'
                ],
                'rotate'=>[
                    'value'=>30,
                ],
                'flip'=>[
                    'value'=>'vertical'
                ],
                'resize'=>[
                    'type' => 'custom',
                    'model'=>'lfit',
                    'width'=>'200',
                    'height'=>'300',
                    'limit'=> 0
                ],
                'indexcrop'=>[
                    'type' => 'x',
                    'value'=> 50,
                    'saveArea'=>0
                ],
                'circle'=>[
                    'radius' => 20,
                ],
                'roundedCorners'=>[
                    'radius' => 20,
                ],
            
            ]
        ]
    ]);
...

###文件分段上传示例
通过OSSClient->initiateMultipartUpload初始化一个分段上传任务
通过OSSClient->uploadPart上传段
通过OSSClient->completeMultipartUpload合并段
- 代码示例：
...
/*
	 * Create bucket
	 */
	printf("Create a new bucket for demo\n\n");
	$OSSClient -> createBucket(['Bucket' => $bucketName]);
	
	/*
	 * Claim a upload id firstly
	 */
	$resp = $OSSClient -> initiateMultipartUpload(['Bucket' => $bucketName, 'Key' => $objectKey]);
	
	$uploadId = $resp['UploadId'];
	printf("Claiming a new upload id %s\n\n", $uploadId);
	
	$sampleFilePath = '/temp/test.txt'; //sample large file path
	//  you can prepare a large file in you filesystem first
	createSampleFile($sampleFilePath);
	
	$partSize = 5 * 1024 * 1024;
	$fileLength = filesize($sampleFilePath);
	
	$partCount = $fileLength % $partSize === 0 ?  intval($fileLength / $partSize) : intval($fileLength / $partSize) + 1;
	
	if($partCount > 10000){
		throw new \RuntimeException('Total parts count should not exceed 10000');
	}
	
	printf("Total parts count %d\n\n", $partCount);
	$parts = [];
	$promise = null;
	/*
	 * Upload multiparts to your bucket
	 */
	printf("Begin to upload multiparts to OSS from a file\n\n");
	for($i = 0; $i < $partCount; $i++){
		$offset = $i * $partSize;
		$currPartSize = ($i + 1 === $partCount) ? $fileLength - $offset : $partSize;
		$partNumber = $i + 1;
		$p = $OSSClient -> uploadPartAsync([
				'Bucket' => $bucketName, 
				'Key' => $objectKey, 
				'UploadId' => $uploadId, 
				'PartNumber' => $partNumber,
				'SourceFile' => $sampleFilePath,
				'Offset' => $offset,
				'PartSize' => $currPartSize
		], function($exception, $resp) use(&$parts, $partNumber) {
			$parts[] = ['PartNumber' => $partNumber, 'ETag' => $resp['ETag']];
			printf ( "Part#" . strval ( $partNumber ) . " done\n\n" );
		});
		
		if($promise === null){
			$promise = $p;
		}
	}
	
	/*
	 * Waiting for all parts finished
	 */
	$promise -> wait();
	
	usort($parts, function($a, $b){
		if($a['PartNumber'] === $b['PartNumber']){
			return 0;
		}
		return $a['PartNumber'] > $b['PartNumber'] ? 1 : -1;
	});
	
	/*
	 * Verify whether all parts are finished
	 */
	if(count($parts) !== $partCount){
		throw new \RuntimeException('Upload multiparts fail due to some parts are not finished yet');
	}
	
	
	printf("Succeed to complete multiparts into an object named %s\n\n", $objectKey);
	
	/*
	 * View all parts uploaded recently
	 */
	printf("Listing all parts......\n");
	$resp = $OSSClient -> listParts(['Bucket' => $bucketName, 'Key' => $objectKey, 'UploadId' => $uploadId]);
	foreach ($resp['Parts'] as $part)
	{
		printf("\tPart#%d, ETag=%s\n", $part['PartNumber'], $part['ETag']);
	}
	printf("\n");
	
	
	/*
	 * Complete to upload multiparts
	 */
	$resp = $OSSClient->completeMultipartUpload([
			'Bucket' => $bucketName,
			'Key' => $objectKey,
			'UploadId' => $uploadId,
			'Parts'=> $parts
	]);
	
...

 

高级压缩
参数说明：
file:待处理的文件名 图片格式支持： JPEG、PNG
type:压缩类型 可选参数：avif,heif
- 代码示例：
...
 $OSSClient -> formatConversionOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
            'instruction'=>[
                'type' => 'avif',
            ]
        ]
    ]);
```

获取媒体文件元数据
参数说明：
file:待处理的文件名

- 代码示例：
```php
$OSSClient->GetAvInfoOperation([
        'body' => [
            'file' => 'xxx.mp4',
        ]
   ]);
```
[packagist]: http://packagist.org

[install-packagist]: https://packagist.org/packages/lcsdk/php-oss-sdk
