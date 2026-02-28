<?php
namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pmb\StoreSyaratRequest;
use App\Models\Pmb\Jalur;
use App\Models\Pmb\JenisDokumen;
use App\Models\Pmb\SyaratDokumenJalur;
use App\Services\Pmb\SyaratDokumenJalurService;
use Illuminate\Http\Request;

class SyaratDokumenJalurController extends Controller
{
    public function __construct(protected SyaratDokumenJalurService $syaratDokumenJalurService)
    {}

    public function index(Request $request)
    {
        $jalurId      = decryptIdIfEncrypted($request->input('jalur'));
        $jalur        = Jalur::findOrFail($jalurId);
        $syarat       = $this->syaratDokumenJalurService->getSyaratByJalur($jalurId);
        $jenisDokumen = JenisDokumen::all();

        return view('pages.pmb.syarat-jalur.index', compact('jalur', 'syarat', 'jenisDokumen'));
    }

    public function store(StoreSyaratRequest $request)
    {
        $this->syaratDokumenJalurService->updateOrCreate($request->validated());
        return jsonSuccess('Syarat dokumen berhasil dikonfigurasi.', route('pmb.syarat-jalur.index', ['jalur' => $request->jalur]));
    }

    public function destroy(SyaratDokumenJalur $syarat)
    {
        $this->syaratDokumenJalurService->delete($syarat);
        return jsonSuccess('Syarat dokumen berhasil dihapus.');
    }
}
