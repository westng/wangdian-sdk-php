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
include 'wdtsdk.php';

$sid = 'wdterp30';
$appkey = 'lichAPI';
$appsecret = 'aa:cc';
$service_url = 'http://192.168.2.174:30000';

$client = new WdtErpClient($sid, $appkey, $appsecret, $service_url); // 直接输入ip参数

$data = $client->call('sales.StockSync.getSelfWaitSyncIdListOpen', 5000, 0);
$jsonData = json_encode($data);
echo $jsonData;
