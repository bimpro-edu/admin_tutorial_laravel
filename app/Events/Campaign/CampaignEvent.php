<?php

namespace ThaoHR\Events\Campaign;

use ThaoHR\Campaign;

abstract class CampaignEvent
{
    /**
     * @var Campaign
     */
    protected $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * @return Campaign
     */
    public function getCampaign()
    {
        return $this->campaign;
    }
}