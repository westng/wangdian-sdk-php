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

$parMap = new stdClass();
$parMap->start_time = '2020-09-01 00:00:00';
$parMap->end_time = '2020-09-29 00:00:00';
$parMap->shop_no = 'jd';

$pager = new Pager(1, 0, true);

$response = $client->pageCall('finance.RawPayment.search', $pager, $parMap);

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
