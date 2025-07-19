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
// 为了向后兼容，保留全局命名空间
require_once __DIR__ . '/Exception/WdtErpException.php';
require_once __DIR__ . '/Model/Pager.php';
require_once __DIR__ . '/Client/WdtErpClient.php';

// 将类映射到全局命名空间以保持兼容性
class_alias('WangDianSDK\Exception\WdtErpException', 'WdtErpException');
class_alias('WangDianSDK\Model\Pager', 'Pager');
class_alias('WangDianSDK\Client\WdtErpClient', 'WdtErpClient');
