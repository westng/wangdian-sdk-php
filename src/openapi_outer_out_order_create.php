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

$outerOutOrder = new stdClass();
$outerOutOrder->order_no = 'OO20201012005';
$outerOutOrder->warehouse_no = 'spw_warehouse';
$outerOutOrder->src_order_type = 2;
// $transferInOrder->logisctics_code='';
$outerOutOrder->remark = 'api调整出库创建';

$outerOutOrderList = [];
$outerOutOrderDetail1 = new stdClass();
$outerOutOrderDetail1->spec_no = 'orange_new';
$outerOutOrderDetail1->num = 1;
$outerOutOrderDetail1->remark = 'api调整出库创建';

array_push($outerOutOrderList, $outerOutOrderDetail1);

$data = $client->call('wms.outer.OuterOut.createOrder', $outerOutOrder, $outerOutOrderList, true);
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
