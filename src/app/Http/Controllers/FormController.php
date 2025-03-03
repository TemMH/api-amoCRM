<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;

use App\Services\LeadService;

class FormController extends Controller
{
    private LeadService $leadService;

    public function __construct(LeadService $leadService)
    {
        $this->leadService = $leadService;
    }

    public function store(StoreLeadRequest $request)
    {
        $validatedData = $request->validated();

        $lead = $this->leadService->createLead($validatedData);

        return back()->with('success', 'Лид успешно добавлен!');
    }
}
