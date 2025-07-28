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
- 🌐 **Guzzle 集成**：使用 Guzzle HTTP 客户端，支持自定义配置
- ⚡ **Hyperf 友好**：完美适配 Hyperf 框架，支持依赖注入

## 📦 安装

### 通过 Composer 安装

```bash
composer require westng/wangdian-sdk-php
```

## 🚀 快速开始

### 方式一：使用环境变量（推荐）

#### 1. 安装 SDK

```bash
composer require westng/wangdian-sdk-php
```


### 使用自定义 Guzzle 客户端（推荐用于 Hyperf 项目）

```php
<?php
use WangDianSDK\Client\WdtErpClient;
use GuzzleHttp\Client;

// 创建自定义Guzzle客户端
$httpClient = new Client([
    'timeout' => 60,
    'connect_timeout' => 15,
    'http_errors' => false,
    'headers' => [
        'User-Agent' => 'Hyperf-WangDianSDK/1.0'
    ]
]);

// 使用自定义客户端创建WangDian客户端
$client = new WdtErpClient(
    'your_sid_here',
    'your_appkey_here',
    'your_secret:salt',
    'https://api.wangdian.cn',
    false,
    $httpClient // 传入自定义Guzzle客户端
);
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



## 🌐 Guzzle 集成说明

### 为什么使用 Guzzle？

- **更好的 HTTP 处理**：Guzzle 提供了更强大的 HTTP 客户端功能
- **Hyperf 友好**：完美适配 Hyperf 框架的协程环境
- **自定义配置**：支持自定义超时、重试、中间件等配置
- **更好的错误处理**：提供更详细的 HTTP 错误信息

### 在 Hyperf 项目中使用

```php
<?php
use WangDianSDK\Client\WdtErpClient;
use GuzzleHttp\Client;

// 在Hyperf服务中
class WangDianService
{
    private WdtErpClient $client;

    public function __construct()
    {
        // 创建适合Hyperf的Guzzle客户端
        $httpClient = new Client([
            'timeout' => 30,
            'connect_timeout' => 10,
            'http_errors' => false,
            'headers' => [
                'User-Agent' => 'Hyperf-WangDianSDK/1.0'
            ]
        ]);

        $this->client = new WdtErpClient(
            env('WDT_SID'),
            env('WDT_APPKEY'),
            env('WDT_APPSECRET'),
            env('WDT_BASE_URL'),
            env('WDT_MULTI_TENANT_MODE', false),
            $httpClient
        );
    }
}
```

### 自定义 Guzzle 配置

```php
// 高级配置示例
$httpClient = new Client([
    'timeout' => 60,
    'connect_timeout' => 15,
    'http_errors' => false,
    'headers' => [
        'User-Agent' => 'Custom-WangDianSDK/1.0',
        'Accept' => 'application/json'
    ],
    'verify' => false, // 跳过SSL验证（开发环境）
    'allow_redirects' => [
        'max' => 5,
        'strict' => true
    ]
]);
```

## ⚠️ 错误处理

### 异常类型

- `WdtErpException`：API 调用异常
- `GuzzleException`：HTTP 请求异常（新增）
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
│   │   └── WdtErpClient.php     # 主要客户端类（已集成Guzzle）
│   ├── Exception/                # 异常类
│   │   └── WdtErpException.php  # 自定义异常
│   ├── Model/                    # 模型类
│   │   └── Pager.php            # 分页模型
│   └── wdtsdk.php               # 兼容性入口文件
├── test/                         # 测试目录
│   ├── .env                     # 环境变量配置
│   ├── simple_test.php          # 简单测试示例
│   └── guzzle_test.php          # Guzzle集成测试
├── examples/                     # 使用示例
│   └── hyperf_usage.php         # Hyperf项目使用示例
├── vendor/                       # Composer依赖
├── composer.json                 # Composer配置
├── composer.lock                 # 依赖锁定文件
└── README.md                     # 项目说明文档
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
