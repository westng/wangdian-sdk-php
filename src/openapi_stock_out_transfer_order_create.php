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

$transferOutOrder = new stdClass();
$transferOutOrder->src_order_no = 'TF201811130002';
$transferOutOrder->warehouse_no = 'zmw01';
// $transferInOrder->logisctics_code='';
$transferOutOrder->remark = 'api测试';

$transferOutOrderList = [];
$transferOutOrderDetail1 = new stdClass();
$transferOutOrderDetail1->spec_no = 'daba5';
$transferOutOrderDetail1->num = 66;
$transferOutOrderDetail1->unit_name = '个';
$transferOutOrderDetail1->remark = 'api测试';

array_push($transferOutOrderList, $transferOutOrderDetail1);
$data = $client->call('wms.stockout.Transfer.createOrder', $transferOutOrder, $transferOutOrderList, false);
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
