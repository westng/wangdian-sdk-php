<?php
include 'wdtsdk.php';

$sid = 'wdtapi3';
$appkey = 'wdt_test';
$appsecret = 'aa:dd';
$service_url = 'http://47.92.239.46/openapi';

$client = new WdtErpClient($sid, $appkey, $appsecret, $service_url); // 直接输入ip参数
// $client = new WdtErpClient($sid, $appkey, $appsecret);//无需传入ip参数

$pager = new Pager(50, 0);

// $client = new WdtErpClient($sid, $appkey, $appsecret, $service_url);
// date.setTimezone();
$dateStr = substr(date('Y-m-d H:i:s'), 5);
echo date('Y-m-d H:i:s');
$goodListNum = 2;
$specListNum = 4;
$editValue = 'Q';

$goodsList = [];

// for ($i = 0; $i < $goodListNum; $i++) {
$i = 0;
$goods = new stdClass();
$goodsSuffix = $editValue . $dateStr . '_' . $i;
$goods->goods_no = 'testGoods' . $goodsSuffix;
$goods->goods_name = 'testGoodsName' . $goodsSuffix;
$goods->short_name = 'shortName';
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
$goods->flag_name = 'g_f';

$specList = [];
for ($y = 0; $y < $specListNum; ++$y) {
    $specSuffix = $goodsSuffix . '_' . $y;
    $spec = new stdClass();
    $spec->spec_no = 'spec_no' . $specSuffix;
    $spec->spec_name = 'spec_name' . $specSuffix;
    $spec->spec_code = 'spec_code';
    $spec->barcode = $spec->spec_no;

    $spec->pack_score = 12.0000;
    $spec->lowest_price = 200.0000;
    $spec->unit_name = '个';
    $spec->aux_unit_name = '10个';
    $spec->remark = '单品备注';
    $spec->is_single_batch = 1;
    $spec->goods_label = 6;
    array_push($specList, $spec);
}
// $goods->spec_list = $specList;
// array_push($goodsList, $goods);
// }

try {
    $data = $client->call('goods.Goods.push', $goods, $specList);
    var_dump($data);
    // $php_json = json_encode($data,true);
    // echo decodeUnicode($php_json);
} catch (WdtErpException $e) {
    echo 'message:' . $e->getMessage() . ' status:' . $e->getCode();
}
?>

