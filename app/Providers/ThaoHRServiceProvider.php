<?php

namespace ThaoHR\Providers;

use ThaoHR\Plugins\ThaoHRServiceProvider as BaseThaoHRServiceProvider;
use ThaoHR\Support\Plugins\Dashboard\Widgets\BannedUsers;
use ThaoHR\Support\Plugins\Dashboard\Widgets\LatestRegistrations;
use ThaoHR\Support\Plugins\Dashboard\Widgets\NewUsers;
use ThaoHR\Support\Plugins\Dashboard\Widgets\RegistrationHistory;
use ThaoHR\Support\Plugins\Dashboard\Widgets\TotalUsers;
use ThaoHR\Support\Plugins\Dashboard\Widgets\UnconfirmedUsers;
use ThaoHR\Support\Plugins\Dashboard\Widgets\UserActions;
use ThaoHR\Support\Plugins\Dashboard\Widgets\UserActivity;
use ThaoHR\Support\Plugins\Dashboard\Widgets\TotalClients;
use ThaoHR\Support\Plugins\Dashboard\Widgets\TotalClientsCalled;
use ThaoHR\Support\Plugins\Dashboard\Widgets\TotalClientsNotAnswer;
use ThaoHR\Support\Plugins\Dashboard\Widgets\TotalClientsNotCalled;
use ThaoHR\Support\Plugins\Dashboard\Widgets\StaffCallStatistic;

class ThaoHRServiceProvider extends BaseThaoHRServiceProvider
{
    /**
     * List of registered plugins.
     *
     * @return array
     */
    protected function plugins()
    {
        return [
            \ThaoHR\Support\Plugins\Dashboard\Dashboard::class,
            \ThaoHR\Support\Plugins\CRM::class,
            \ThaoHR\Support\Plugins\Users::class,
            \ThaoHR\Support\Plugins\RolesAndPermissions::class,
            \ThaoHR\Support\Plugins\Settings::class
        ];
    }

    /**
     * Dashboard widgets.
     *
     * @return array
     */
    protected function widgets()
    {
        return [
            UserActions::class,
            TotalUsers::class,
            NewUsers::class,
            BannedUsers::class,
            UnconfirmedUsers::class,
            TotalClients::class,
            TotalClientsCalled::class,
            TotalClientsNotCalled::class,
            TotalClientsNotAnswer::class,
            StaffCallStatistic::class,
            RegistrationHistory::class,
            LatestRegistrations::class,
            UserActivity::class,
        ];
    }
}
