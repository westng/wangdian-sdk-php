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
class WdtErpException extends Exception
{
    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }
}

class Pager
{
    private $pageSize = 0;

    private $pageNo = 0;

    private $calcTotal = false;

    public function __construct($pageSize, $pageNo = 0, $calcTotal = false)
    {
        $this->pageSize = $pageSize;
        $this->pageNo = $pageNo;
        $this->calcTotal = $calcTotal;
    }

    public function getPageSize()
    {
        return $this->pageSize;
    }

    public function getPageNo()
    {
        return $this->pageNo;
    }

    public function getCalcTotal()
    {
        return $this->calcTotal;
    }
}

class WdtErpClient
{
    private $url = '';

    private $sid = '';

    private $key = '';

    private $secret = '';

    private $salt = '';

    private $version = '1.0';

    private $multi_tenant_mode = false;

    public function __construct()
    {
        $count = func_num_args();
        $args = func_get_args();
        if (method_exists($this, $f = '__construct' . ($count - 2))) {
            call_user_func_array([$this, $f], $args);
        } else {
            throw new Exception('参数不合法');
        }
    }

    public function __construct1($sid, $key, $secret)
    {
        $this->sid = $sid;
        $this->key = $key;

        $arr = explode(':', $secret, 2);
        $this->secret = $arr[0];
        $this->salt = $arr[1];

        $this->url = 'http://wdt.wangdian.cn/openapi';
    }

    public function __construct2($url, $sid, $key, $secret)
    {
        if (substr($url, -1) == '/') {
            $url = $url . 'openapi';
        } else {
            $url = $url . '/openapi';
        }
        $this->url = $url;
        $this->sid = $sid;
        $this->key = $key;

        $arr = explode(':', $secret, 2);
        $this->secret = $arr[0];
        $this->salt = $arr[1];
    }

    /**
     * @param $multi_tenant_mode bool:多卖家模式, 在同一环境(同一台机器/同一容器)下请求旗舰版多个卖家时需要开启
     */
    public function __construct3($url, $sid, $key, $secret, $multi_tenant_mode)
    {
        if (substr($url, -1) == '/') {
            $url = $url . 'openapi';
        } else {
            $url = $url . '/openapi';
        }
        $this->url = $url;
        $this->sid = $sid;
        $this->key = $key;

        $arr = explode(':', $secret, 2);
        $this->secret = $arr[0];
        $this->salt = $arr[1];
        $this->multi_tenant_mode = $multi_tenant_mode;
    }

    /**
     * build request.
     * @return array
     */
    public function buildRequest($method, $pager, $args)
    {
        $req = [];
        $req['sid'] = $this->sid;
        $req['key'] = $this->key;
        $req['salt'] = $this->salt;
        $req['method'] = $method;
        $req['timestamp'] = time() - 1325347200;
        $req['v'] = $this->version;

        if ($pager != null) {
            $req['page_size'] = $pager->getPageSize();
            $req['page_no'] = $pager->getPageNo();
            $req['calc_total'] = $pager->getCalcTotal() ? 1 : 0;
        }

        $body = json_encode($args);
        $req['body'] = $body;

        $this->makeSign($req, $this->secret);

        unset($req['body']);

        $service_url = $this->url . '?' . http_build_query($req);
        return [$body, $service_url];
    }

    /**
     * send http request.
     * @return false|string
     */
    public function sendRequest($body, $service_url)
    {
        $header_connection = $this->multi_tenant_mode ? 'Connection:close' : '';
        $opts = ['http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n"
                . 'X-Version-SDK: php-3.0'
                . $header_connection,
            'content' => $body,
        ],
        ];

        $context = stream_context_create($opts);

        return file_get_contents($service_url, false, $context);
    }

    /**
     * check if a string is json.
     * @return bool
     */
    public function isJson($string)
    {
        json_decode($string);
        return json_last_error() == JSON_ERROR_NONE;
    }

    public function call($method)
    {
        $args = func_get_args();
        array_shift($args);
        $json = $this->execute($method, null, $args);

        return @$json->data;
    }

    public function pageCall($method, $pager)
    {
        $args = func_get_args();
        array_shift($args);
        array_shift($args);

        $json = $this->execute($method, $pager, $args);

        if (! $pager->getCalcTotal()) {
            return $json->data;
        }

        return $json;
    }

    /*
        调用BeanShell脚本接口
    */
    public function callEx($method)
    {
        $args = func_get_args();
        $json = $this->execute('system.ScriptExtension.call', null, $args);

        return @$json->data;
    }

    private function makeSign(&$req)
    {
        ksort($req);

        $arr = [];
        $arr[] = $this->secret;
        foreach ($req as $key => $val) {
            if ($key == 'sign') {
                continue;
            }

            $arr[] = $key;
            $arr[] = $val;
        }
        $arr[] = $this->secret;

        $sign = md5(implode('', $arr));
        $req['sign'] = $sign;
    }

    private function execute($method, $pager, $args)
    {
        [$body, $service_url] = $this->buildRequest($method, $pager, $args);
        $response = $this->sendRequest($body, $service_url);
        $json = '';
        if ($this->isjson($response)) {
            $json = json_decode($response);
        } else {
            $json = $this->sendRequest($body, $service_url);
        }

        if (isset($json->status) && $json->status > 0) {
            throw new WdtErpException($json->message, $json->status);
        }

        return $json;
    }

    private function cacheGet($key, $secs)
    {
        $g_cache_dir = './tmp/';

        $path = $g_cache_dir . md5($key);

        $str = @file_get_contents($path);
        if (empty($str)) {
            return null;
        }

        $obj = unserialize($str);
        if (! $obj) {
            return null;
        }

        $now = time();
        if ($now - $obj['time'] > $secs) {
            @unlink($path);
            return null;
        }

        return $obj['val'];
    }

    private function cachePut($key, $val)
    {
        $g_cache_dir = './tmp/';

        if (! is_dir($g_cache_dir)) {
            @mkdir($g_cache_dir, 0777, true);
        }

        $path = $g_cache_dir . md5($key);

        $obj = [
            'time' => time(),
            'val' => $val,
        ];

        file_put_contents($path, serialize($obj));
    }
}
