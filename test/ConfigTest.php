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

use WangDianSDK\Config\ConfigLoader;

class ConfigTest
{
    public static function run(): void
    {
        echo "🔧 配置加载测试\n";
        echo "================\n\n";

        try {
            // 重置配置缓存
            ConfigLoader::reset();

            // 加载配置
            $config = ConfigLoader::load();

            echo "✓ 配置加载成功\n\n";

            // 显示配置信息
            echo "📋 当前配置:\n";
            echo '  - SID: ' . ($config['sid'] ?: '未设置') . "\n";
            echo '  - APPKEY: ' . ($config['appkey'] ?: '未设置') . "\n";
            echo '  - APPSECRET: ' . ($config['appsecret'] ? '已设置' : '未设置') . "\n";
            echo '  - BASE_URL: ' . $config['base_url'] . "\n";
            echo '  - MULTI_TENANT_MODE: ' . ($config['multi_tenant_mode'] ? 'true' : 'false') . "\n\n";

            // 验证配置
            if (ConfigLoader::validate()) {
                echo "✅ 配置验证通过\n";
            } else {
                echo "❌ 配置验证失败\n";
                $errors = ConfigLoader::getValidationErrors();
                echo "错误信息:\n";
                foreach ($errors as $error) {
                    echo '  - ' . $error . "\n";
                }
                echo "\n";
            }

            // 测试环境变量获取
            echo "🔍 环境变量测试:\n";
            echo '  - WDT_SID: ' . (ConfigLoader::get('sid') ?: '未设置') . "\n";
            echo '  - WDT_APPKEY: ' . (ConfigLoader::get('appkey') ?: '未设置') . "\n";
            echo '  - WDT_APPSECRET: ' . (ConfigLoader::get('appsecret') ? '已设置' : '未设置') . "\n";
            echo '  - WDT_BASE_URL: ' . ConfigLoader::get('base_url', '未设置') . "\n";
            echo '  - WDT_MULTI_TENANT_MODE: ' . (ConfigLoader::get('multi_tenant_mode') ? 'true' : 'false') . "\n\n";

            // 测试动态配置设置
            echo "⚙️ 测试动态配置设置...\n";
            ConfigLoader::set('test_key', 'test_value');
            $testValue = ConfigLoader::get('test_key');
            echo "  - 设置 test_key = test_value\n";
            echo '  - 获取 test_key = ' . $testValue . "\n";

            if ($testValue === 'test_value') {
                echo "  ✅ 动态配置设置成功\n";
            } else {
                echo "  ❌ 动态配置设置失败\n";
            }
        } catch (Exception $e) {
            echo '❌ 配置测试失败: ' . $e->getMessage() . "\n";
            echo '错误代码: ' . $e->getCode() . "\n";
        }

        echo "\n✅ 配置测试完成\n";
    }
}

// 运行测试
if (php_sapi_name() === 'cli') {
    ConfigTest::run();
}
