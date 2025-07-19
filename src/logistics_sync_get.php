<?php
header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set('Asia/Shanghai');
require_once 'wdtsdk.php';

$sid = 'wdterp30';
$appkey = 'spw001';
$appsecret = 'aa:cc';
$service_url = 'http://172.17.105.65:30000/';

$client = new WdtErpClient($service_url, $sid, $appkey, $appsecret); // 直接输入ip参数
// $client = new WdtErpClient($sid, $appkey, $appsecret);//无需传入ip参数

$parMap = new stdClass();
$parMap->shop_no = 'fx_test';
$parMap->is_own_platform = true;
// $parMap->tid = 'GO201812210003';
// $parMap->logistics_no = '234252441232';
// $parMap->is_part_sync = 1;

$syncList = [];
array_push($syncList, $parMap);

// $pager = new Pager(50, 0, false);
$pager = new Pager(1, 0, true);

$data = $client->pageCall('sales.LogisticsSync.getSyncListExt', $pager, $parMap);
// var_dump($data);
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>

