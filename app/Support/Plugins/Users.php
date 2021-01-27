<?php

namespace ThaoHR\Support\Plugins;

use ThaoHR\Plugins\Plugin;
use ThaoHR\Support\Sidebar\Item;

class Users extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('Người dùng'))
            ->route('users.index')
            ->icon('fas fa-users')
            ->active("users*")
            ->permissions('users.manage');
    }
}
