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

namespace WangDianSDK\Exception;

class WdtErpException extends \Exception
{
    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code);
    }
}
