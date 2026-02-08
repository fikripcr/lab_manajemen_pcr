<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages.lab.dashboard.index');
    }
}
