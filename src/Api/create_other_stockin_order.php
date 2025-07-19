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
$service_url = 'http://192.168.2.196:30000/';

$client = new WdtErpClient($service_url, $sid, $appkey, $appsecret);
$order = new stdClass();
$order->outer_no = 'CG201912268052';
$order->warehouse_no = '1001';
$order->stockin_no = '251';
$order->is_check = true;

$order->reason = '1';

$orderDetail = new stdClass();
$orderDetail->spec_no = 'PC_2016';
$orderDetail->num = '15';
$orderDetail->position_no = 'B-A';
$order->goods_list = [$orderDetail];

$response = $client->call('wms.stockin.Other.createOtherOrder', $order);

$php_json = json_encode($response);
echo $php_json;
