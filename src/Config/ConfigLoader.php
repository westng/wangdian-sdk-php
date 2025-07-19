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

namespace WangDianSDK\Config;

use Dotenv\Dotenv;

class ConfigLoader
{
    private static ?array $config = null;

    /**
     * 加载配置.
     *
     * @param string $envPath 环境变量文件路径
     * @return array 配置数组
     */
    public static function load(?string $envPath = null): array
    {
        if (self::$config !== null) {
            return self::$config;
        }

        // 尝试加载环境变量文件
        if ($envPath === null) {
            $envPath = dirname(__DIR__, 2) . '/.env';
        }

        if (file_exists($envPath)) {
            $dotenv = Dotenv::createImmutable(dirname($envPath));
            $dotenv->load();
        }

        // 从环境变量或常量获取配置
        self::$config = [
            'sid' => $_ENV['WDT_SID'] ?? $_SERVER['WDT_SID'] ?? (defined('SID') ? SID : ''),
            'appkey' => $_ENV['WDT_APPKEY'] ?? $_SERVER['WDT_APPKEY'] ?? (defined('APPKEY') ? APPKEY : ''),
            'appsecret' => $_ENV['WDT_APPSECRET'] ?? $_SERVER['WDT_APPSECRET'] ?? (defined('APPSECRET') ? APPSECRET : ''),
            'base_url' => $_ENV['WDT_BASE_URL'] ?? $_SERVER['WDT_BASE_URL'] ?? (defined('BASE_URL') ? BASE_URL : 'https://api.wangdian.cn'),
            'multi_tenant_mode' => filter_var(
                $_ENV['WDT_MULTI_TENANT_MODE'] ?? $_SERVER['WDT_MULTI_TENANT_MODE'] ?? 'false',
                FILTER_VALIDATE_BOOLEAN
            ),
        ];

        return self::$config;
    }

    /**
     * 获取配置值
     *
     * @param string $key 配置键
     * @param mixed $default 默认值
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $config = self::load();
        return $config[$key] ?? $default;
    }

    /**
     * 验证配置是否完整.
     */
    public static function validate(): bool
    {
        $config = self::load();

        $required = ['sid', 'appkey', 'appsecret'];
        foreach ($required as $key) {
            if (empty($config[$key])) {
                return false;
            }
        }

        return true;
    }

    /**
     * 获取验证错误信息.
     */
    public static function getValidationErrors(): array
    {
        $config = self::load();
        $errors = [];

        if (empty($config['sid'])) {
            $errors[] = 'WDT_SID 未配置';
        }
        if (empty($config['appkey'])) {
            $errors[] = 'WDT_APPKEY 未配置';
        }
        if (empty($config['appsecret'])) {
            $errors[] = 'WDT_APPSECRET 未配置';
        }

        return $errors;
    }
}
