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
require_once 'wdtsdk.php';

$sid = 'wdterp30';
$appkey = 'spw001';
$appsecret = 'aa:cc';
$service_url = 'http://172.17.105.65:30000/';

$client = new WdtErpClient($service_url, $sid, $appkey, $appsecret);

$provider = new stdClass();
$provider->provider_no = 'spwtest';
$provider->provider_name = 'spwtest';
$provider->contact = 'spw';
$provider->province = 110000;
$provider->city = 110100;
$provider->district = 110101;

$data = $client->call('setting.PurchaseProvider.push', $provider);

echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
