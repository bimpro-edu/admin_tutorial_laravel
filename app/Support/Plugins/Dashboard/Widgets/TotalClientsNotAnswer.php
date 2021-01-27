<?php

namespace ThaoHR\Support\Plugins\Dashboard\Widgets;

use ThaoHR\Plugins\Widget;
use ThaoHR\Repositories\Client\ClientRepository;

class TotalClientsNotAnswer extends Widget
{
    /**
     * {@inheritdoc}
     */
    public $width = '3';

    /**
     * {@inheritdoc}
     */
    protected $permissions = 'dashboard.client-not-answer';

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
        return view('plugins.dashboard.widgets.total-clients-not-answer', [
            'count' => $this->clientRepository->countNotAnswer()
        ]);
    }
}
