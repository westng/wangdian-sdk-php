<?php

declare(strict_types=1);
/**
 * This file is part of WangDian PHP SDK.
 *
 * @link     https://github.com/westng/wangdian-sdk-php
 * @document https://github.com/westng/wangdian-sdk-php
 * @contact  westng
 * @license  https://github.com/westng/wangdian-sdk-php/blob/main/LICENSE
 */
header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set('Asia/Shanghai');
require_once __DIR__ . '/../wdtsdk.php';

$sid = 'wdterp30';
$appkey = 'spw001';
$appsecret = 'aa:cc';
$service_url = 'http://172.17.105.65:30000/';

$client = new WdtErpClient($sid, $appkey, $appsecret, $service_url);

$purchase = new stdClass();
$purchase->provider_no = 'spw_gys_001';
$purchase->warehouse_no = 'spw_warehouse003';
$purchase->contact = '史鹏威api_test';
$purchase->telno = '17695759528';
$purchase->post_fee = '888888888888888';
$purchase->other_fee = '66666666666666';
$purchase->receive_address = '';

$purchaseDetails = [];
$purchaseDetails1 = new stdClass();
$purchaseDetails1->spec_no = 'spw_spec01';
$purchaseDetails1->num = 3;
array_push($purchaseDetails, $purchaseDetails1);

$data = $client->call('purchase.PurchaseReturn.createOrder', $purchase, $purchaseDetails, true);
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
