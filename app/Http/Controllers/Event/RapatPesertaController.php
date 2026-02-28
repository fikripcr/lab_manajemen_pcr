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
        $this->service->store($request->validated());
        return jsonSuccess('Peserta berhasil ditambahkan');
    }

    public function update(RapatPesertaRequest $request, RapatPeserta $peserta)
    {
        $this->service->update($peserta, $request->validated());
        return jsonSuccess('Peserta berhasil diperbarui');
    }

    public function destroy(RapatPeserta $peserta)
    {
        $this->service->destroy($peserta);
        return jsonSuccess('Peserta berhasil dihapus');
    }
}
