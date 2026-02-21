<?php
namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\RapatPesertaRequest;
use App\Models\Event\Rapat;
use App\Models\Event\RapatPeserta;
use App\Models\User;
use App\Services\Event\RapatPesertaService;
use Illuminate\Http\Request;

class RapatPesertaController extends Controller
{
    public function __construct(
        protected RapatPesertaService $service
    ) {}

    public function create(Request $request)
    {
        $rapatId = decryptIdIfEncrypted($request->rapat_id);
        $rapat   = Rapat::findOrFail($rapatId);
        $peserta = new RapatPeserta(['rapat_id' => $rapatId]);
        $users   = User::with('roles')->get();
        return view('pages.event.rapat.peserta.create-edit-ajax', compact('rapat', 'peserta', 'users'));
    }

    public function edit(RapatPeserta $peserta)
    {
        $rapat = $peserta->rapat;
        $users = User::with('roles')->get();
        return view('pages.event.rapat.peserta.create-edit-ajax', compact('rapat', 'peserta', 'users'));
    }

    public function store(RapatPesertaRequest $request)
    {
        try {
            $this->service->store($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Peserta berhasil ditambahkan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(RapatPesertaRequest $request, RapatPeserta $peserta)
    {
        try {
            $this->service->update($peserta, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Peserta berhasil diperbarui',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(RapatPeserta $peserta)
    {
        try {
            $this->service->destroy($peserta);
            return response()->json([
                'success' => true,
                'message' => 'Peserta berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
