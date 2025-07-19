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
$client = new WdtErpClient($service_url, $sid, $appkey, $appsecret);

$order = new stdClass();
$order->outer_no = 'stockother_out10';
$order->warehouse_no = '232312312';
$order->defect = 1;
$order->remark = 'API TEST';
$order->is_check = true;

$specList = [];
$spec = new stdClass();
$spec->spec_no = 'PC_2018';
$spec->remark = 'PD TEST';
$spec->num = '200.0000';
$spec->defect = false;
$spec->batch_no = '11111';
$spec->expire_date = '2020-07-01 00:00:00';
// $spec->position_no = 'xcx';

array_push($specList, $spec);

// 审核失败的message, 前缀为check_fail
$data = $client->call('wms.stockother.Out.push', $order, $specList);

echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
