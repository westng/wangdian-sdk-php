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
require_once 'wdtsdk.php';

$sid = 'wdterp30';
$appkey = 'spw001';
$appsecret = 'aa:cc';
$service_url = 'http://172.17.105.65:30000/';

$client = new WdtErpClient($service_url, $sid, $appkey, $appsecret);

$purchase = new stdClass();
$purchase->provider_no = 'spw_gys_001';
$purchase->receive_warehouse_nos = 'test';
$purchase->expect_warehouse_no = 'test';
$purchase->purchaser_name = '史鹏威';
$purchase->contact = '史鹏威';
$purchase->telno = '17695759528';
$purchase->post_fee = '888888888888888';
$purchase->other_fee = '66666666666666';
$purchase->receive_address = '东土大唐';
$purchase->is_submit = 1;

$purchaseDetails = [];
$purchaseDetails1 = new stdClass();
$purchaseDetails1->spec_no = 'daba1';
$purchaseDetails1->num = 3;
array_push($purchaseDetails, $purchaseDetails1);
$purchase->purchase_details = $purchaseDetails;

$data = $client->call('purchase.PurchaseOrder.createOrder', $purchase);
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
