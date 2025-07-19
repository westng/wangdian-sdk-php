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

$transferInOrderList = [];
$transferInOrderDetail1 = new stdClass();
$transferInOrderDetail1->order_no = 'TID-DpkvagHHUO';
$transferInOrderDetail1->shop_no = 'spw_shop_001';
$transferInOrderDetail1->num = 1;
$transferInOrderDetail1->remark = 'api平台账单推送';

array_push($transferInOrderList, $transferInOrderDetail1);

$data = $client->call('finance.RawPayment.push', $transferInOrderList);
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
