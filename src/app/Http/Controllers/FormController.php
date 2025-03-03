<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;

use App\Services\LeadService;
use Illuminate\Support\Facades\Log;

class FormController extends Controller
{
    private LeadService $leadService;

    public function __construct(LeadService $leadService)
    {
        $this->leadService = $leadService;
    }

    public function store(StoreLeadRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $lead = $this->leadService->createLead($validatedData);



            return back()->with('success', 'Лид успешно добавлен в AmoCRM!');
        } catch (\Exception $e) {
            Log::error('Ошибка в контроллере при создании сделки-> ' . $e->getMessage());

            abort(502, 'Произошла ошибка при обработке запроса.');
        }
    }
}
