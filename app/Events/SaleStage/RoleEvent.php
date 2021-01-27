<?php

namespace ThaoHR\Events\SaleStage;

use ThaoHR\SaleStage;

abstract class SaleStageEvent
{
    /**
     * @var SaleStage
     */
    protected $saleStage;

    public function __construct(SaleStage $saleStage)
    {
        $this->saleStage = $saleStage;
    }

    /**
     * @return SaleStage
     */
    public function getSaleStage()
    {
        return $this->saleStage;
    }
}