<?php

namespace ThaoHR\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use ThaoHR\Repositories\Announcement\AnnouncementsRepository;

class NavbarItems
{
    /**
     * @var AnnouncementsRepository
     */
    private $announcements;

    public function __construct(AnnouncementsRepository $announcements)
    {
        $this->announcements = $announcements;
    }

    /**
     * Execute the hook action.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function compose(View $view)
    {
        $announcements = $this->announcements->latest(5);
        $announcements->load('creator');

        $view->with('announcements', $announcements);
    }
}
