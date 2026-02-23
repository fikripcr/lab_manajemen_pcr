<?php

namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    /**
     * Unified Master Data page for E-Office
     * Combines: Jenis Layanan, Kategori Isian, Kategori Perusahaan, Perusahaan
     */
    public function index(Request $request)
    {
        $validTabs = ['jenis-layanan', 'kategori-isian', 'kategori-perusahaan', 'perusahaan'];
        $activeTab = $request->get('tab', 'jenis-layanan');

        if (!in_array($activeTab, $validTabs)) {
            abort(404, 'Invalid master data tab');
        }

        $pageTitle = 'Master Data E-Office';

        return view('pages.eoffice.master-data.index', compact('pageTitle', 'activeTab'));
    }
}
