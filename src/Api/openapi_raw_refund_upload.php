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

$shopNo = 'spw_shop_001';

$orderList = [];

$orderList1 = new stdClass();
$orderList1->refund_no = 'spwrefund06';
$orderList1->num = 1;
$orderList1->tid = 'TK202010090000003';
$orderList1->oid = 'asdadsasdas';
$orderList1->type = 1;
$orderList1->status = 2;
$orderList1->refund_version = '1';
$orderList1->refund_amount = 1;
$orderList1->actual_refund_amount = 1;
$orderList1->title = 'test';
$orderList1->logistics_name = '';
$orderList1->logistics_no = '';
$orderList1->buyer_nick = '退换单推送测试1';
$orderList1->is_aftersale = 0;
$orderList1->refund_time = '2020-10-09 10:27:27';
$orderList1->reason = '';

array_push($orderList, $orderList1);
$response = $client->Call('aftersales.refund.RawRefund.upload', $shopNo, $orderList);
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
