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

namespace WangDianSDK\Client;

use WangDianSDK\Exception\WdtErpException;
use WangDianSDK\Model\Pager;

class WdtErpClient
{
    private string $url = '';

    private string $sid = '';

    private string $key = '';

    private string $secret = '';

    private string $salt = '';

    private string $version = '1.0';

    private bool $multi_tenant_mode = false;

    /**
     * 构造函数.
     *
     * @param string $url API基础URL，可选，默认为 http://wdt.wangdian.cn
     * @param string $sid 店铺ID
     * @param string $appkey 应用Key
     * @param string $appsecret 应用Secret (格式: secret:salt)
     * @param bool $multi_tenant_mode 多卖家模式，可选，默认为false
     */
    public function __construct(
        string $sid,
        string $appkey,
        string $appsecret,
        string $url = 'http://wdt.wangdian.cn',
        bool $multi_tenant_mode = false
    ) {
        $this->url = $this->buildUrl($url);
        $this->sid = $sid;
        $this->key = $appkey;
        $this->parseSecret($appsecret);
        $this->multi_tenant_mode = $multi_tenant_mode;
    }

    /**
     * 构建请求参数.
     */
    public function buildRequest(string $method, ?Pager $pager, array $args): array
    {
        $req = [
            'sid' => $this->sid,
            'key' => $this->key,
            'salt' => $this->salt,
            'method' => $method,
            'timestamp' => time() - 1325347200,
            'v' => $this->version,
        ];

        if ($pager !== null) {
            $req['page_size'] = $pager->getPageSize();
            $req['page_no'] = $pager->getPageNo();
            $req['calc_total'] = $pager->getCalcTotal() ? 1 : 0;
        }

        $body = json_encode($args, JSON_UNESCAPED_UNICODE);
        if ($body === false) {
            throw new \InvalidArgumentException('参数JSON编码失败');
        }

        $req['body'] = $body;
        $this->makeSign($req);
        unset($req['body']);

        $service_url = $this->url . '?' . http_build_query($req);
        return [$body, $service_url];
    }

    /**
     * 发送HTTP请求
     */
    public function sendRequest(string $body, string $service_url): string
    {
        $header_connection = $this->multi_tenant_mode ? 'Connection:close' : '';
        $opts = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n"
                    . 'X-Version-SDK: php-3.0'
                    . $header_connection,
                'content' => $body,
                'timeout' => 30, // 添加超时设置
            ],
        ];

        $context = stream_context_create($opts);
        $response = file_get_contents($service_url, false, $context);

        if ($response === false) {
            throw new WdtErpException('HTTP请求失败', 0);
        }

        return $response;
    }

    /**
     * 检查字符串是否为有效的JSON.
     */
    public function isJson(string $string): bool
    {
        if (empty($string)) {
            return false;
        }

        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * 调用API方法.
     */
    public function call(string $method, ...$args)
    {
        $json = $this->execute($method, null, $args);
        return $json->data ?? null;
    }

    /**
     * 分页调用API方法.
     */
    public function pageCall(string $method, Pager $pager, ...$args)
    {
        $json = $this->execute($method, $pager, $args);

        if (! $pager->getCalcTotal()) {
            return $json->data ?? null;
        }

        return $json;
    }

    /**
     * 调用BeanShell脚本接口.
     */
    public function callEx(string $method, ...$args)
    {
        $json = $this->execute('system.ScriptExtension.call', null, [$method, ...$args]);
        return $json->data ?? null;
    }

    /**
     * 构建请求URL.
     */
    private function buildUrl(string $url): string
    {
        if (substr($url, -1) === '/') {
            return $url . 'openapi';
        }
        return $url . '/openapi';
    }

    /**
     * 解析密钥.
     */
    private function parseSecret(string $secret): void
    {
        $arr = explode(':', $secret, 2);
        if (count($arr) !== 2) {
            throw new \InvalidArgumentException('密钥格式不正确，应为 secret:salt 格式');
        }
        $this->secret = $arr[0];
        $this->salt = $arr[1];
    }

    /**
     * 生成签名.
     */
    private function makeSign(array &$req): void
    {
        ksort($req);

        $arr = [$this->secret];
        foreach ($req as $key => $val) {
            if ($key === 'sign') {
                continue;
            }
            $arr[] = $key;
            $arr[] = $val;
        }
        $arr[] = $this->secret;

        $sign = md5(implode('', $arr));
        $req['sign'] = $sign;
    }

    /**
     * 执行API调用.
     */
    private function execute(string $method, ?Pager $pager, array $args): object
    {
        [$body, $service_url] = $this->buildRequest($method, $pager, $args);
        $response = $this->sendRequest($body, $service_url);

        if (! $this->isJson($response)) {
            throw new WdtErpException('服务器返回的不是有效的JSON数据', 0);
        }

        $json = json_decode($response);
        if ($json === null) {
            throw new WdtErpException('JSON解析失败', 0);
        }

        if (isset($json->status) && $json->status > 0) {
            throw new WdtErpException($json->message ?? '未知错误', $json->status);
        }

        return $json;
    }

    /**
     * 缓存获取.
     */
    private function cacheGet(string $key, int $secs)
    {
        $cache_dir = './tmp/';
        $path = $cache_dir . md5($key);

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

    /**
     * 缓存存储.
     * @param mixed $val
     */
    private function cachePut(string $key, $val): void
    {
        $cache_dir = './tmp/';

        if (! is_dir($cache_dir)) {
            @mkdir($cache_dir, 0777, true);
        }

        $path = $cache_dir . md5($key);
        $obj = [
            'time' => time(),
            'val' => $val,
        ];

        file_put_contents($path, serialize($obj));
    }
}
