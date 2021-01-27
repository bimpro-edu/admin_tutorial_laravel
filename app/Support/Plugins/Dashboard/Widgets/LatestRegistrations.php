<?php

namespace ThaoHR\Support\Plugins\Dashboard\Widgets;

use ThaoHR\Plugins\Widget;
use ThaoHR\Repositories\User\UserRepository;

class LatestRegistrations extends Widget
{
    /**
     * {@inheritdoc}
     */
    public $width = '4';

    /**
     * {@inheritdoc}
     */
    protected $permissions = 'dashboard.user-statistic';

    /**
     * @var UserRepository
     */
    private $users;

    /**
     * LatestRegistrations constructor.
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
        return view('plugins.dashboard.widgets.latest-registrations', [
            'latestRegistrations' => $this->users->latest(6)
        ]);
    }
}
