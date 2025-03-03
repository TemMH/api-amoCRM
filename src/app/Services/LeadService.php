<?php

namespace App\Services;

use Illuminate\Http\Request;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\CheckboxCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\CheckboxCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\CheckboxCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\LeadModel;



class LeadService
{

    public AmoCRMApiClient $apiClient;

    public function __construct(AmoCRMApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }


    public function createLead(array $data)
    {

        try {

            $contact = $this->addContact($data['name'], $data['phone'], $data['email']);


            $lead = $this->addLead($data['price'], $data['visit_duration'], $contact);

            return $lead;
        } catch (\Exception $e) {

            throw new \Exception('Ошибка при создании сделки-> ' . $e->getMessage());
        }
    }

    private function addContact(string $name, string $phone, string $email)
    {
        try {
            $contact = new ContactModel();
            $contact->setName($name);

            $phoneField = new MultitextCustomFieldValuesModel();
            $phoneField->setFieldCode('PHONE')
                ->setValues((new MultitextCustomFieldValueCollection())
                        ->add((new MultitextCustomFieldValueModel())
                                ->setEnum('WORK')
                                ->setValue($phone)
                        )
                );

            $emailField = new MultitextCustomFieldValuesModel();
            $emailField->setFieldCode('EMAIL')
                ->setValues((new MultitextCustomFieldValueCollection())
                        ->add((new MultitextCustomFieldValueModel())
                                ->setEnum('WORK')
                                ->setValue($email)
                        )
                );

            $customFieldsValues = new CustomFieldsValuesCollection();
            $customFieldsValues->add($phoneField);
            $customFieldsValues->add($emailField);

            $contact->setCustomFieldsValues($customFieldsValues);


            return $this->apiClient->contacts()->addOne($contact);
        } catch (AmoCRMApiException $e) {

            throw new \Exception('Ошибка при добавлении контакта' . $e->getMessage());
        }
    }

    private function addLead($price, $visit_duration, $contact)
    {
        try {

            $lead = new LeadModel();
            $lead->setName("Сделка №" . uniqid());
            $lead->setPrice($price);

            $customFieldsValues = new CustomFieldsValuesCollection();

            $booleanValue = ($visit_duration == 1);

            $customField = new CheckboxCustomFieldValuesModel();
            $customField->setFieldId(744461)
                ->setValues((new CheckboxCustomFieldValueCollection())
                        ->add((new CheckboxCustomFieldValueModel())
                                ->setValue($booleanValue)
                        )
                );

            $customFieldsValues->add($customField);

            $lead->setCustomFieldsValues($customFieldsValues);

            $contactsCollection = new \AmoCRM\Collections\ContactsCollection();
            $contactsCollection->add($contact);
            $lead->setContacts($contactsCollection);




            return $this->apiClient->leads()->addOne($lead);
        } catch (AmoCRMApiException $e) {
            throw new \Exception('Ошибка при добавлении сделки' . $e->getMessage());
        }
    }
}
