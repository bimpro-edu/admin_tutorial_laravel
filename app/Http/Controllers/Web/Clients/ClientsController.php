<?php

namespace ThaoHR\Http\Controllers\Web\Clients;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use ThaoHR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ThaoHR\Http\Requests\Client\CreateClientRequest;
use ThaoHR\Http\Requests\Client\UpdateClientRequest;
use ThaoHR\Repositories\Client\ClientRepository;
use ThaoHR\Repositories\User\UserRepository;
use ThaoHR\Client;
use ThaoHR\Repositories\Campaign\CampaignRepository;
use ThaoHR\Repositories\SaleStage\SaleStageRepository;
use ThaoHR\Support\Enum\ClientStatus;
use Carbon\Carbon;
use ThaoHR\Http\Requests\Client\AssignClientRequest;
use ThaoHR\Http\Requests\Client\ExportClientRequest;
use ThaoHR\Services\Export\ExportClient;
use ThaoHR\Http\Requests\Client\ImportClientRequest;
use Maatwebsite\Excel\Facades\Excel;
use ThaoHR\Services\Import\ClientsImportModel;
use ThaoHR\User;
use ThaoHR\Services\Export\ExportAdapter;
use ThaoHR\Http\Requests\Client\DeleteBulkRequest;

/**
 * Class clientsController
 * @package ThaoHR\Http\Controllers
 */
class ClientsController extends Controller
{
    protected $exportService;
    /**
     * @var ClientRepository
     */
    protected $clientRepository;
    
    /**
     * @var SaleStageRepository
     */
    protected $saleStageRepository;
    
    /**
     * @var CampaignRepository
     */
    protected $campaignRepository;
    
    /**
     * @var UserRepository
     */
    protected $userRepository;
    
    protected $exelImport;

    /**
     * clientsController constructor.
     * @param ClientRepository $clientRepository
     */
    public function __construct(
        ClientRepository $clientRepository,
        SaleStageRepository $saleStageRepository,
        CampaignRepository $campaignRepository,
        UserRepository $userRepository
    )
    {
        $this->clientRepository = $clientRepository;
        $this->saleStageRepository = $saleStageRepository;
        $this->campaignRepository = $campaignRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display page with all available clients.
     *
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $params = $request->all();
        if (!empty($params['from_date'])) {
            $params['from_date'] = Carbon::parse($params['from_date'])->format('Y-m-d');
        }
        if (!empty($params['to_date'])) {
            $params['to_date'] = Carbon::parse($params['to_date'])->format('Y-m-d');
        }
        $limit = !empty($params['limit'])? $params['limit'] : 20;
        
        return view('client.index', [
                'clients' => $this->clientRepository->paginate($limit, $params),
                'campaigns' => ['' => 'Chọn chiến dịch'] + $this->campaignRepository->lists()->toArray(),
                'saleStages' => ['' => 'Chọn trạng thái sale'] + $this->saleStageRepository->lists()->toArray(),
                'users' => ['' => 'Chọn nhân viên'] +$this->userRepository->list(),
                'currentUser' => User::with('role')->where('id', auth()->user()->id)->first(),
                'rangeDates' => [
                    '' => 'Chọn khoảng thời gian',
                    '1month' => '1 tháng',
                    '2months' => '2 tháng',
                	'3months' => '3 tháng',
                	'6months' => '6 tháng',
                	'1year' => '1 năm'
                ],
                'reportTypes' => [
                    '' => 'Chọn báo cáo',
                    ExportAdapter::EXPORT_CLIENT => 'Danh sách khách hàng',
                    ExportAdapter::EXPORT_SALE_STAGE_FOREACH_STAFF => 'Thống kê trạng thái sale khách hàng'
                ]
            ]
        );
    }

    /**
     * Display form for creating new client.
     *
     * @return Factory|View
     */
    public function create()
    {
        return view('client.add-edit', [
            'edit' => false,
            'campaigns' => $this->campaignRepository->lists()->toArray(),
            'saleStages' => $this->saleStageRepository->lists()->toArray(),
            'users' => $this->userRepository->list(),
            'code' => strtoupper(uniqid()),
            'statuses' => ClientStatus::lists(),
            'currentUser' => auth()->user()
        ]);
    }

