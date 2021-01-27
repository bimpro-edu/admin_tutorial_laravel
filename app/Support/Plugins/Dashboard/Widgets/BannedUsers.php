<?php

namespace ThaoHR\Support\Plugins\Dashboard\Widgets;

use ThaoHR\Plugins\Widget;
use ThaoHR\Repositories\User\UserRepository;
use ThaoHR\Support\Enum\UserStatus;

class BannedUsers extends Widget
{
    /**
     * {@inheritdoc}
     */
    public $width = '3';

    /**
     * {@inheritdoc}
     */
    protected $permissions = 'dashboard.user-statistic';

    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * BannedUsers constructor.
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        return view('plugins.dashboard.widgets.banned-users', [
            'count' => $this->users->countByStatus(UserStatus::BANNED)
        ]);
    }
}
