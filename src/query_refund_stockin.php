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

$paraMap = new stdClass();
// $paraMap->warehouse_id="18";
// $paraMap->is_query_for_main_provider = false;
$paraMap->start_time = '2019-09-01';
$paraMap->end_time = '2019-10-01';

$pager = new Pager(2, 0, true);

$data = $client->pageCall('wms.stockin.Refund.queryWithDetail', $pager, $paraMap);
// var_dump($data);
$php_json = json_encode($data);
echo $php_json;
