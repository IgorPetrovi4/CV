<?php

namespace App\Services\AmoCRM;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\Interfaces\HasOrderInterface;
use AmoCRM\Filters\LeadsFilter;
use AmoCRM\Filters\UnsortedFilter;
use AmoCRM\Models\TagModel;
use AmoCRM\Models\LeadModel;
use App\Models\AmoCRM\AmoLead;
use App\Models\AmoCRM\AmoLeadTag;
use App\Models\MoySklad\MsOrder;
use App\Services\AmoCRM\Companies;
use App\Services\AmoCRM\Contacts;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\TagsCollection;
use function Symfony\Component\String\u;

class Leads
{

    private $apiClient;

    public function __construct(AmoCRMApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function sync($lead_id)
    {
        try {
            $lead = $this->apiClient->leads()->getOne($lead_id, [LeadModel::CONTACTS]);
            $this->createOrUpdate($lead);
            return $lead;
        } catch (AmoCRMApiException $e) {
            throw $e;
            die;
        }
    }

    public function syncLatest($limit = 1000)
    {
        $leadsService = $this->apiClient->leads();
        $limit_crm = 50;
        $count_pages = $limit / $limit_crm;
        $unsortedFiler = new UnsortedFilter();
        $unsortedFiler->setOrder('id', HasOrderInterface::SORT_DESC);
        try {
            $leadsCollection = $leadsService->get($unsortedFiler, [LeadModel::CONTACTS]);

            for ($page = 1; $page <= $count_pages; $page++) {
                try {
                    foreach ($leadsCollection as $lead) {
                        $this->createOrUpdate($lead);
                    }
                    $leadsCollection = $leadsService->nextPage($leadsCollection);
                } catch (AmoCRMApiException $e) {
                    break;
                }
            }
        } catch (AmoCRMApiException $e) {
            throw $e;
            die;
        }
    }

    public function createOrUpdate(LeadModel $lead)
    {
        $lead_fields_system = [
            'id' => $lead->getId(),
            'name' => $lead->getName(),
            'price' => $lead->getPrice(),
            'responsible_user_id' => $lead->getResponsibleUserId(),
            'status_id' => $lead->getStatusId(),
            'pipeline_id' => $lead->getPipelineId(),
            'loss_reason_id' => $lead->getLossReasonId(),
            'amo_closed_at' => $lead->getClosedAt(),
            'amo_created_at' => $lead->getCreatedAt(),
            'amo_updated_at' => $lead->getUpdatedAt(),
            'amo_is_deleted' => $lead->getIsDeleted(),
        ];

        $leadCustomFields = $lead->getCustomFieldsValues();
        $lead_fields_config = config('amocrm.lead_fields');

        if (!empty($leadCustomFields)) {

            foreach ($lead_fields_config as $key => $value) {
                $data_leads = $leadCustomFields->getBy('fieldId', $value['id']);

                if (!empty($data_leads)) {
                    $data_value = array_column($data_leads->getValues()->toArray(), 'value');
                    $data = implode(",", $data_value);
                    $lead_fields_system[$key] = $data;
                }
            }
        }
        $amoLead = AmoLead::updateOrCreate(['id' => $lead->getId()], $lead_fields_system);


        // create or contacts
        $amo_contacts = $lead->getContacts();
        if (!empty($amo_contacts)) {
            try {
                $amo_contacts_id = $amo_contacts->getBy('isMain', true)->getId();
                $contact = $this->apiClient->contacts()->getOne($amo_contacts_id);
                $contacts = resolve(Contacts::class);
                $contacts->createOrUpdate($contact);
                $amoLead->main_contact_id = $amo_contacts_id;
                $amoLead->save();
                $amoLead->contacts()->sync($amo_contacts_id);
            } catch (AmoCRMApiException $e) {
                throw $e;
                die;
            }

        }

        // with company_fields
        $amo_company = $lead->getCompany();
        if (!empty($amo_company)) {
            try {
                $company_id = $amo_company->getId();
                $company = $this->apiClient->companies()->getOne($company_id);
                $company_service = resolve(Companies::class);
                $company_service->createOrUpdate($company);
                $amoLead->company_id = $company_id;
                $amoLead->save();
            } catch (AmoCRMApiException $e) {
                throw $e;
                die;
            }
        }

        // with tags
        $amo_tags = $lead->getTags();
        if (!empty($amo_tags)) {
            try {
                $arr_tags_id = [];
                foreach ($amo_tags as $tag) {
                    $this->createOrUpdateTag($tag);
                    $arr_tags_id[] = $tag->getId();
                }
                $amoLead->tags()->sync($arr_tags_id);
            } catch (AmoCRMApiException $e) {
                throw $e;
                die;
            }
        }


        // TODO Если к сделке еще не привязан заказ МС - создаем его
        // if( is_null($amo_lead->ms_order) ) {
        //     $amo_lead->ms_order = (new SyncOrders())->newMsOrderFromLead($lead->id);
        // }

        // return lead eloquent model
    }

    public function createOrUpdateTag(TagModel $tag)
    {
        $amoTag = AmoLeadTag::findOrNew($tag->getId());
        $amoTag->id = $tag->getId();
        $amoTag->name = $tag->getName();
        $amoTag->save();

        return $amoTag;
    }

    /**
     * Получение данных о сделке и связанных сущностях для вывода в Амо
     */
    public function getLeadInfo(AmoLead $lead)
    {
        $data = [];

        // Если к сделке еще не привязан заказ МС - создаем его
        // if( is_null($lead->ms_order) ) {

        //     $lead->ms_order = (new SyncOrders())->newMsOrderFromLead($lead->id);

        //     // Если создание заказа из сделки вернуло false - сделка еще не принята в рабоу, ничего с ней не можем делать
        //     if(!$lead->ms_order)
        //         return $data;
        // }

        // Осуществляем сборку данных для вывода в Амо
        // $data['products'] = $this->getLeadProducts();

        return $data;
    }

    public function newLeadFromMsOrder(MsOrder $order)
    {
        // Если уже есть привязанная сделка - выходим
        if (!is_null($order->amo_lead)) {
            return false;
        }

        // Создаем неразобранную сделку в amoCRM с привязанным контактом/компанией через сервис неразобранных сделок
        $lead_id = resolve(Unsorted::class)->newUnsortedFromMsOrder($order);


        // Создаем модель сделки AmoLead и привязываем ее к модели МС заказа MsOrder (только id сделки и заказа, остальные данные подтянем уже из Амо)
        AmoLead::create(['id' => $lead_id, 'ms_order_id' => $order->id]);

        // Подтягивам остальные данные сделки со связанными сущностями в БД (проверить возможно они быстро будут тянуться по хуку и этот вызов будет избыточен)
        // $this->sync($lead_id);

        // Return AmoLead $lead
    }

    public function addTagToLead($lead_id, $tag_name)
    {
        $lead = $this->apiClient->leads()->getOne($lead_id);

        $lead->setTags((new TagsCollection())
            ->add(
                (new TagModel())
                    ->setName($tag_name)
            )
        );

        $this->apiClient->leads()->updateOne($lead);


    }

}
