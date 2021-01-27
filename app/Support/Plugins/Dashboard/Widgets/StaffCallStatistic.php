<?php

namespace ThaoHR\Support\Plugins\Dashboard\Widgets;

use ThaoHR\Plugins\Widget;
use Illuminate\Support\Facades\Auth;
use ThaoHR\User;
use ThaoHR\Repositories\Client\ClientRepository;

class StaffCallStatistic extends Widget
{
    /**
     * {@inheritdoc}
     */
    public $width = '12';

    /**
     * {@inheritdoc}
     */
    protected $permissions = 'dashboard.staff-statistic';

    /**
     * @var ClientRepository
     */
    private $clients;

    /**
     * NewUsers constructor.
     * @param ClientRepository $clients
     */
    public function __construct(ClientRepository $clients)
    {
        $this->clients = $clients;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $currentUser = Auth::user();
        $users = User::where('leader_id', $currentUser->id)->get();
        foreach ($users as &$user) {
            $user->statistic = [
                'total_client' => $this->clients->countClientBy($user->id),
                'total_client_called' => $this->clients->countClientCalled($user->id),
                'total_client_not_called' => $this->clients->countClientNotCalled($user->id),
                'total_client_not_answer' => $this->clients->countClientNotAnswer($user->id),
            ];
        }
        return view('plugins.dashboard.widgets.staff-call-statistic', [
            'users' => $users
        ]);
    }
}
