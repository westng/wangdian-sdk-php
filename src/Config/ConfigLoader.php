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
     * @param null|string $envPath 环境变量文件路径
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
            try {
                $dotenv = Dotenv::createImmutable(dirname($envPath));
                $dotenv->load();
            } catch (\Exception $e) {
                // 如果.env文件加载失败，继续使用其他配置方式
                error_log('Warning: Failed to load .env file: ' . $e->getMessage());
            }
        }

        // 从环境变量或常量获取配置
        self::$config = [
            'sid' => self::getEnvValue('WDT_SID', ''),
            'appkey' => self::getEnvValue('WDT_APPKEY', ''),
            'appsecret' => self::getEnvValue('WDT_APPSECRET', ''),
            'base_url' => self::getEnvValue('WDT_BASE_URL', 'https://api.wangdian.cn'),
            'multi_tenant_mode' => filter_var(
                self::getEnvValue('WDT_MULTI_TENANT_MODE', 'false'),
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

    /**
     * 重置配置缓存（用于测试或重新加载配置）.
     */
    public static function reset(): void
    {
        self::$config = null;
    }

    /**
     * 设置配置值（用于测试或动态配置）.
     *
     * @param string $key 配置键
     * @param mixed $value 配置值
     */
    public static function set(string $key, $value): void
    {
        if (self::$config === null) {
            self::load();
        }
        self::$config[$key] = $value;
    }

    /**
     * 获取环境变量值，支持多种获取方式.
     *
     * @param string $key 环境变量键名
     * @param mixed $default 默认值
     * @return mixed
     */
    private static function getEnvValue(string $key, $default = null)
    {
        // 1. 优先从 $_ENV 获取（.env文件加载的变量）
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        // 2. 从 $_SERVER 获取（服务器环境变量）
        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }

        // 3. 从 getenv() 获取（系统环境变量）
        $envValue = getenv($key);
        if ($envValue !== false) {
            return $envValue;
        }

        // 4. 从常量获取（如果定义了的话）
        $constantKey = strtoupper($key);
        if (defined($constantKey)) {
            return constant($constantKey);
        }

        // 5. 返回默认值
        return $default;
    }
}
