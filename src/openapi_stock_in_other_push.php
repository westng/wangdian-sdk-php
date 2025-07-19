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

$order = new stdClass();
$order->outer_no = 'QR20200927000122';
$order->warehouse_no = 'spw_warehouse003';
$order->defect = 0;
$order->remark = '其他出库测试';
$order->is_check = true;

$specList = [];
$spec = new stdClass();
$spec->spec_no = 'orange_new';
$spec->remark = '其他出库测试';
$spec->num = '200.0000';
$spec->defect = false;
$spec->expire_date = '2020-10-12 00:00:00';
// $spec->position_no = 'xcx';

array_push($specList, $spec);

// 审核失败的message, 前缀为check_fail
$data = $client->call('wms.stockother.In.push', $order, $specList);

echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
