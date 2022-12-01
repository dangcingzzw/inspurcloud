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
 * This sample demonstrates how to set/get self-defined metadata for object
 * on OSS using the OSS SDK for PHP.
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
	 * Create object
	 */
	$content = 'Hello OSS';
	
	/*
	 * Setting self-defined metadata
	 */
	
	$metadata = [];
	
	$metadata['meta1'] = 'value1';
	$metadata['meta2'] = 'value2';
	$OSSClient -> putObject(['Bucket' => $bucketName, 'Key' => $objectKey, 'Body' => $content, 'Metadata' => $metadata]);
	
	printf("Create object %s successfully!\n\n", $objectKey);
	
	/*
	 * Get object metadata
	 */
	$resp = $OSSClient -> getObjectMetadata(['Bucket' => $bucketName, 'Key' => $objectKey]);
	printf("Getting object metadata:\n");
	foreach ($resp['Metadata'] as $key => $value){
		printf("\t%s=%s\n", $key, $value);
	}
	
	/*
	 * Delete object
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