<?php

namespace ThaoHR\Support\Plugins\Dashboard\Widgets;

use ThaoHR\Plugins\Widget;
use ThaoHR\Repositories\Client\ClientRepository;

class TotalClientsCalled extends Widget
{
    /**
     * {@inheritdoc}
     */
    public $width = '3';

    /**
     * {@inheritdoc}
     */
    protected $permissions = 'dashboard.client-statistic';

    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * TotalUsers constructor.
     * @param ClientRepository $users
     */
    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return view('plugins.dashboard.widgets.total-clients-called', [
            'count' => $this->clientRepository->countCalled()
        ]);
    }
}
