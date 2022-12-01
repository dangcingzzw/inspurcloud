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
 * This sample demonstrates how to download an object
 * from OSS in different ways using the OSS SDK for PHP.
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
	/*
	 * Create bucket
	 */
	printf("Create a new bucket for demo\n\n");
	$OSSClient -> createBucket(['Bucket' => $bucketName]);
	
	/*
	 * Upload an object to your bucket
	 */
	printf("Uploading a new object to OSS\n\n");
	$content = "abcdefghijklmnopqrstuvwxyz\n\t0123456789011234567890\n";
	$OSSClient -> putObject(['Bucket' => $bucketName, 'Key' => $objectKey, 'Body' => $content]);
	
	/*
	 * Download the object as an inputstream and display it directly
	 */
	printf("Downloading an object\n");
	$resp = $OSSClient -> getObject(['Bucket' => $bucketName, 'Key' => $objectKey]);
	printf("\t%s\n\n", $resp['Body']);
	
	
	/*
	 * Download the object to a file
	 */
	printf("Downloading an object to local file\n");
	$resp = $OSSClient -> getObject(['Bucket' => $bucketName, 'Key' => $objectKey, 'SaveAsFile' => '/temp/' .$objectKey]);
	printf("\tSaveAsFile:%s\n\n", $resp['SaveAsFile']);
	
	
	printf("Deleting object %s \n\n", $objectKey);
	$OSSClient -> deleteObject(['Bucket' => $bucketName, 'Key' => $objectKey]);
	
	
} catch ( OSSException $e ) {
	echo 'Response Code:' . $e->getStatusCode () . PHP_EOL;
	echo 'Error Message:' . $e->getExceptionMessage () . PHP_EOL;
	echo 'Error Code:' . $e->getExceptionCode () . PHP_EOL;
	echo 'Request ID:' . $e->getRequestId () . PHP_EOL;
	echo 'Exception Type:' . $e->getExceptionType () . PHP_EOL;
} finally{
	$OSSClient->close ();
}