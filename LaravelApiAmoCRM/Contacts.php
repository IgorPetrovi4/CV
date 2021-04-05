<?php

namespace App\Services\AmoCRM;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Models\ContactModel;
use App\Models\AmoCRM\AmoContact;

class Contacts
{
    private $apiClient;

    public function __construct(AmoCRMApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function sync($contact_id){

        $contact = $this->apiClient->contacts()->getOne($contact_id);
        $this->createOrUpdate($contact);
    }

    public function createOrUpdate(ContactModel $contact)
    {
        $customFieldsContact = $contact->getCustomFieldsValues();
        $contact_fields = config('amocrm.contact_fields');
        $contact_fields_custom =
            [
                'id' => $contact->getId(),
                'name' => $contact->getName(),
                'first_name' => $contact->getFirstName(),
                'last_name' => $contact->getLastName(),
                'amo_created_at' => $contact->getCreatedAt(),
                'amo_updated_at' => $contact->getUpdatedAt(),
            ];

        if (!empty($customFieldsContact)) {
            foreach ($contact_fields as $key => $value) {
                if ($value['id'] === 'PHONE' || $value['id'] === 'EMAIL') {
                    $data_contacts = $customFieldsContact->getBy('fieldCode', $value['id']);
                    if (!empty($data_contacts)) {
                        if (in_array($value['type'], ['select', 'multiselect', 'radiobutton'])) {
                            $data_value = array_column($data_contacts->getValues()->toArray(), 'id');
                        } else
                            $data_value = array_column($data_contacts->getValues()->toArray(), 'value');
                        $data = implode(",", $data_value);
                        $contact_fields_custom[$key] = $data;
                    }
                }
            }

        }
        $amoContact = AmoContact::updateOrCreate(['id' => $contact->getId()], $contact_fields_custom);
        return $amoContact;
    }


}
