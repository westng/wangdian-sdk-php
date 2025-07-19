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

namespace WangDianSDK\Model;

class Pager
{
    private int $pageSize = 0;

    private int $pageNo = 0;

    private bool $calcTotal = false;

    public function __construct(int $pageSize, int $pageNo = 0, bool $calcTotal = false)
    {
        $this->pageSize = $pageSize;
        $this->pageNo = $pageNo;
        $this->calcTotal = $calcTotal;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function getPageNo(): int
    {
        return $this->pageNo;
    }

    public function getCalcTotal(): bool
    {
        return $this->calcTotal;
    }
}
