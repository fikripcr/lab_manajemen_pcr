<?php
namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;

class ProdiController extends Controller
{
    public function index()
    {
        return view('pages.pmb.prodi.index');
    }

    public function data()
    {
        // Placeholder for DataTables
        return datatables()->of(collect([]))->make(true);
    }
}
