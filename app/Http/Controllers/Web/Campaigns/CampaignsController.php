<?php

namespace ThaoHR\Http\Controllers\Web\Campaigns;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use ThaoHR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ThaoHR\Http\Requests\Campaign\CreateCampaignRequest;
use ThaoHR\Http\Requests\Campaign\UpdateCampaignRequest;
use ThaoHR\Repositories\Campaign\CampaignRepository;
use ThaoHR\Repositories\User\UserRepository;
use ThaoHR\Campaign;

/**
 * Class CampaignsController
 * @package ThaoHR\Http\Controllers
 */
class CampaignsController extends Controller
{
    /**
     * @var CampaignRepository
     */
    private $campaign;

    /**
     * CampaignsController constructor.
     * @param CampaignRepository $campaign
     */
    public function __construct(CampaignRepository $campaign)
    {
        $this->Campaigns = $campaign;
    }

    /**
     * Display page with all available campaigns.
     *
     * @return Factory|View
     */
    public function index(Request $request)
    {
        return view('campaign.index', [
                'Campaigns' => $this->Campaigns->paginate(20, $request->search)
            ]
        );
    }

    /**
     * Display form for creating new campaign.
     *
     * @return Factory|View
     */
    public function create()
    {
        return view('campaign.add-edit', [
            'edit' => false
        ]);
    }

    /**
     * Store newly created Campaign to database.
     *
     * @param CreateCampaignRequest $request
     * @return mixed
     */
    public function store(CreateCampaignRequest $request)
    {
        $this->Campaigns->create($request->all());

        return redirect()->route('campaigns.index')
            ->withSuccess(__('Campaign created successfully.'));
    }

    /**
     * Display for for editing specified campaign.
     *
     * @param Campaign $campaign
     * @return Factory|View
     */
    public function edit(Campaign $campaign)
    {
        return view('campaign.add-edit', [
            'campaign' => $campaign,
            'edit' => true
        ]);
    }

    /**
     * Update specified Campaign with provided data.
     *
     * @param Campaign $campaign
     * @param UpdateCampaignRequest $request
     * @return mixed
     */
    public function update(Campaign $campaign, UpdateCampaignRequest $request)
    {
        $this->Campaigns->update($campaign->id, $request->all());

        return redirect()->route('campaigns.index')
            ->withSuccess(__('Campaign updated successfully.'));
    }

    /**
     * Remove specified Campaign from system.
     *
     * @param Campaign $campaign
     * @param UserRepository $userRepository
     * @return mixed
     */
    public function destroy(Campaign $campaign, UserRepository $userRepository)
    {
        $this->Campaigns->delete($campaign->id);

        return redirect()->route('campaigns.index')
            ->withSuccess(__('Campaign deleted successfully.'));
    }
}
