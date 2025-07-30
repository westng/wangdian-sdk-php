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

class RetryTest
{
    public function testRetryLogic()
    {
        // 创建客户端实例
        $client = new WdtErpClient(
            'test_sid',
            'test_key',
            'test_secret:test_salt'
        );

        // 测试重试配置
        echo "=== 重试配置测试 ===\n";
        $retryConfig = $client->getRetryConfig();
        echo '启用重试: ' . ($retryConfig['enable_retry'] ? '是' : '否') . "\n";
        echo '最大重试次数: ' . $retryConfig['max_retry_attempts'] . "\n";
        echo '重试延迟: ' . $retryConfig['retry_delay'] . "ms\n";
        echo '指数退避: ' . ($retryConfig['exponential_backoff'] ? '是' : '否') . "\n";

        // 测试shouldRetry方法（通过反射访问私有方法）
        echo "\n=== 重试判断测试 ===\n";
        $reflection = new ReflectionClass($client);
        $shouldRetryMethod = $reflection->getMethod('shouldRetry');
        $shouldRetryMethod->setAccessible(true);

        // 测试需要重试的情况
        $testCases = [
            [
                'name' => '频率限制错误',
                'json' => (object) ['status' => 100, 'message' => '超过每分钟最大调用频率限制，请稍后重试'],
                'expected' => true,
            ],
            [
                'name' => '并发限制错误',
                'json' => (object) ['status' => 100, 'message' => '超过每分钟最大并发次数限制，请稍后重试'],
                'expected' => true,
            ],
            [
                'name' => '不需要重试的错误（单据不存在）',
                'json' => (object) ['status' => 100, 'message' => '未找到对应的单据信息!'],
                'expected' => false,
            ],
            [
                'name' => '其他错误码',
                'json' => (object) ['status' => 200, 'message' => '其他错误'],
                'expected' => false,
            ],
            [
                'name' => '成功响应',
                'json' => (object) ['status' => 0, 'message' => 'success'],
                'expected' => false,
            ],
        ];

        foreach ($testCases as $testCase) {
            $result = $shouldRetryMethod->invoke($client, $testCase['json']);
            $status = $result === $testCase['expected'] ? '✓' : '✗';
            echo "{$status} {$testCase['name']}: " . ($result ? '需要重试' : '不需要重试') . "\n";
        }

        // 测试延迟计算
        echo "\n=== 延迟计算测试 ===\n";
        $calculateRetryDelayMethod = $reflection->getMethod('calculateRetryDelay');
        $calculateRetryDelayMethod->setAccessible(true);

        for ($i = 1; $i <= 3; ++$i) {
            $delay = $calculateRetryDelayMethod->invoke($client, $i);
            echo "第{$i}次重试延迟: {$delay}ms\n";
        }

        // 测试禁用重试
        echo "\n=== 禁用重试测试 ===\n";
        $client->setRetryConfig(['enable_retry' => false]);
        $newRetryConfig = $client->getRetryConfig();
        echo '启用重试: ' . ($newRetryConfig['enable_retry'] ? '是' : '否') . "\n";

        // 重新启用重试
        $client->setRetryConfig(['enable_retry' => true]);
        $finalRetryConfig = $client->getRetryConfig();
        echo '重新启用重试: ' . ($finalRetryConfig['enable_retry'] ? '是' : '否') . "\n";

        echo "\n=== 测试完成 ===\n";
    }
}

// 运行测试
$test = new RetryTest();
$test->testRetryLogic();
