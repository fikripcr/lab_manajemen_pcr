<?php
namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pmb\StoreSyaratRequest;
use App\Models\Pmb\Jalur;
use App\Models\Pmb\JenisDokumen;
use App\Models\Pmb\SyaratDokumenJalur;
use App\Services\Pmb\SyaratDokumenJalurService;
use Exception;
use Illuminate\Http\Request;

class SyaratDokumenJalurController extends Controller
{
    protected $SyaratService;

    public function __construct(SyaratDokumenJalurService $SyaratService)
    {
        $this->SyaratService = $SyaratService;
    }

    public function index(Request $request)
    {
        $jalurId      = decryptId($request->jalur);
        $jalur        = Jalur::findOrFail($jalurId);
        $syarat       = SyaratDokumenJalur::with('jenisDokumen')->where('jalur_id', $jalurId)->get();
        $jenisDokumen = JenisDokumen::all();

        return view('pages.pmb.syarat-jalur.index', compact('jalur', 'syarat', 'jenisDokumen'));
    }

    public function store(StoreSyaratRequest $request)
    {
        try {
            $this->SyaratService->updateOrCreate($request->validated());
            return jsonSuccess('Syarat dokumen berhasil ditambahkan.', route('pmb.syarat-jalur.index', ['jalur' => $request->jalur]));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(SyaratDokumenJalur $syarat)
    {
        try {
            $this->SyaratService->delete($syarat);
            return jsonSuccess('Syarat dokumen berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
