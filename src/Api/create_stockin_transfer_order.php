 <?php
header('Content-Type: text/html;   charset=UTF-8');
date_default_timezone_set('Asia/Shanghai');
require_once __DIR__ . '/../wdtsdk.php';

$sid = 'wdterp30';
$appkey = 'zyOther';
$appsecret = 'aa:cc';
$service_url = 'http://192.168.1.135:30000/openapi';

$client = new WdtErpClient($sid, $appkey, $appsecret, $service_url);

$transferInOrder = new stdClass();
$transferInOrder->src_order_no = 'TF202003020004';
$transferInOrder->warehouse_no = 'jziyy';
// $transferInOrder->logisctics_code='';
$transferInOrder->remark = 'test';

$transferInOrderList = [];
$transferInOrderDetail1 = new stdClass();
$transferInOrderDetail1->spec_no = 'lz41';
$transferInOrderDetail1->num = 1;
$transferInOrderDetail1->unit_name = 'lz1';
$transferInOrderDetail1->remark = 'test';
$transferInOrderDetail1->position_no = 'b01';

array_push($transferInOrderList, $transferInOrderDetail1);

$response = $client->call('wms.stockin.Transfer.createOrder', $transferInOrder, $transferInOrderList, true);

$php_json = json_encode($response);
echo $php_json;

?>