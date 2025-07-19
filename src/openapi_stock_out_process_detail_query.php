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

$pars
= [
    'stockout_no' => 'CK2020100918',
    /*'warehouse_no' => 'lich0313',
    'process_no' => 'PS2018101002',
    'time_type' => '2',*/
    'start_time' => '2020-10-05 10:05:36',
    'end_time' => '2020-10-17 11:05:36',
];
$pager = new Pager(50, 0, true);
$data = $client->pageCall('wms.stockout.Process.queryWithDetail', $pager, $pars); // 分页方法

echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
