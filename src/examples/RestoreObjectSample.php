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
 * from OSS using the OSS SDK for PHP.
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

$bucketName = 'my-OSS-cold-bucket-demo';

$objectKey = 'my-OSS-cold-object-key-demo';


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
	 * Create a cold bucket
	 */
	printf("Create a new cold bucket for demo\n\n");
	$OSSClient -> createBucket(['Bucket' => $bucketName, 'StorageClass' => OSSClient::StorageClassCold]);
	
	/*
	 * Create a cold object
	 */
	printf("Create a new cold object for demo\n\n");
	$content = 'Hello OSS';
	$OSSClient -> putObject(['Bucket' => $bucketName, 'Key' => $objectKey, 'Body' => $content]);
	
	/*
	 * Restore the cold object
	 */
	printf("Restore the cold object\n\n");
	$OSSClient -> restoreObject([
			'Bucket' => $bucketName,
			'Key' => $objectKey,
			'Days' => 1,
	    'Tier' => OSSClient::RestoreTierExpedited
	]);
	
	/*
	 * Wait 6 minute to get the object
	 */
	sleep(60 * 6);
	
	/*
	 * Get the cold object
	 */
	printf("Get the cold object\n");
	$resp = $OSSClient -> getObject(['Bucket' => $bucketName, 'Key' => $objectKey]);
	printf("\t%s\n\n", $resp['Body']);
	
	/*
	 * Delete the cold object
	 */
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