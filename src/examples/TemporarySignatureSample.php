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
 * This sample demonstrates how to do common operations in temporary signature way
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
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

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
]);

/**
 * simaple
 * $content 需要加密的数组
 * $secret  应用秘钥
 */

$OSSClient->generateSignList($content,$secret);


/**
 * other
 */
$httpClient = new Client(['verify' => false]);

/*
 * Create bucket
 */
$method = 'PUT';
$res = $OSSClient -> createSignedUrl(['Bucket' => $bucketName, 'Method' => $method]);
doAction('Create bucket', $method, $res['SignedUrl']);

/*
 * Set/Get/Delete bucket cors
 */
$method = 'PUT';
$content = '<CORSConfiguration><CORSRule><AllowedMethod>PUT</AllowedMethod><AllowedOrigin>http://www.a.com</AllowedOrigin><AllowedHeader>header1</AllowedHeader><MaxAgeSeconds>100</MaxAgeSeconds><ExposeHeader>header2</ExposeHeader></CORSRule></CORSConfiguration>';
$headers = ['Content-Length'=> strval(strlen($content)), 'Content-MD5' => base64_encode(md5($content, true))];
$res = $OSSClient -> createSignedUrl(['Bucket' => $bucketName, 'Method' => $method, 'SpecialParam' => 'cors', 'Headers' => $headers]);
doAction('Set bucket cors ', $method, $res['SignedUrl'], $content, $res['ActualSignedRequestHeaders']);


$method = 'GET';
$res= $OSSClient -> createSignedUrl(['Bucket' => $bucketName, 'Method' => $method, 'SpecialParam' => 'cors']);
doAction('Get bucket cors ', $method, $res['SignedUrl']);

$method = 'DELETE';
$res= $OSSClient -> createSignedUrl(['Bucket' => $bucketName, 'Method' => $method, 'SpecialParam' => 'cors']);
doAction('Delete bucket cors ', $method, $res['SignedUrl']);

/*
 * Create object
 */
$method = 'PUT';
$content = 'Hello OSS';
$headers = ['Content-Length'=> strval(strlen($content))];
$res = $OSSClient -> createSignedUrl(['Method' => $method, 'Bucket' => $bucketName, 'Key' => $objectKey, 'Headers'=> $headers]);
doAction('Create object', $method, $res['SignedUrl'], $content, $res['ActualSignedRequestHeaders']);
		

/*
 * Get object
 */
$method = 'GET';
$res = $OSSClient -> createSignedUrl(['Method' => $method, 'Bucket' => $bucketName, 'Key' => $objectKey]);
doAction('Get object', $method, $res['SignedUrl']);

/*
 * Set/Get object acl 
 */
$method = 'PUT';
$headers = ['x-amz-acl'=> OSSClient::AclPublicRead];
$res = $OSSClient -> createSignedUrl(['Method' => $method, 'Bucket' => $bucketName, 'Key' => $objectKey, 'Headers'=> $headers, 'SpecialParam' => 'acl']);
doAction('Set object Acl', $method, $res['SignedUrl'], null, $res['ActualSignedRequestHeaders']);


$method = 'GET';
$res = $OSSClient -> createSignedUrl(['Method' => $method, 'Bucket' => $bucketName, 'Key' => $objectKey, 'SpecialParam' => 'acl']);
doAction('Get object Acl', $method, $res['SignedUrl']);

/*
 * Delete object
 */
$method = 'DELETE';
$res = $OSSClient -> createSignedUrl(['Method' => $method, 'Bucket' => $bucketName, 'Key' => $objectKey]);
doAction('Delete object', $method, $res['SignedUrl']);

/*
 * Delete bucket
 */
$method = 'DELETE';
$res = $OSSClient -> createSignedUrl(['Bucket' => $bucketName, 'Method' => $method]);
doAction('Delete bucket', $method, $res['SignedUrl']);


function doAction($msg, $method, $url, $content=null, $headers=null){
	global $httpClient;
	
	try{
		$response = $httpClient -> request($method, $url, ['body' => $content, 'headers'=> $headers]);
		printf("%s using temporary signature url:\n", $msg);
		printf("\t%s successfully.\n", $url);
		printf("\tStatus:%d\n", $response -> getStatusCode());
		printf("\tContent:%s\n", $response -> getBody() -> getContents());
		$response -> getBody()-> close();
	}catch (ClientException $ex){
		printf("%s using temporary signature url:\n", $msg);
		printf("\t%s failed!\n", $url);
		printf('Exception message:%s', $ex ->getMessage());
	}

	printf("\n");
}

 

