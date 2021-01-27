<?php

namespace ThaoHR\Support\Plugins\Dashboard\Widgets;

use ThaoHR\Plugins\Widget;
use ThaoHR\Repositories\User\UserRepository;
use ThaoHR\Support\Enum\UserStatus;

class UnconfirmedUsers extends Widget
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
    private $users;

    /**
     * UnconfirmedUsers constructor.
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return view('plugins.dashboard.widgets.unconfirmed-users', [
            'count' => $this->users->countByStatus(UserStatus::UNCONFIRMED)
        ]);
    }
}
