<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\RapatEntitasRequest;
use App\Models\Pemutu\RapatEntitas;
use App\Services\Pemutu\RapatEntitasService;

class RapatEntitasController extends Controller
{
    public function __construct(
        protected RapatEntitasService $service
    ) {}

    public function store(RapatEntitasRequest $request)
    {
        try {
            $this->service->store($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Entitas berhasil ditambahkan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(RapatEntitasRequest $request, RapatEntitas $entitas)
    {
        try {
            $this->service->update($entitas, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Entitas berhasil diperbarui',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(RapatEntitas $entitas)
    {
        try {
            $this->service->destroy($entitas);
            return response()->json([
                'success' => true,
                'message' => 'Entitas berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
