<?php

namespace ThaoHR\Http\Controllers\Web\SaleStages;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use ThaoHR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ThaoHR\Http\Requests\SaleStage\CreateSaleStageRequest;
use ThaoHR\Http\Requests\SaleStage\UpdateSaleStageRequest;
use ThaoHR\Repositories\SaleStage\SaleStageRepository;
use ThaoHR\Repositories\User\UserRepository;
use ThaoHR\SaleStage;

/**
 * Class SaleStagesController
 * @package ThaoHR\Http\Controllers
 */
class SaleStagesController extends Controller
{
    /**
     * @var SaleStageRepository
     */
    protected $saleStageRepository;

    /**
     * SaleStagesController constructor.
     * @param SaleStageRepository $saleStages
     */
    public function __construct(SaleStageRepository $saleStages)
    {
        $this->saleStageRepository = $saleStages;
    }

    /**
     * Display page with all available sale-stages.
     *
     * @return Factory|View
     */
    public function index(Request $request)
    {
        return view('sale-stage.index', [
                'saleStages' => $this->saleStageRepository->paginate(20, $request->search)
            ]
        );
    }

    /**
     * Display form for creating new sale-stage.
     *
     * @return Factory|View
     */
    public function create()
    {
        return view('sale-stage.add-edit', [
            'edit' => false
        ]);
    }

    /**
     * Store newly created SaleStage to database.
     *
     * @param CreateSaleStageRequest $request
     * @return mixed
     */
    public function store(CreateSaleStageRequest $request)
    {
        $this->saleStageRepository->create($request->all());

        return redirect()->route('sale-stages.index')
            ->withSuccess(__('SaleStage created successfully.'));
    }

    /**
     * Display for for editing specified sale-stage.
     *
     * @param SaleStage $saleStage
     * @return Factory|View
     */
    public function edit(SaleStage $saleStage)
    {
        return view('sale-stage.add-edit', [
            'saleStage' => $saleStage,
            'edit' => true
        ]);
    }

    /**
     * Update specified SaleStage with provided data.
     *
     * @param SaleStage $saleStage
     * @param UpdateSaleStageRequest $request
     * @return mixed
     */
    public function update(SaleStage $saleStage, UpdateSaleStageRequest $request)
    {
        $this->saleStageRepository->update($saleStage->id, $request->all());

        return redirect()->route('sale-stages.index')
            ->withSuccess(__('SaleStage updated successfully.'));
    }

    /**
     * Remove specified SaleStage from system.
     *
     * @param SaleStage $saleStage
     * @param UserRepository $userRepository
     * @return mixed
     */
    public function destroy(SaleStage $saleStage, UserRepository $userRepository)
    {
        $this->saleStageRepository->delete($saleStage->id);

        return redirect()->route('sale-stages.index')
            ->withSuccess(__('SaleStage deleted successfully.'));
    }
}
