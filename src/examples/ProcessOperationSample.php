<?php

/**
 * Copyright 2022 InspurCloud Technologies Co.,Ltd.
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use
 * this file except in compliance with the License.  You may obtain a copy of the
 * License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed
 * under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR
 * CONDITIONS OF ANY KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations under the License.
 *
 */

/**
 * This sample demonstrates how to download an cold object
 * from OBS using the OBS SDK for PHP.
 */
if (file_exists ( 'vendor/autoload.php' )) {
    require 'vendor/autoload.php';
} else {
    require '../vendor/autoload.php'; // sample env
}

if (file_exists ( 'obs-autoloader.php' )) {
    require 'obs-autoloader.php';
} else {
    require '../obs-autoloader.php'; // sample env
}

use Obs\ObsClient;
use Obs\ObsException;

$ak = '*** Provide your Access Key ***';

$sk = '*** Provide your Secret Key ***';

$endpoint = 'https://your-endpoint:443';

$bucketName = 'my-obs-cold-bucket-demo';

$objectKey = 'my-obs-cold-object-key-demo';


/*
 * Constructs a obs client instance with your account for accessing OBS
 */
$obsClient = ObsClient::factory ( [
    'key' => $ak,
    'secret' => $sk,
    'endpoint' => $endpoint,
    'socket_timeout' => 30,
    'connect_timeout' => 10
] );

try
{
    /**
     * value:百分比
     */
    printf("图片处理-旋转-测试用例");
    $obsClient -> rotateOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
            'instruction'=>[
                'value' => '30',
            ]
        ]
    ]);
    /**
     * value:horizontal（水平翻转） vertical（垂直翻转）
     */
    printf("图片处理-翻转-测试用例");
    $obsClient -> flipOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
            'instruction'=>[
                'value' => '30',
            ]
        ]
    ]);
    /*
     * 图片缩略方法
     * file:需要处理的文件全路径名称
     * type:custom 自定义； rate 等比例缩放
     * model:缩放模式  【type值为custom时必填】
     *      lfit：等比缩放，限制在指定长宽的矩形内的最大图片
     *      mfit：等比缩放，延伸出指定长宽的矩形外的最小图片
     *      fill：固定宽高，将mfit得到的图片进行居中裁剪
     *      pad：固定宽高，将lfit得到的图片置于指定宽高的矩形正中，然后将空白处进行填充
     *      fixed：	强制缩放到指定宽高
     * width:宽度 【type值为custom时必填】
     * height:高度 【type值为custom时必填】
     * limit: 限制大小 【type值为custom时必填】
     * value: 等比例缩放百分比值 0-100
     */
    printf("图片处理缩放-自定义-测试用例");
    $obsClient -> resizeOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
            'instruction'=>[
                'type' => 'custom',
                'model'=>'lfit',
                'width'=>'200',
                'height'=>'300',
                'limit'=> 0
            ]
        ]
    ]);
    printf("图片处理缩放-按缩放比例-测试用例");
    $obsClient -> resizeOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
            'instruction'=>[
                'type' => 'rate',
                'value'=> 50,
            ]
        ]
    ]);



    /*
     * 图片裁剪
     * type：可选参数 x,y
     * value:裁剪区域  取值范围[1,图片宽度/高度]
     * saveArea:默认为0，表示第一块。
     */
    printf("图片处理-索引裁剪-测试用例");
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

    /**
     * radus:裁剪半径
     */
    printf("图片处理-内切圆裁剪-测试用例");
    $obsClient -> circleOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
            'instruction'=>[
                'radius' => 20,
            ]
        ]
    ]);

    /**
     * radus:裁剪半径
     */
    printf("图片处理-圆角矩形裁剪-测试用例");
    $obsClient -> roundedCornersOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
            'instruction'=>[
                'radius' => 20,
            ]
        ]
    ]);


    printf("图片处理-获取图片信息 -测试用例");
    $obsClient -> getInfoOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
        ]
    ]);

    printf("图片处理-获取图片平均色调 -测试用例");
    $obsClient -> averageHueOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
        ]
    ]);

    /**
     * type:可选参数heif,avif
     */
    printf("图片处理-转换格式 -测试用例");
    $obsClient -> formatConversionOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
            'instruction'=>[
                'type' => 'avif',
            ]
        ]
    ]);

    /**
     * type:可选参数text,image
     * content:文本内容或图片base64格式数据
     * font:文字字体 思源宋体（5oCd5rqQ5a6L5L2T），思源黑体（5oCd5rqQ6buR5L2T），文泉微米黑（5paH5rOJ5b6u57Gz6buR）
     * size:字体大小
     * color:字体颜色
     * position:位置  可选值tl，top，tr，left，center，right，bl，bottom，br
     */
    printf("图片处理-普通水印 -测试用例");
    $obsClient -> watermarkOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
            'instruction'=>[
                'type' => 'text',
                'content'=>'5rWq5r2u5LqRaW5zcHVy',
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

    /**
     * type:可选参数text,image
     * content:文本内容或图片base64格式数据
     */
    printf("图片处理-盲水印 -测试用例");
        $obsClient->blindWatermarkOperation([
            'body' => [
                'file' => $filePath,
                'instruction' => [
                    'type' => 'text',
                    'content' => 'abcdef',
                ]
            ]
        ]);

    printf("图片管道处理-缩略，裁剪，旋转等-测试用例");
    $obsClient -> pannelMogrOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
            'instructions'=>[
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
                ]
            ]
        ]
    ]);

    //水印综合处理
    printf("图片处理-普通水印 -测试用例");
    $obsClient -> pannelWatermarkOperation([
        'body'=>[
            'file' => 'https://sfff.oss.cn-north-3.inspurcloudoss.com/012.jpg',
            'instructions'=>[
                'watermark'=>[
                    [
                        'type' => 'text',
                        'content'=>'5rWq5r2u5LqRaW5zcHVy',
                        'font'=>'5oCd5rqQ5a6L5L2T',
                        'color'=>'263d29',
                        'size'=>'40',
                        'position'=>'tl',
                        'x'=>'0',
                        'y'=>'0',
                        'transparency'=>100
                    ],
                    [
                        'type' => 'image',
                        'content'=>'5rWq5r2u5LqRaW5zcHVy',
                        'font'=>'5oCd5rqQ5a6L5L2T',
                        'color'=>'263d29',
                        'size'=>'40',
                        'position'=>'tl',
                        'x'=>'0',
                        'y'=>'0',
                        'transparency'=>100
                    ]

                ],
            ]
        ]
    ]);


} catch ( ObsException $e ) {
    echo 'Response Code:' . $e->getStatusCode () . PHP_EOL;
    echo 'Error Message:' . $e->getExceptionMessage () . PHP_EOL;
    echo 'Error Code:' . $e->getExceptionCode () . PHP_EOL;
    echo 'Request ID:' . $e->getRequestId () . PHP_EOL;
    echo 'Exception Type:' . $e->getExceptionType () . PHP_EOL;
} finally{
    $obsClient->close ();
}