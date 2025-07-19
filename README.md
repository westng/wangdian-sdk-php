# 旺店通旗舰版 PHP SDK

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.4-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Composer](https://img.shields.io/badge/Composer-Required-orange.svg)](https://getcomposer.org/)

一个用于旺店通旗舰版 API 的 PHP SDK，提供简单易用的接口调用方法。

## 📋 目录

- [功能特性](#功能特性)
- [系统要求](#系统要求)
- [安装](#安装)
- [快速开始](#快速开始)
- [配置说明](#配置说明)
- [API 示例](#api-示例)
- [错误处理](#错误处理)
- [项目结构](#项目结构)
- [贡献指南](#贡献指南)
- [许可证](#许可证)

## ✨ 功能特性

- 🚀 **简单易用**：提供简洁的 API 接口，快速上手
- 🔐 **安全认证**：支持签名验证，确保 API 调用安全
- 📦 **自动加载**：基于 Composer PSR-4 标准，自动加载类文件
- 🛡️ **异常处理**：完善的错误处理和异常机制
- 📄 **分页支持**：内置分页类，轻松处理大量数据
- 🔧 **多租户**：支持多卖家模式，灵活配置
- 📊 **完整覆盖**：支持旺店通旗舰版所有主要 API 接口

## 🖥️ 系统要求

- PHP >= 7.4
- cURL 扩展
- Composer

## 📦 安装

### 通过 Composer 安装

```bash
composer require westng/wangdian-sdk-php
```

### 手动安装

1. 克隆或下载项目到本地

```bash
git clone https://github.com/your-username/wangdian-sdk-php.git
cd wangdian-sdk-php
```

2. 安装依赖

```bash
composer install
```

## 🚀 快速开始

### 方式一：使用环境变量（推荐）

#### 1. 安装 SDK

```bash
composer require westng/wangdian-sdk-php
```

#### 2. 配置环境变量

复制环境变量示例文件并填入您的配置：

```bash
# 复制环境变量示例文件
cp env.example .env

# 编辑 .env 文件，填入您的实际配置信息
```

环境变量配置示例：

```bash
# 旺店通API配置
WDT_SID=your_sid_here           # 店铺ID
WDT_APPKEY=your_appkey_here     # 应用Key
WDT_APPSECRET=your_secret:salt  # 应用Secret (格式: secret:salt)
WDT_BASE_URL=https://api.wangdian.cn  # API基础URL
WDT_MULTI_TENANT_MODE=false     # 多卖家模式（可选）
```

#### 3. 基本使用示例

```php
<?php
require_once 'vendor/autoload.php';

use WangDianSDK\Client\WdtErpClient;
use WangDianSDK\Model\Pager;
use WangDianSDK\Exception\WdtErpException;
use WangDianSDK\Config\ConfigLoader;

// 加载配置
$config = ConfigLoader::load();

// 创建客户端实例
$client = new WdtErpClient(
    $config['sid'],
    $config['appkey'],
    $config['appsecret'],
    $config['base_url'],
    $config['multi_tenant_mode']
);

try {
    // 查询商品信息
    $params = [
        'goods_no' => 'test123',
        'page_no' => 1,
        'page_size' => 10
    ];

    $result = $client->goodsQuery($params);
    print_r($result);

} catch (WdtErpException $e) {
    echo "API调用失败: " . $e->getMessage();
}
```

### 方式二：直接使用构造函数参数

#### 1. 下载 SDK

```bash
git clone https://github.com/your-username/wangdian-sdk-php.git
cd wangdian-sdk-php
composer install
```

#### 2. 基本使用示例

```php
<?php
require_once 'vendor/autoload.php';

use WangDianSDK\Client\WdtErpClient;
use WangDianSDK\Model\Pager;
use WangDianSDK\Exception\WdtErpException;

// 直接传入配置参数
$client = new WdtErpClient(
    'your_sid_here',
    'your_appkey_here',
    'your_secret:salt',
    'https://api.wangdian.cn',
    false  // 多卖家模式
);

// ... 其余代码同方式一
```

## ⚙️ 配置说明

### 📦 自动加载说明

#### Composer 安装方式（推荐）

当通过 `composer require westng/wangdian-sdk-php` 安装时：

- ✅ **无需手动引入** `vendor/autoload.php`
- ✅ Composer 会自动处理自动加载
- ✅ 直接使用 `use` 语句即可

#### 手动安装方式

当直接下载 SDK 文件时：

- ❌ **需要手动引入** `vendor/autoload.php`
- ❌ 需要确保 Composer 依赖已安装

### 必需配置项

| 配置项      | 说明         | 获取方式                                    |
| ----------- | ------------ | ------------------------------------------- |
| `SID`       | 店铺 ID      | 旺店通后台 → 系统设置 → 店铺信息            |
| `APPKEY`    | 应用 Key     | 旺店通后台 → 系统设置 → 开放平台 → 应用管理 |
| `APPSECRET` | 应用 Secret  | 旺店通后台 → 系统设置 → 开放平台 → 应用管理 |
| `BASE_URL`  | API 基础 URL | 生产环境：`https://api.wangdian.cn`         |

### 多租户模式

支持多卖家配置：

```php
// 多租户配置示例
$configs = [
    'seller1' => [
        'sid' => 'sid1',
        'appkey' => 'appkey1',
        'appsecret' => 'appsecret1'
    ],
    'seller2' => [
        'sid' => 'sid2',
        'appkey' => 'appkey2',
        'appsecret' => 'appsecret2'
    ]
];

$client = new WdtErpClient(
    $configs['seller1']['sid'],
    $configs['seller1']['appkey'],
    $configs['seller1']['appsecret']
);
```

## 📚 API 示例

### 商品管理

```php
// 查询商品
$params = [
    'goods_no' => 'test123',
    'page_no' => 1,
    'page_size' => 20
];
$result = $client->goodsQuery($params);

// 推送商品
$goodsData = [
    'goods_no' => 'test123',
    'goods_name' => '测试商品',
    'goods_type' => 1,
    'spec_list' => [
        [
            'spec_no' => 'test123-001',
            'barcode' => '1234567890123'
        ]
    ]
];
$result = $client->goodsPush($goodsData);
```

### 库存管理

```php
// 查询库存
$params = [
    'spec_no' => 'test123-001',
    'warehouse_no' => 'WH001'
];
$result = $client->stockQuery($params);

// 库存计算
$params = [
    'spec_no' => 'test123-001',
    'warehouse_no' => 'WH001'
];
$result = $client->calcStock($params);
```

### 订单管理

```php
// 查询订单
$params = [
    'start_time' => '2024-01-01 00:00:00',
    'end_time' => '2024-01-31 23:59:59',
    'page_no' => 1,
    'page_size' => 50
];
$result = $client->tradeQuery($params);

// 创建采购订单
$orderData = [
    'provider_no' => 'PROVIDER001',
    'warehouse_no' => 'WH001',
    'remark' => '测试采购订单',
    'details' => [
        [
            'spec_no' => 'test123-001',
            'goods_count' => 100,
            'goods_price' => 10.50
        ]
    ]
];
$result = $client->purchaseOrderCreate($orderData);
```

### 分页查询

```php
// 使用分页类
$pager = new Pager(1, 20); // 第1页，每页20条

$params = [
    'start_time' => '2024-01-01 00:00:00',
    'end_time' => '2024-01-31 23:59:59'
];

// 合并分页参数
$params = array_merge($params, $pager->toArray());

$result = $client->tradeQuery($params);
```

## ⚠️ 错误处理

### 异常类型

- `WdtErpException`：API 调用异常
- `JsonException`：JSON 解析异常
- `Exception`：其他通用异常

### 错误处理示例

```php
try {
    $result = $client->goodsQuery($params);

    if ($result['status'] === 0) {
        // 成功处理
        $data = $result['data'];
        echo "查询成功，共找到 " . count($data) . " 条记录";
    } else {
        // API返回错误
        echo "API错误: " . $result['message'];
    }

} catch (WdtErpException $e) {
    // 网络或认证错误
    echo "API调用失败: " . $e->getMessage();
    echo "错误代码: " . $e->getCode();

} catch (JsonException $e) {
    // JSON解析错误
    echo "数据解析失败: " . $e->getMessage();

} catch (Exception $e) {
    // 其他错误
    echo "未知错误: " . $e->getMessage();
}
```

### 常见错误码

| 错误码 | 说明                  | 解决方案                          |
| ------ | --------------------- | --------------------------------- |
| 405    | Method Not Allowed    | 检查 API 接口 URL 是否正确        |
| 401    | Unauthorized          | 检查 APPKEY 和 APPSECRET 是否正确 |
| 400    | Bad Request           | 检查请求参数格式                  |
| 500    | Internal Server Error | 联系旺店通技术支持                |

## 📁 项目结构

```
wangdian-sdk-php/
├── src/                          # 源代码目录
│   ├── Client/                   # 客户端类
│   │   └── WdtErpClient.php     # 主要客户端类
│   ├── Exception/                # 异常类
│   │   └── WdtErpException.php  # 自定义异常
│   ├── Model/                    # 模型类
│   │   └── Pager.php            # 分页模型
│   └── wdtsdk.php               # 兼容性入口文件
├── test/                         # 测试目录
│   ├── .env                     # 环境变量配置
│   └── simple_test.php          # 简单测试示例
├── vendor/                       # Composer依赖
├── composer.json                 # Composer配置
├── composer.lock                 # 依赖锁定文件
└── README.md                     # 项目说明文档
```

## 🧪 测试

### 运行测试

```bash
# 运行环境变量测试（推荐）
php test/env_test.php

# 运行简单测试
php test/simple_test.php

# 运行官方示例
php src/demo.php

# 运行API示例（需要配置正确的API密钥）
php src/Api/openapi_shop_update.php
```

### 测试配置

确保 `.env` 文件中包含正确的测试配置：

```bash
# 测试环境配置
WDT_SID=test_sid
WDT_APPKEY=test_appkey
WDT_APPSECRET=test_secret:test_salt
WDT_BASE_URL=https://api.wangdian.cn
WDT_MULTI_TENANT_MODE=false
```

## 🤝 贡献指南

1. Fork 本仓库
2. 创建特性分支 (`git checkout -b feature/AmazingFeature`)
3. 提交更改 (`git commit -m 'Add some AmazingFeature'`)
4. 推送到分支 (`git push origin feature/AmazingFeature`)
5. 开启 Pull Request

### 开发规范

- 遵循 PSR-4 自动加载标准
- 使用 PSR-12 代码风格
- 添加适当的注释和文档
- 编写测试用例

## 📄 许可证

本项目采用 MIT 许可证 - 查看 [LICENSE](LICENSE) 文件了解详情。

## 📞 支持

- 📧 邮箱：457395070@qq.com
- 🐛 问题反馈：[GitHub Issues](https://github.com/your-username/wangdian-sdk-php/issues)
- 📖 旺店通官方文档：[API 文档](https://open.wangdian.cn/)

## 🙏 致谢

感谢旺店通提供的 API 接口支持。

---

**注意**：使用本 SDK 前，请确保您已获得旺店通开放平台的开发者权限，并正确配置了相关的 API 密钥。

### 🔒 安全提醒

- **环境变量安全**：`.env` 文件包含敏感信息，已被 `.gitignore` 排除，不会被提交到版本控制系统
- **密钥保护**：请妥善保管您的 API 密钥，不要将其提交到公开的代码仓库
- **生产环境**：生产环境建议使用系统环境变量或容器环境变量存储敏感配置信息
