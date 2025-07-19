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

$apiGoodsId = 4;
$infoMap = new stdClass();
$infoMap->syn_stock = 5;
$infoMap->stock_change_count = 5;
$infoMap->stock_syn_rule_id = 1;
$infoMap->stock_syn_rule_no = '001';
$infoMap->stock_syn_other = '';
$infoMap->stock_syn_warehouses = 450;
$infoMap->stock_syn_mask = 451;
$infoMap->stock_syn_percent = 5;
$infoMap->stock_syn_plus = 200;
$infoMap->stock_syn_min = 2;
$infoMap->stock_syn_max = 200;
$infoMap->is_auto_listing = 0;
$infoMap->is_auto_delisting = 0;
$infoMap->is_syn_success = 0;
$infoMap->is_manual = 0;
$infoMap->syn_result = 'api测试库存同步成功';

$pager = new Pager(1, 0, true);

$response = $client->Call('sales.StockSync.syncSuccess', $apiGoodsId, $infoMap);
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
