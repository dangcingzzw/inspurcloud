# Inspur Cloud SDK for PHP

## 安装

* 推荐使用 `composer` 进行安装。可以使用 composer.json 声明依赖，或者运行下面的命令。SDK 包已经放到这里 [`qiniu/php-sdk`][install-packagist] 。
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
### 图片旋转
参数说明： value顺时针旋转度数 ，度数为整数类型，取值为 0到359。
- 代码示例：
...
 $obsClient -> rotateOperation([
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
 $obsClient -> flipOperation([
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
    $obsClient -> resizeOperation([
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
    $obsClient -> indexcropOperation([
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
    $obsClient -> circleOperation([
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
    $obsClient -> roundedCornersOperation([
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
     $obsClient -> getInfoOperation([
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
     $obsClient -> averageHueOperation([
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
    $obsClient -> formatConversionOperation([
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
   $obsClient -> watermarkOperation([
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
         $obsClient->blindWatermarkOperation([
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
      

- 代码示例：
...
         $obsClient -> pannelMogrOperation([
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





 

高级压缩
参数说明：
file:待处理的文件名 图片格式支持： JPEG、PNG
type:压缩类型 可选参数：avif,heif
- 代码示例：
...
 $obsClient -> formatConversionOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
            'instruction'=>[
                'type' => 'avif',
            ]
        ]
    ]);
```

[packagist]: http://packagist.org

[install-packagist]: https://packagist.org/packages/lcsdk/php-oss-sdk
