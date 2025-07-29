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
use WangDianSDK\Model\Pager;

/**
 * 模拟 Hyperf 服务类.
 */
class WangDianService
{
    private WdtErpClient $client;

    public function __construct()
    {
        // 加载配置
        $config = ConfigLoader::load();

        // 创建客户端 - SDK 会自动检测 Hyperf 环境并优化配置
        $this->client = new WdtErpClient(
            $config['sid'],
            $config['appkey'],
            $config['appsecret'],
            $config['base_url'],
            $config['multi_tenant_mode']
        );

        echo "✓ 客户端创建成功\n";
        echo '✓ 自动检测到 Hyperf 环境: ' . ($this->client->isHyperfEnvironment() ? '是' : '否') . "\n";
        echo '✓ 重试配置: ' . json_encode($this->client->getRetryConfig(), JSON_UNESCAPED_UNICODE) . "\n";
    }

    /**
     * 查询商品
     */
    public function queryGoods(array $params = [])
    {
        echo "🔍 查询商品...\n";

        $pager = new Pager(10, 0, true);
        return $this->client->pageCall('goods.Goods.queryWithSpec', $pager, $params);
    }

    /**
     * 查询订单.
     */
    public function queryOrders(array $params = [])
    {
        echo "📦 查询订单...\n";

        $pager = new Pager(10, 0, true);
        return $this->client->pageCall('sales.TradeQuery.queryWithDetail', $pager, $params);
    }

    /**
     * 查询仓库.
     */
    public function queryWarehouses()
    {
        echo "🏭 查询仓库...\n";

        $pager = new Pager(10, 0, true);
        return $this->client->pageCall('setting.Warehouse.queryWarehouse', $pager, []);
    }

    /**
     * 自定义重试配置示例.
     */
    public function setCustomRetryConfig()
    {
        echo "⚙️ 设置自定义重试配置...\n";

        $this->client->setRetryConfig([
            'max_retry_attempts' => 5,
            'retry_delay' => 2000,
            'retry_on_status' => [429, 500, 502, 503, 504, 408, 503],
            'exponential_backoff' => true,
        ]);

        echo "✓ 自定义重试配置已设置\n";
    }
}

/**
 * 模拟 Hyperf 控制器.
 */
class WangDianController
{
    private WangDianService $wangDianService;

    public function __construct()
    {
        $this->wangDianService = new WangDianService();
    }

    public function index()
    {
        echo "🚀 旺店通 SDK Hyperf 集成示例\n";
        echo "================================\n\n";

        try {
            // 查询仓库
            $warehouses = $this->wangDianService->queryWarehouses();
            echo '✓ 仓库查询成功，共 ' . ($warehouses->total_count ?? 0) . " 个仓库\n\n";

            // 查询商品
            $goods = $this->wangDianService->queryGoods([
                'start_time' => date('Y-m-d H:i:s', strtotime('-7 days')),
                'end_time' => date('Y-m-d H:i:s'),
            ]);
            echo '✓ 商品查询成功，共 ' . ($goods->total_count ?? 0) . " 个商品\n\n";

            // 查询订单
            $orders = $this->wangDianService->queryOrders([
                'start_time' => date('Y-m-d H:i:s', strtotime('-7 days')),
                'end_time' => date('Y-m-d H:i:s'),
            ]);
            echo '✓ 订单查询成功，共 ' . ($orders->total_count ?? 0) . " 个订单\n\n";

            // 演示自定义重试配置
            $this->wangDianService->setCustomRetryConfig();
        } catch (Exception $e) {
            echo '❌ 错误: ' . $e->getMessage() . "\n";
        }
    }
}

// 运行示例
if (php_sapi_name() === 'cli') {
    $controller = new WangDianController();
    $controller->index();
}
