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
header('Content-Type: text/html;   charset=UTF-8');
date_default_timezone_set('Asia/Shanghai');
require_once 'wdtsdk.php';

$sid = 'wdterp30';
$appkey = 'zyOther';
$appsecret = 'aa:bb';
$service_url = 'http://192.168.1.135:30000/openapi';

$client = new WdtErpClient($service_url, $sid, $appkey, $appsecret);

$stockTransfer = new stdClass();
$stockTransfer->from_warehouse_no = 'lz';
$stockTransfer->to_warehouse_no = 'jziyy';
$stockTransfer->mode = 3;
$stockTransfer->outer_no = 'test1111';

$transferList = [];
$transferDetail1 = new stdClass();
$transferDetail1->num = 1;
$transferDetail1->spec_no = 'lz11';
$transferDetail1->remark = 'test1';

$transferDetail2 = new stdClass();
$transferDetail2->num = 1;
$transferDetail2->spec_no = 'lz12';
$transferDetail2->remark = 'test2';
array_push($transferList, $transferDetail1);
array_push($transferList, $transferDetail2);

$response = $client->call('wms.stocktransfer.Edit.createOrder', $stockTransfer, $transferList, true);

$php_json = json_encode($response);
echo $php_json;
