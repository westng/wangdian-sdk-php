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
header('Content-Type: text/html;   charset=UTF-8');
date_default_timezone_set('Asia/Shanghai');
require_once __DIR__ . '/../wdtsdk.php';

$sid = 'wdterp30';
$appkey = 'zyOther';
$appsecret = 'aa:bb';
$service_url = 'http://192.168.1.135:30000/openapi';

$client = new WdtErpClient($sid, $appkey, $appsecret, $service_url);
$transferOutOrder = new stdClass();
$transferOutOrder->src_order_no = 'TF202003020004';
$transferOutOrder->warehouse_no = 'lz';
// $transferInOrder->logisctics_code='';
$transferOutOrder->remark = 'test';

$transferOutOrderList = [];
$transferOutOrderDetail1 = new stdClass();
$transferOutOrderDetail1->spec_no = 'lz41';
$transferOutOrderDetail1->num = 10;
$transferOutOrderDetail1->unit_name = 'lz1';
$transferOutOrderDetail1->remark = 'test';

array_push($transferOutOrderList, $transferOutOrderDetail1);
$data = $client->call('wms.stockout.Transfer.createOrder', $transferOutOrder, $transferOutOrderList, false);
$response = $client->call('wms.stockout.Other.createOther', $order);

$php_json = json_encode($response);
echo $php_json;
