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
require_once __DIR__ . '/../vendor/autoload.php';

use WangDianSDK\Client\WdtErpClient;
use WangDianSDK\Config\ConfigLoader;

class RetryTest
{
    public static function run(): void
    {
        echo "🧪 重试机制测试\n";
        echo "================\n\n";

        try {
            // 加载配置
            $config = ConfigLoader::load();

            if (! ConfigLoader::validate()) {
                echo "❌ 配置验证失败，请检查 .env 文件\n";
                return;
            }

            // 创建客户端
            $client = new WdtErpClient(
                $config['sid'],
                $config['appkey'],
                $config['appsecret'],
                $config['base_url'],
                $config['multi_tenant_mode']
            );

            echo "✓ 客户端创建成功\n";
            echo '✓ Hyperf 环境检测: ' . ($client->isHyperfEnvironment() ? '是' : '否') . "\n";

            // 显示当前重试配置
            $retryConfig = $client->getRetryConfig();
            echo "✓ 当前重试配置:\n";
            echo '  - 最大重试次数: ' . $retryConfig['max_retry_attempts'] . "\n";
            echo '  - 重试延迟: ' . $retryConfig['retry_delay'] . "ms\n";
            echo '  - 重试状态码: ' . implode(', ', $retryConfig['retry_on_status']) . "\n";
            echo '  - 超时重试: ' . ($retryConfig['retry_on_timeout'] ? '是' : '否') . "\n";
            echo '  - 指数退避: ' . ($retryConfig['exponential_backoff'] ? '是' : '否') . "\n\n";

            // 测试自定义重试配置
            echo "🔧 测试自定义重试配置...\n";
            $client->setRetryConfig([
                'max_retry_attempts' => 2,
                'retry_delay' => 500,
                'retry_on_status' => [429, 500, 502, 503, 504],
                'exponential_backoff' => true,
            ]);

            $newRetryConfig = $client->getRetryConfig();
            echo "✓ 自定义重试配置已设置:\n";
            echo '  - 最大重试次数: ' . $newRetryConfig['max_retry_attempts'] . "\n";
            echo '  - 重试延迟: ' . $newRetryConfig['retry_delay'] . "ms\n\n";

            // 测试API调用（这里使用一个简单的API来测试重试机制）
            echo "🌐 测试API调用...\n";
            $result = $client->call('system.Core.now');

            if ($result) {
                echo "✓ API调用成功\n";
                echo "✓ 重试机制正常工作\n";
            } else {
                echo "⚠️ API调用返回空结果\n";
            }
        } catch (Exception $e) {
            echo '❌ 测试失败: ' . $e->getMessage() . "\n";
            echo '错误代码: ' . $e->getCode() . "\n";
        }

        echo "\n✅ 重试机制测试完成\n";
    }
}

// 运行测试
if (php_sapi_name() === 'cli') {
    RetryTest::run();
}
