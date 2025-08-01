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
$service_url = 'http://192.168.2.174:30000/openapi';

$client = new WdtErpClient($sid, $appkey, $appsecret, $service_url); // 直接输入ip参数

$pars
= [
    // 'stockin_no' => '',
    // 'warehouse_no' => 'lich0313',
    'process_no' => 'PS2020062202',
    // 'time_type' => '2',
    // 'start_time' => '2020-06-15 10:05:36',
    // 'end_time' => '2020-06-17 11:05:36'
];
$pager = new Pager(50, 0, true);
$data = $client->pageCall('wms.stockin.Process.queryWithDetail', $pager, $pars); // 分页方法
$php_json = json_encode($data);
echo $php_json;
