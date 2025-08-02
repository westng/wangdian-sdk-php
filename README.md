# 旺店通旗舰版 PHP SDK

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.4-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-Apache%202.0-blue.svg)](LICENSE)

一个用于旺店通旗舰版 API 的 PHP SDK，提供简单易用的接口调用方法。

## ✨ 功能特性

- 🚀 **简单易用**：提供简洁的 API 接口，快速上手
- 🔐 **安全认证**：支持签名验证，确保 API 调用安全
- 🔄 **智能重试**：内置重试机制，自动处理网络异常
- ⚡ **Hyperf 友好**：完美适配 Hyperf 框架，支持依赖注入
- 📄 **分页支持**：内置分页类，轻松处理大量数据
- 🔧 **多租户**：支持多卖家模式，灵活配置

## 📦 安装

```bash
composer require westng/wangdian-sdk-php
```

## ⚙️ 配置

### 环境变量配置

复制 `env.example` 文件为 `.env`：

```bash
cp env.example .env
```

编辑 `.env` 文件：

```env
WDT_SID=your_sid_here
WDT_APPKEY=your_appkey_here
WDT_APPSECRET=your_secret:salt
WDT_BASE_URL=https://api.wangdian.cn
WDT_MULTI_TENANT_MODE=false
```

## 🚀 快速开始

### 基础使用

```php
<?php

use WangDianSDK\Client\WdtErpClient;
use WangDianSDK\Config\ConfigLoader;
use WangDianSDK\Model\Pager;

// 加载配置
$config = ConfigLoader::load();

// 创建客户端
$client = new WdtErpClient(
    $config['sid'],
    $config['appkey'],
    $config['appsecret'],
    $config['base_url'],
    $config['multi_tenant_mode']
);

// 调用API
$result = $client->call('goods.Goods.queryWithSpec', $params);

// 分页调用
$pager = new Pager(10, 0, true);
$result = $client->pageCall('setting.Shop.queryShop', $pager, $params);
```

### 在 Hyperf 中使用

```php
<?php

declare(strict_types=1);

namespace App\Service;

use WangDianSDK\Client\WdtErpClient;
use WangDianSDK\Config\ConfigLoader;

class WangDianService
{
    private WdtErpClient $client;

    public function __construct()
    {
        $config = ConfigLoader::load();

        $this->client = new WdtErpClient(
            $config['sid'],
            $config['appkey'],
            $config['appsecret'],
            $config['base_url'],
            $config['multi_tenant_mode']
        );
    }

    public function queryGoods(array $params = [])
    {
        return $this->client->call('goods.Goods.queryWithSpec', $params);
    }
}
```

## 🔄 重试机制

### 默认配置

```php
$retryConfig = [
    'enable_retry' => true,           // 是否启用重试机制
    'max_retry_attempts' => 3,        // 最大重试次数
    'retry_on_timeout' => true,       // 超时重试
    'retry_delay' => 1000,            // 重试延迟（毫秒）
    'exponential_backoff' => true,    // 指数退避
];
```

### 业务逻辑重试

SDK 采用基于业务逻辑的重试机制，当旺店通平台返回以下错误时会自动重试：

- **频率限制错误**：`status=100` 且 `message` 包含 "超过每分钟最大调用频率限制，请稍后重试"
- **并发限制错误**：`status=100` 且 `message` 包含 "超过每分钟最大并发次数限制，请稍后重试"

其他业务错误（如"未找到对应的单据信息"）不会触发重试，会直接抛出异常。

### 自定义重试配置

```php
// 禁用重试机制
$client->setRetryConfig(['enable_retry' => false]);

// 启用重试机制并自定义配置
$client->setRetryConfig([
    'enable_retry' => true,           // 启用重试机制
    'max_retry_attempts' => 5,        // 增加重试次数
    'retry_delay' => 2000,            // 增加重试延迟
    'exponential_backoff' => true,    // 启用指数退避
]);
```

### Hyperf 环境优化

SDK 会自动检测 Hyperf 环境并优化配置：

- 减少重试次数（3→2 次）
- 缩短重试延迟（1000ms→500ms）
- 启用指数退避

## 📋 API 示例

### 查询店铺

```php
$pager = new Pager(10, 0, true);
$result = $client->pageCall('setting.Shop.queryShop', $pager, []);
```

### 查询仓库

```php
$pager = new Pager(10, 0, true);
$result = $client->pageCall('setting.Warehouse.queryWarehouse', $pager, []);
```

### 查询商品

```php
$params = [
    'start_time' => '2024-01-01 00:00:00',
    'end_time' => '2024-01-31 23:59:59'
];
$result = $client->call('goods.Goods.queryWithSpec', $params);
```

## ⚠️ 错误处理

```php
try {
    $result = $client->call('goods.Goods.queryWithSpec', $params);

    if ($result) {
        // 处理成功结果
        return $result;
    }

} catch (WdtErpException $e) {
    // 处理 API 调用异常
    error_log("API 调用失败: " . $e->getMessage());
    throw $e;

} catch (GuzzleException $e) {
    // 处理 HTTP 请求异常
    error_log("HTTP 请求失败: " . $e->getMessage());
    throw $e;
}
```

## 🧪 测试

### 配置测试

```bash
php test/ConfigTest.php
```

### 重试机制测试

```bash
php test/RetryTest.php
```

### API 调用测试

```bash
# 店铺查询测试
php test/SettingShopQueryShop.php

# 仓库查询测试
php test/SettingWarehouseQueryWarehouse.php
```

## 📁 项目结构

```
wangdian-sdk-php/
├── src/                          # 源代码目录
│   ├── Client/                   # 客户端类
│   │   └── WdtErpClient.php     # 主要客户端类
│   ├── Config/                   # 配置管理
│   │   └── ConfigLoader.php     # 配置加载器
│   ├── Exception/                # 异常类
│   │   └── WdtErpException.php  # 自定义异常
│   ├── Model/                    # 模型类
│   │   └── Pager.php            # 分页类
│   └── Api/                      # API 示例
├── test/                         # 测试文件
├── examples/                     # 使用示例
├── composer.json                 # 依赖配置
├── env.example                   # 环境变量示例
└── README.md                     # 说明文档
```

## 📄 许可证

本项目基于 [Apache License 2.0](LICENSE) 开源协议。

## 🤝 贡献

欢迎提交 Issue 和 Pull Request 来改进这个项目。

## 📞 联系方式

- 作者：westng
- 邮箱：457395070@qq.com
- 项目地址：https://github.com/westng/wangdian-sdk-php
