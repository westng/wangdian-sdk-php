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
use WangDianSDK\Client\WdtErpClient;
use WangDianSDK\Config\ConfigLoader;
use WangDianSDK\Exception\WdtErpException;
use WangDianSDK\Model\Pager;

/**
 * 店铺查询
 * SettingShopQueryShop.
 */
class SettingShopQueryShop
{
    public static function run(): void
    {
        try {
            // 加载配置
            $config = ConfigLoader::load();

            // 验证配置
            if (! ConfigLoader::validate()) {
                $errors = ConfigLoader::getValidationErrors();
                echo "❌ 配置验证失败:\n";
                foreach ($errors as $error) {
                    echo "  - {$error}\n";
                }
                echo "\n请检查您的 .env 文件或环境变量配置。\n";
                exit(1);
            }

            echo "✓ 配置验证通过\n";

            $client = new WdtErpClient(
                $config['sid'],
                $config['appkey'],
                $config['appsecret'],
                $config['base_url'],
                $config['multi_tenant_mode']
            );
            echo "✓ 客户端创建成功\n";

            $pager = new Pager(10, 0, true);  // 分页大小10，页号0，计算总数

            $parMap = new stdClass();

            $result = $client->pageCall('setting.Shop.queryShop', $pager, $parMap);

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
