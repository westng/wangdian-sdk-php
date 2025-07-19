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

$stockinOrder = new stdClass();
$stockinOrder->warehouse_no = 'spw_warehouse003';
$stockinOrder->remark = 'test';

$stockinOrderDetailList = [];
$stockinOrderDetail1 = new stdClass();
$stockinOrderDetail1->spec_no = 'TEST';
$stockinOrderDetail1->remark = 'test1';
$stockinOrderDetail1->num = 1;
$stockinOrderDetail1->defect = false;

$stockinOrderDetail2 = new stdClass();
$stockinOrderDetail2->spec_no = 'sjdsn';
$stockinOrderDetail2->remark = 'test2';
$stockinOrderDetail2->num = 1;
$stockinOrderDetail2->defect = false;
array_push($stockinOrderDetailList, $stockinOrderDetail1);
array_push($stockinOrderDetailList, $stockinOrderDetail2);
for ($i = 0; $i < 10000; ++$i) {
    $response = $client->call('wms.stockin.PreStockin.createExt', $stockinOrder, $stockinOrderDetailList);
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
// echo json_encode($response, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
