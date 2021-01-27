<?php

namespace ThaoHR\Support\Plugins;

use ThaoHR\Plugins\Plugin;
use ThaoHR\Support\Sidebar\Item;
use ThaoHR\User;

class Settings extends Plugin
{
    public function sidebar()
    {
        $general = Item::create(__('Thông tin chung'))
            ->route('settings.general')
            ->active("settings")
            ->permissions('settings.general');

        $authAndRegistration = Item::create(__('Đăng ký và xác thực'))
            ->route('settings.auth')
            ->active("settings/auth")
            ->permissions('settings.auth');

        $notifications = Item::create(__('Thông báo'))
            ->route('settings.notifications')
            ->active("settings/notifications")
            ->permissions(function (User $user) {
                return $user->hasPermission('settings.notifications');
            });
        $roles = Item::create(__('Quyền'))
            ->route('roles.index')
            ->active("roles*")
            ->permissions('roles.manage');

        $permissions = Item::create(__('Phân quyền'))
            ->route('permissions.index')
            ->active("permissions*")
            ->permissions('permissions.manage');
        $activity = Item::create(__('Lịch sử người dùng'))
            ->route('activity.index')
            ->active("activity*")
            ->permissions('users.activity');
        $announcements = Item::create(__('Thông báo'))
            ->route('announcements.index')
            ->active("announcements*")
            ->permissions('announcements.manage');
        return Item::create(__('Cài đặt'))
            ->href('#settings-dropdown')
            ->icon('fas fa-cogs')
            ->permissions(['settings.general', 'settings.auth', 'settings.notifications'])
            ->addChildren([
                $general,
                $authAndRegistration,
                $notifications,
                $roles,
                $permissions,
                $activity,
                $announcements
            ]);
    }
}
