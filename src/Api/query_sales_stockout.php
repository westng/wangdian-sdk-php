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

$client = new WdtErpClient($sid, $appkey, $appsecret, $service_url);
// $client = new WdtErpClient($sid, $appkey, $appsecret);

$pager = new Pager(50, 0);

$pars
= [
    'stockout_no' => 'CK2020071015',
];
$pager = new Pager(50, 0, true);

$data = $client->pageCall('wms.stockout.Sales.queryWithDetail', $pager, $pars); // ��ҳ����
// echo "<pre>";print_r($data);echo "<pre>";
echo json_encode($data);
