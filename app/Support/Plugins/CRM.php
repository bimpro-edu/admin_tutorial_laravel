<?php

namespace ThaoHR\Support\Plugins;

use ThaoHR\Plugins\Plugin;
use ThaoHR\Support\Sidebar\Item;

class CRM extends Plugin
{
    public function sidebar()
    {
        $client = Item::create(__('Khách hàng'))
        ->route('clients.index')
        ->active("clients*")
        ->permissions('clients.index');
        
        $saleStage = Item::create(__('Trạng thái sale'))
        ->route('sale-stages.index')
        ->active("sale-stages*")
        ->permissions('sale-stages.index');
        
        $campaign = Item::create(__('Chiến dịch'))
        ->route('campaigns.index')
        ->active("campaigns*")
        ->permissions('campaigns.index');
        return Item::create(__('CRM'))
        ->href('#crm-dropdown')
        ->icon('fa fa-address-card')
        ->addChildren([
            $client,
            $saleStage,
            $campaign
        ]);
    }
}
