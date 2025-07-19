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
// $client = new WdtErpClient($sid, $appkey, $appsecret);//无需传入ip参数

$pager = new Pager(50, 0);

$parMap = new stdClass();
// $parMap->start_time = '2019-06-29 09:09:09';
// $parMap->end_time = '2019-07-29 09:09:09';
$parMap->spec_nos = ['daba4'];
$parMap->warehouse_no = 'pos';
$parMap->mask = 1 | 2;

// $pager = new Pager(50, 0, false);
$pager = new Pager(50, 0, true);

$data = $client->pageCall('wms.StockSpec.search', $pager, $parMap);
// var_dump($data);
echo "\n\n";
$php_json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
echo $php_json;
