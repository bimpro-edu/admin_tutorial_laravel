<?php

namespace ThaoHR\Support\Plugins\Dashboard;

use ThaoHR\Plugins\Plugin;
use ThaoHR\Support\Sidebar\Item;

class Dashboard extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('Dashboard'))
            ->route('dashboard')
            ->icon('fas fa-home')
            ->active("/");
    }
}
