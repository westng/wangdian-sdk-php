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
$appkey = 'zyOther';
$appsecret = 'aa:cc';
$service_url = 'http://192.168.10.194:30000/openapi';

$client = new WdtErpClient($service_url, $sid, $appkey, $appsecret);

$params = new stdClass();
$params->start_time = '2020-06-2 00:00:00';
$params->end_time = '2020-06-14 00:00:00';
$params->src_order_type = '20';
$pager = new Pager(1, 0, true);
$data = $client->pageCall('wms.stockin.Other.queryWithDetail', $pager, $params);

$php_json = json_encode($data);
echo $php_json;
// echo "<pre>";print_r($data);echo "<pre>";
