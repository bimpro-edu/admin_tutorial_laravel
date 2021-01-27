<?php

namespace ThaoHR\Support\Plugins\Dashboard\Widgets;

use ThaoHR\Plugins\Widget;
use ThaoHR\Repositories\User\UserRepository;

class NewUsers extends Widget
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
     * NewUsers constructor.
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
        return view('plugins.dashboard.widgets.new-users', [
            'count' => $this->users->newUsersCount()
        ]);
    }
}
