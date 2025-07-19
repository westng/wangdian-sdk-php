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
require_once __DIR__ . '/../wdtsdk.php';

$sid = 'wdterp30';
$appkey = 'spw001';
$appsecret = 'aa:cc';
$service_url = 'http://172.17.105.65:30000/';

$client = new WdtErpClient($service_url, $sid, $appkey, $appsecret);

$parMap = new stdClass();
$parMap->modified_from = '2019-09-01 00:00:00';
$parMap->modified_to = '2020-10-20 00:00:00';
$parMap->refund_no = 'TK2009300003';

$pager = new Pager(1, 0, true);

$response = $client->pageCall('aftersales.refund.Refund.search', $pager, $parMap);

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
