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
require_once 'config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use WangDianSDK\Client\WdtErpClient;
use WangDianSDK\Exception\WdtErpException;
use WangDianSDK\Model\Pager;

/**
 * 店铺查询
 * SettingShopQueryShop.
 */
class SettingShopQueryShop
{
    private const SID = SID;

    private const APPKEY = APPKEY;

    private const APPSECRET = APPSECRET;

    private const SERVICE_URL = 'http://wdt.wangdian.cn';

    public static function run(): void
    {
        try {
            $client = new WdtErpClient(self::SERVICE_URL, self::SID, self::APPKEY, self::APPSECRET);
            echo "✓ 客户端创建成功\n";

            $pager = new Pager(10, 0, true);  // 分页大小10，页号0，计算总数

            $parMap = new stdClass();
            $parMap->start_time = '2025-01-01 00:00:00';
            $parMap->end_time = '2025-01-20 00:00:00';
            $parMap->hide_deleted = 1;

            $result = $client->pageCall('goods.Goods.queryWithSpec', $pager, $parMap);

            if ($result) {
                echo "✓ API调用成功\n";
                echo '返回数据条数: ' . ($result->total_count ?? 0) . "\n";
                var_dump($result);
            } else {
                echo "✗ API调用失败\n";
            }
        } catch (WdtErpException $e) {
            echo '旺店通API异常: ' . $e->getMessage() . PHP_EOL;
            echo '错误代码: ' . $e->getCode() . PHP_EOL;
            exit(1);
        } catch (Exception $e) {
            echo '系统异常: ' . $e->getMessage() . PHP_EOL;
            echo '错误文件: ' . $e->getFile() . PHP_EOL;
            echo '错误行号: ' . $e->getLine() . PHP_EOL;
            exit(1);
        }
    }
}

SettingShopQueryShop::run();
