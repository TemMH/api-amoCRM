<?php

namespace App\Http\Controllers;

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


class FormController extends Controller
{

    public AmoCRMApiClient $apiClient;

    public function __construct(AmoCRMApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'price' => 'required|numeric',
            'visit_duration' => 'nullable|integer|min:0'
        ]);
    
        $contact = $this->addContact($validatedData['name'], $validatedData['phone'], $validatedData['email']);
        $lead = $this->addLead($validatedData['price'],$validatedData['visit_duration'], $contact);
        
        return back();
    }
    
    private function addContact($name, $phone, $email)
    {
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
    
        try {
            return $this->apiClient->contacts()->addOne($contact);
        } catch (AmoCRMApiException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
    

private function addLead($price, $visit_duration, $contact)
{
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

    try {
        return $this->apiClient->leads()->addOne($lead);
    } catch (AmoCRMApiException $e) {
        return response()->json(['error' => $e->getMessage()], 422);
    }
}

}

