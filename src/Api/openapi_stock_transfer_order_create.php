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

$stockTransfer = new stdClass();
$stockTransfer->from_warehouse_no = 'spw_warehouse003';
$stockTransfer->to_warehouse_no = 'spw_warehouse002';
$stockTransfer->mode = 0;
$stockTransfer->outer_no = 'api_test002';
$stockTransfer->remark = '调拨单创建';

$transferList = [];
$transferDetail1 = new stdClass();
$transferDetail1->num = 1;
$transferDetail1->spec_no = 'daba1';
$transferDetail1->remark = '调拨单创建';

$transferDetail2 = new stdClass();
$transferDetail2->num = 1;
$transferDetail2->spec_no = 'daba2';
array_push($transferList, $transferDetail1);
array_push($transferList, $transferDetail2);

$data = $client->call('wms.stocktransfer.Edit.createOrder', $stockTransfer, $transferList, true);
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
