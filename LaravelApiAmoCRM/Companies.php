<?php

namespace App\Services\AmoCRM;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\CompanyModel;
use App\Models\AmoCRM\AmoCompany;
use Illuminate\Support\Facades\Config;

class Companies
{
    private $apiClient;

    public function __construct(AmoCRMApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function sync($company_id){

        $company = $this->apiClient->companies()->getOne($company_id);
        $this->createOrUpdate($company);
    }

    public function createOrUpdate(CompanyModel $company)
    {

        $customFieldsCompany = $company->getCustomFieldsValues();
        $company_fields = config('amocrm.company_fields');
        $company_fields_custom =
            [
                'id' => $company->getId(),
                'name' => $company->getName(),
                'amo_created_at' => $company->getCreatedAt(),
                'amo_updated_at' => $company->getUpdatedAt(),
            ];

        if (!empty($customFieldsCompany)) {
            foreach ($company_fields as $key => $value) {
                if ($value['id'] === 'PHONE' || $value['id'] === 'EMAIL') {
                    $data_company = $customFieldsCompany->getBy('fieldCode', $value['id']);
                    if (!empty($data_company)) {
                        if (in_array($value['type'], ['select', 'multiselect', 'radiobutton'])) {
                            $data_value = array_column($data_company->getValues()->toArray(), 'id');
                        } else
                            $data_value = array_column($data_company->getValues()->toArray(), 'value');
                        $data = implode(",", $data_value);
                        $company_fields_custom[$key] = $data;
                    }
                }

                $data_company = $customFieldsCompany->getBy('fieldId', $value['id']);
                if (!empty($data_company)) {
                    $data_value = array_column($data_company->getValues()->toArray(), 'value');
                    $data = implode(",", $data_value);
                    $company_fields_custom[$key] = $data;
                }
            }
        }
        $amoCompany = AmoCompany::updateOrCreate(['id' => $company->getId()], $company_fields_custom);
        return $amoCompany;
    }

}
