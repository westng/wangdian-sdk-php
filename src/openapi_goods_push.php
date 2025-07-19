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

$goodListNum = 2;
$specListNum = 3;
$editValue = 'spw';

$goodsList = [];

$i = 11;
$goods = new stdClass();
$goodsSuffix = $editValue . '_' . $i;
$goods->goods_no = 'testgoods' . $goodsSuffix;
$goods->goods_name = 'spwtestgoodsname' . $goodsSuffix;
$goods->short_name = 'spwtestgoodsshortname' . $goodsSuffix;
$goods->auto_create_bc = true;
$goods->class_name = '新建分类3';
$goods->brand_name = '新建品牌3';
$goods->unit_name = '个';
$goods->aux_unit_name = '件';
// $goods->cycle_name = "无";
$goods->goods_type = 1;

$goods->alias = 'alias';
$goods->pinyin = 'ceshi';
$goods->origin = '内蒙古呼和浩特市';
$goods->remark = '货品备注';
$goods->prop1 = 'P1';
$goods->prop2 = 'P2';
$goods->prop3 = 'P13';
$goods->prop4 = 'P14';
$goods->prop5 = 'P15';
$goods->prop6 = 'P16';

$specList = [];
for ($y = 0; $y < $specListNum; ++$y) {
    $specSuffix = $goodsSuffix . '_' . $y;
    $spec = new stdClass();
    $spec->spec_no = 'spw_api1' . $specSuffix;
    $spec->spec_name = 'spw_api1' . $specSuffix;
    $spec->spec_code = 'spw_api_code1' . $specSuffix;
    $spec->barcode = $spec->spec_no;

    $spec->goods_label = '航空禁运,陆路禁运,3';
    $spec->pack_score = 12.0000;
    $spec->lowest_price = 200.0000;
    $spec->unit_name = '个';
    $spec->aux_unit_name = '10个';
    $spec->remark = '单品备注';
    $spec->is_single_batch = 1;
    array_push($specList, $spec);
}
$response = $client->call('goods.Goods.push', $goods, $specList);

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
