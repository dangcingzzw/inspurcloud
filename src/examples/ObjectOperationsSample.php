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
 * This sample demonstrates how to do object-related operations
 * (such as create/delete/get/copy object, do object ACL/OPTIONS)
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
	$OSSClient -> putObject(['Bucket' => $bucketName, 'Key' => $objectKey, 'Body' => $content]);
	printf("Create object: %s successfully!\n\n", $objectKey);
	
	
	/*
	 * Get object metadata
	 */
	printf("Getting object metadata\n");
	$resp = $OSSClient->getObjectMetadata([
			'Bucket'=>$bucketName,
			'Key'=>$objectKey,
	]);
	printf("\tMetadata:%s\n\n", json_encode($resp));
	
	/*
	 * Get object
	 */
	printf("Getting object content\n");
	$resp = $OSSClient -> getObject(['Bucket' => $bucketName, 'Key' => $objectKey]);
	printf("\t%s\n\n", $resp['Body']);
	
	/*
	 * Copy object
	 */
	$sourceBucketName = $bucketName;
	$destBucketName = $bucketName;
	$sourceObjectKey = $objectKey;
	$destObjectKey = $objectKey . '-back';
	printf("Copying object\n\n");
	$OSSClient -> copyObject([
			'Bucket'=> $destBucketName,
			'Key'=> $destObjectKey,
			'CopySource'=>$sourceBucketName . '/' . $sourceObjectKey,
			'MetadataDirective' => OSSClient::CopyMetadata
	]);
	
	/*
	 * Options object
	 */
	doObjectOptions();
	
	/*
	 * Put/Get object acl operations
	 */
	doObjectAclOperations();
	
	/*
	 * Delete object
	 */
	printf("Deleting objects\n\n");
	$OSSClient -> deleteObject(['Bucket' => $bucketName, 'Key' => $objectKey]);
	$OSSClient -> deleteObject(['Bucket' => $bucketName, 'Key' => $destObjectKey]);
	
} catch ( OSSException $e ) {
	echo 'Response Code:' . $e->getStatusCode () . PHP_EOL;
	echo 'Error Message:' . $e->getExceptionMessage () . PHP_EOL;
	echo 'Error Code:' . $e->getExceptionCode () . PHP_EOL;
	echo 'Request ID:' . $e->getRequestId () . PHP_EOL;
	echo 'Exception Type:' . $e->getExceptionType () . PHP_EOL;
} finally{
	$OSSClient->close ();
}

function doObjectOptions()
{
	
	global $OSSClient;
	global $bucketName;
	global $objectKey;
	
	$OSSClient->setBucketCors ( [
			'Bucket' => $bucketName,
			'CorsRule' => [
					[
							'AllowedMethod' => ['HEAD', 'GET', 'PUT'],
							'AllowedOrigin' => ['http://www.a.com', 'http://www.b.com'],
							'AllowedHeader'=> ['Authorization'],
							'ExposeHeaders' => ['x-OSS-test1', 'x-OSS-test2'],
							'MaxAgeSeconds' => 100
					]
			]
	] );
	
	$resp = $OSSClient->optionsObject([
			'Bucket'=>$bucketName,
			'Key' => $objectKey,
			'Origin'=>'http://www.a.com',
			'AccessControlRequestMethods' => ['PUT'],
			'AccessControlRequestHeaders'=> ['Authorization']
	]);
	printf ("Options bucket: %s\n\n", json_encode($resp -> toArray()));

}

function doObjectAclOperations()
{
	global $OSSClient;
	global $bucketName;
	global $objectKey;
	
	printf("Setting object ACL to " . OSSClient::AclPublicRead . "\n\n");
	
	$OSSClient ->setObjectAcl([
			'Bucket' => $bucketName,
			'Key' => $objectKey,
			'ACL' => OSSClient::AclPublicRead
	]);
	
	printf("Getting object ACL\n");
	$resp = $OSSClient -> getObjectAcl([
			'Bucket' => $bucketName,
			'Key' => $objectKey
	]);
	printf("\tOwner:%s\n", json_encode($resp['Owner']));
	printf("\tGrants:%s\n\n", json_encode($resp['Grants']));
}