<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;

use App\Services\LeadService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;

class FormController extends Controller
{
    private LeadService $leadService;

    public function __construct(LeadService $leadService)
    {
        $this->leadService = $leadService;
    }

    public function store(StoreLeadRequest $request): RedirectResponse
    {
        try {
            $validatedData = $request->validated();
            $lead = $this->leadService->createLead($validatedData);



            return redirect()->back()->with('success', 'Сделка добавлена в AmoCRM');
        } catch (\Exception $e) {
            Log::error('Ошибка в контроллере при создании сделки-> ' . $e->getMessage());

            abort(502, 'Произошла ошибка при обработке запроса.');
        }
    }
}
