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

$sid = 'wdtapi3';
$appkey = 'wdt_test';
$appsecret = 'aa:dd';
$service_url = 'http://47.92.239.46/openapi';

$client = new WdtErpClient($service_url, $sid, $appkey, $appsecret); // 直接输入ip参数
$pager = new Pager(50, 0);

$pars
= [
    'start_time' => '2019-09-13 11:05:36',
    'end_time' => '2019-10-12 11:05:36',
];
$pager = new Pager(50, 0, true);

$data = $client->pageCall('sales.TradeQuery.queryWithDetail', $pager, $pars); // 分页方法
echo '<pre>';
print_r($data);
echo '<pre>';
