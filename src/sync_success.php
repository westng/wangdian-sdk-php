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
$service_url = 'http://192.168.2.174:30000';

$client = new WdtErpClient($service_url, $sid, $appkey, $appsecret); // 直接输入ip参数

$info = new stdClass();
$info->syn_stock = 200;
$info->stock_change_count = 200;
$info->stock_syn_rule_id = -30000;
$info->stock_syn_rule_no = '';
$info->stock_syn_other = '';
$info->stock_syn_warehouses = '434';
$info->stock_syn_mask = 0;
$info->stock_syn_percent = 100;
$info->stock_syn_plus = 0;
$info->stock_syn_min = 0;
$info->stock_syn_max = 0;
$info->is_auto_listing = 1;
$info->is_auto_delisting = 1;
$info->is_syn_success = 1;
$info->is_manual = 1;
$info->syn_result = '同步成功';

$data = $client->call('sales.StockSync.syncSuccess', 2499, $info);
$jsonData = json_encode($data);
echo $jsonData;
