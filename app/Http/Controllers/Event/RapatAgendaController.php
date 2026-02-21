<?php
namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\RapatAgendaRequest;
use App\Models\Event\RapatAgenda;
use App\Services\Event\RapatAgendaService;

class RapatAgendaController extends Controller
{
    public function __construct(
        protected RapatAgendaService $rapatAgendaService
    ) {}

    public function store(RapatAgendaRequest $request)
    {
        try {
            $this->rapatAgendaService->store($request->validated());
            return jsonSuccess('Agenda berhasil ditambahkan');
        } catch (\Exception $e) {
            logError($e);
            return jsonError($e->getMessage());
        }
    }

    public function update(RapatAgendaRequest $request, RapatAgenda $agenda)
    {
        try {
            $this->rapatAgendaService->update($agenda, $request->validated());
            return jsonSuccess('Agenda berhasil diperbarui');
        } catch (\Exception $e) {
            logError($e);
            return jsonError($e->getMessage());
        }
    }

    public function destroy(RapatAgenda $agenda)
    {
        try {
            $this->rapatAgendaService->destroy($agenda);
            return jsonSuccess('Agenda berhasil dihapus');
        } catch (\Exception $e) {
            logError($e);
            return jsonError($e->getMessage());
        }
    }
}
