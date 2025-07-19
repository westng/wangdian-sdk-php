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

$orderInfo
= [
    'outer_no' => 'CR201701010002',
    'warehouse_no' => 'lich0313',
    'provider_no' => 'lich0313',
    'post_fee' => 15.0000,
    'other_fee' => 10.0000,
    'remark' => '测试使用',
];

$detailList
= [
    [
        'spec_no' => 'lich0313q',
        'num' => 10.0000,
        'discount' => 0.4,
        'price' => 10.0000,
        'remark' => '11111',
    ],
    [
        'spec_no' => 'lich0313',
        'num' => 10.0000,
        'discount' => 0.4,
        'price' => 10.0000,
        'remark' => '22222',
    ],
];
$is_submit = true;

$data = $client->call('purchase.PurchaseReturn.createOrder', $orderInfo, $detailList, $is_submit); // 分页方法
$php_json = json_encode($data);
echo $php_json;
