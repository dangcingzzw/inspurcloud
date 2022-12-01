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
 * This sample demonstrates how to upload multiparts to OSS
 * using the OSS SDK for PHP.
 */
if (file_exists ( 'vendor/autoload.php' )) {
	require 'vendor/autoload.php';
} else {
	require '../vendor/autoload.php'; // sample env
}

if (file_exists ( 'OSS-autoloader.php' )) {
	require 'OSS-autoloader.php';
} else {
	require '../OSS-autoloader.php'; // sample env
}

use OSS\OSSClient;
use OSS\OSSException;

$ak = '*** Provide your Access Key ***';

$sk = '*** Provide your Secret Key ***';

$endpoint = 'https://your-endpoint:443';

$bucketName = 'my-OSS-bucket-demo';

$objectKey = 'my-OSS-object-key-demo';


/*
 * Constructs a OSS client instance with your account for accessing OSS
 */
$OSSClient = OSSClient::factory ( [
		'key' => $ak,
		'secret' => $sk,
		'endpoint' => $endpoint,
		'socket_timeout' => 30,
		'connect_timeout' => 10
] );

try
{
	printf("Create a new bucket for demo\n\n");
	$OSSClient -> createBucket(['Bucket' => $bucketName]);
	
	/*
	 * Step 1: initiate multipart upload
	 */
	printf("Step 1: initiate multipart upload\n\n");
	
	$resp = $OSSClient -> initiateMultipartUpload(['Bucket'=>$bucketName,
			'Key'=>$objectKey]);
	
	$uploadId = $resp['UploadId'];
	/*
	 * Step 2: upload a part
	 */
	printf("Step 2: upload a part\n\n");
	$resp = $OSSClient->uploadPart([
			'Bucket'=>$bucketName,
			'Key' => $objectKey,
			'UploadId'=>$uploadId,
			'PartNumber'=>1,
			'Body' => 'Hello OSS',
            'random-object-name'=>true,
	]);
	
	$etag = $resp['ETag'];
	
	/*
	 * Step 3: complete multipart upload
	 */
	printf("Step 3: complete multipart upload\n\n");
	$OSSClient->completeMultipartUpload([
			'Bucket'=>$bucketName,
			'Key'=>$objectKey,
			'UploadId'=>$uploadId,
			'Parts'=>[
					['PartNumber'=>1,'ETag'=>$etag]
			],
	]);
	
	
} catch ( OSSException $e ) {
	echo 'Response Code:' . $e->getStatusCode () . PHP_EOL;
	echo 'Error Message:' . $e->getExceptionMessage () . PHP_EOL;
	echo 'Error Code:' . $e->getExceptionCode () . PHP_EOL;
	echo 'Request ID:' . $e->getRequestId () . PHP_EOL;
	echo 'Exception Type:' . $e->getExceptionType () . PHP_EOL;
} finally{
	$OSSClient->close ();
}