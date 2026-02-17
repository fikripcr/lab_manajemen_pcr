<?php
namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\RapatAgendaRequest;
use App\Models\Event\RapatAgenda;
use App\Services\Event\RapatAgendaService;

class RapatAgendaController extends Controller
{
    public function __construct(
        protected RapatAgendaService $service
    ) {}

    public function store(RapatAgendaRequest $request)
    {
        try {
            $this->service->store($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Agenda berhasil ditambahkan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(RapatAgendaRequest $request, RapatAgenda $agenda)
    {
        try {
            $this->service->update($agenda, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Agenda berhasil diperbarui',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(RapatAgenda $agenda)
    {
        try {
            $this->service->destroy($agenda);
            return response()->json([
                'success' => true,
                'message' => 'Agenda berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
