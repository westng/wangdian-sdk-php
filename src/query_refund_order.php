<?php
include 'wdtsdk.php';

$sid = 'wdtapi3';
$appkey = 'wdt_test';
$appsecret = 'aa:dd';
$service_url = 'http://47.92.239.46/openapi';

$client = new WdtErpClient($service_url, $sid, $appkey, $appsecret); // 直接输入ip参数
// $client = new WdtErpClient($sid, $appkey, $appsecret);//无需传入ip参数

$pager = new Pager(50, 0);

$parMap = new stdClass();

$parMap->shop_nos = 'fx_test';
// $parMap->tid = '20181201006';
// $parMap->buyer_nick = '';
// $parMap->trade_no = '';
// $parMap->refund_no = '';
$parMap->modified_from = '2019-02-12 11:00';
$parMap->modified_to = '2019-04-12 11:26:31';
// $parMap->status = '';
// $parMap->stockin_status = '';
// $parMap->type = '';

// $pager = new Pager(50, 0, false);
$pager = new Pager(2, 0, true);

$data = $client->pageCall('aftersales.refund.Refund.search', $pager, $parMap);
// var_dump($data);
$php_json = json_encode($data);
echo $php_json;
?>