    /**
     * Store newly created Client to database.
     *
     * @param CreateClientRequest $request
     * @return mixed
     */
    public function store(CreateClientRequest $request)
    {
        $data = $request->all();
        $data['import_date'] = Carbon::parse($data['import_date'])->format('Y-m-d');
        $this->clientRepository->create($data);

        return redirect()->route('clients.index')
            ->withSuccess(__('Client created successfully.'));
    }

    /**
     * Display for for editing specified client.
     *
     * @param Client $client
     * @return Factory|View
     */
    public function edit(Client $client)
    {
        return view('client.add-edit', [
            'client' => $client,
            'edit' => true,
            'campaigns' => $this->campaignRepository->lists()->toArray(),
            'saleStages' => $this->saleStageRepository->lists()->toArray(),
            'users' => $this->userRepository->list(),
            'statuses' => ClientStatus::lists(),
            'clientHistories' => $this->clientRepository->clientHistories($client->id)
        ]);
    }

    /**
     * Update specified Client with provided data.
     *
     * @param Client $client
     * @param UpdateClientRequest $request
     * @return mixed
     */
    public function update(Client $client, UpdateClientRequest $request)
    {
        $data = $request->all();
        $data['import_date'] = Carbon::createFromFormat('d-m-Y', $data['import_date'])->format('Y-m-d');
        $data['disbursement_amount'] = str_replace(',', '', $data['disbursement_amount']);
        $data['money_limit'] = str_replace(',', '', $data['money_limit']);
        $this->clientRepository->update($client->id, $data);

        return redirect()->route('clients.index')
            ->withSuccess(__('Client updated successfully.'));
    }

    /**
     * Remove specified Client from system.
     *
     * @param Client $client
     * @param UserRepository $userRepository
     * @return mixed
     */
    public function destroy(Client $client, UserRepository $userRepository)
    {
        $this->clientRepository->delete($client->id);

        return redirect()->route('clients.index')
            ->withSuccess(__('Client deleted successfully.'));
    }
    
    /**
     * Assign client
     * 
     * @param AssignClientRequest $request
     */
    public function assignClient(AssignClientRequest $request)
    {
        $user = $request->user();
        $assignUserId = $request->get('reassign_user_id');
        $clientIds = $request->get('client_ids');
        foreach ($clientIds as $clientId) {
            $this->clientRepository->update($clientId, [
                'assign_user_id' => $assignUserId,
                'assign_by_user_id' => $user->id
            ]);
        }
        return redirect()->route('clients.index')
        ->withSuccess(__('Assign the client to user successfully.'));
    }
    
    
    /**
     * Export clients
     * 
     * @param ExportClientRequest $request
     */
    public function exportClient(ExportClientRequest $request)
    {
        $params = $request->all();
        $reportType = $request->get('report_type');
        $this->exportService = ExportAdapter::getInstance($reportType);
        $name = $this->exportService->export($params);
        
        return response()->download(public_path($name));
        
    }
    
    /**
     * Import client form
     * @return Factory|View
     */
    public function import()
    {
        return view('client.import');
    }
    
    /**
     * Import client
     * @param ImportClientRequest $request
     */
    public function makeImportClient(ImportClientRequest $request)
    {
        $clientImportModel = new ClientsImportModel($this->campaignRepository, $this->saleStageRepository, $this->userRepository, $this->clientRepository);
        Excel::import($clientImportModel, request()->file('file'));
        $totalRow = $clientImportModel->getRowCount();
        $totalImportRow = $clientImportModel->getImportRowCount();
        $duplicateClients = $clientImportModel->getDuplicateClients();
        $result = "Nhập thành công $totalImportRow"."/"."$totalRow khách hàng";
        if (empty($duplicateClients)) {
            return redirect()->route('clients.index')
            ->withSuccess($result);
        } else {
            return redirect()->route('clients.index')
            ->with('duplicate_results', [
                'duplicate_clients' => $duplicateClients,
                'message' => $result
            ]);
        }

    }
    
    public function deleteBulk(DeleteBulkRequest $request) {
        $clientIds = $request->get('client_ids');
        foreach ($clientIds as $clientId) {
            $this->clientRepository->delete($clientId);
        }
        
        return response()->json([
            'status' => 'success',
            'data' => 'Remove success'
        ]);
    }
}
