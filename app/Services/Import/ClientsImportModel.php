<?php
namespace ThaoHR\Services\Import;
use Maatwebsite\Excel\Concerns\ToModel;
use ThaoHR\Client;
use ThaoHR\Repositories\Campaign\CampaignRepository;
use ThaoHR\Repositories\SaleStage\SaleStageRepository;
use ThaoHR\Repositories\Client\ClientRepository;
use Maatwebsite\Excel\Concerns\WithStartRow;
use ThaoHR\Repositories\User\UserRepository;

class ClientsImportModel implements ToModel, WithStartRow
{
    
    protected $rows = 0;
    protected $importRows = 0;
    
    /**
     * @var CampaignRepository
     */
    protected $campaignRepository;
    
    /**
     * @var SaleStageRepository
     */
    protected $saleStageRepository;
    
    /**
     * @var ClientRepository
     */
    protected $clientRepository;
    
    /**
     * @var UserRepository
     */
    protected $userRepository;
    
    /**
     * @var array
     */
    protected $duplicateClients = [];

    public function __construct(
        CampaignRepository $campaignRepository,
        SaleStageRepository $saleStageRepository,
        UserRepository $userRepository,
        ClientRepository $clientRepository)
    {
        $this->campaignRepository = $campaignRepository;
        $this->saleStageRepository = $saleStageRepository;
        $this->clientRepository = $clientRepository;
        $this->userRepository = $userRepository;
    }
    
    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
    
    /**
     * @param array $row
     *
     * @return Client|null
     */
    public function model(array $row)
    {
        ++$this->rows;
        $name = $row[0];
        $phone = trim($row[2]);
        $userEmail = trim($row[5]);
        $user = $this->userRepository->findByEmail($userEmail);
        if (empty($name) || empty($phone)) {
            return null;
        }
        $client = $this->clientRepository->findByPhone($phone);
        if (!empty($client)) {
            $this->duplicateClients[$this->rows] = $client;
            return null;
        }
        ++$this->importRows;
        $campaign = $this->campaignRepository->findByName(trim($row[6]));
        $saleStage = $this->saleStageRepository->findByName(trim($row[7]));
        return new Client([
            'name'     => $name,
            'code' => strtoupper(uniqid()),
            'national_id' => $row[1],
            'phone' => $row[2],
            'address' => $row[3],
            'disbursement_amount' => $row[4],
            'assign_user_id' => !empty($user)? $user->id:auth()->user()->id,
            'campaign_id' => !empty($campaign->id)? $campaign->id : null,
            'import_date' => date('Y-m-d'),
            'sale_stage_id' => !empty($saleStage->id)? $saleStage->id : null,
            'money_limit' => $row[8],
            'description' => $row[9],
            'status' => Client::STATUS_ACTIVE
        ]);
    }
    
    public function getRowCount(): int
    {
        return $this->rows;
    }
    
    public function getImportRowCount(): int
    {
        return $this->importRows;
    }
    
    public function getDuplicateClients(): array
    {
        return $this->duplicateClients;
    }
}

