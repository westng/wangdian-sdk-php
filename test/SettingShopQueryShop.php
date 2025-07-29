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
use WangDianSDK\Exception\WdtErpException;
use WangDianSDK\Model\Pager;

/**
 * 店铺查询测试
 * SettingShopQueryShop.
 */
class SettingShopQueryShop
{
    public static function run(): void
    {
        echo "🏪 店铺查询测试\n";
        echo "================\n\n";

        try {
            // 重置配置缓存
            ConfigLoader::reset();

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
                echo "您可以运行 'php test/ConfigTest.php' 来测试配置加载。\n";
                exit(1);
            }

            echo "✓ 配置验证通过\n";

            // 创建客户端
            $client = new WdtErpClient(
                $config['sid'],
                $config['appkey'],
                $config['appsecret'],
                $config['base_url'],
                $config['multi_tenant_mode']
            );
            echo "✓ 客户端创建成功\n";

            // 显示环境检测结果
            echo '✓ Hyperf 环境检测: ' . ($client->isHyperfEnvironment() ? '是' : '否') . "\n";

            // 显示重试配置
            $retryConfig = $client->getRetryConfig();
            echo '✓ 重试配置: 最大重试 ' . $retryConfig['max_retry_attempts'] . ' 次，延迟 ' . $retryConfig['retry_delay'] . "ms\n\n";

            // 创建分页对象
            $pager = new Pager(10, 0, true);  // 分页大小10，页号0，计算总数

            // 创建查询参数
            $parMap = new stdClass();
            // 可以添加查询条件，例如：
            // $parMap->shop_no = 'your_shop_no';
            // $parMap->shop_name = 'your_shop_name';

            echo "🔍 开始查询店铺信息...\n";

            // 调用API
            $result = $client->pageCall('setting.Shop.queryShop', $pager, $parMap);

            if ($result) {
                echo "✓ API调用成功\n";
                echo '📊 返回数据条数: ' . ($result->total_count ?? 0) . "\n";

                // 安全地获取数据条数
                $dataCount = 0;
                if (isset($result->data, $result->data->details)) {
                    if (is_array($result->data->details)) {
                        $dataCount = count($result->data->details);
                    } elseif (is_object($result->data->details) && method_exists($result->data->details, 'count')) {
                        $dataCount = $result->data->details->count();
                    } else {
                        $dataCount = 0;
                    }
                }
                echo '📄 当前页数据条数: ' . $dataCount . "\n\n";

                // 显示店铺信息
                if (isset($result->data, $result->data->details) && ! empty($result->data->details)) {
                    echo "🏪 店铺列表:\n";
                    if (is_array($result->data->details)) {
                        foreach ($result->data->details as $index => $shop) {
                            echo '  ' . ($index + 1) . '. 店铺编号: ' . ($shop->shop_no ?? 'N/A')
                                 . ', 店铺名称: ' . ($shop->shop_name ?? 'N/A')
                                 . ', 状态: ' . ($shop->status ?? 'N/A') . "\n";
                        }
                    } else {
                        echo "ℹ️ 数据格式不是数组，无法显示详细信息\n";
                    }
                } else {
                    echo "ℹ️ 暂无店铺数据\n";
                }

                // 显示分页信息
                if (isset($result->data, $result->data->total_count)) {
                    echo "\n📄 分页信息:\n";
                    echo '  - 总记录数: ' . $result->data->total_count . "\n";
                    echo '  - 当前页大小: ' . $pager->getPageSize() . "\n";
                    echo '  - 当前页码: ' . ($pager->getPageNo() + 1) . "\n";
                }
            } else {
                echo "✗ API调用失败，返回空结果\n";
            }
        } catch (WdtErpException $e) {
            echo '❌ 旺店通API异常: ' . $e->getMessage() . "\n";
            echo '错误代码: ' . $e->getCode() . "\n";
            exit(1);
        } catch (Exception $e) {
            echo '❌ 系统异常: ' . $e->getMessage() . "\n";
            echo '错误文件: ' . $e->getFile() . "\n";
            echo '错误行号: ' . $e->getLine() . "\n";
            exit(1);
        }

        echo "\n✅ 店铺查询测试完成\n";
    }
}

// 运行测试
if (php_sapi_name() === 'cli') {
    SettingShopQueryShop::run();
}
