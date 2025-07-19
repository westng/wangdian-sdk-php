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

$client = new WdtErpClient($sid, $appkey, $appsecret, $service_url); // 直接输入ip参数

$pager = new Pager(50, 0);

$paraMap = new stdClass();
// $paraMap->provider_no = 'ty';
// $paraMap->deleted=false;

$pager = new Pager(50, 0, true);

$data = $client->pageCall('setting.PurchaseProvider.queryDetail', $pager, $paraMap);
$php_json = json_encode($data, JSON_UNESCAPED_UNICODE);
echo $php_json;
