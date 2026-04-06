<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        return redirect()->route($request->user()->dashboardRouteName());
    }

    public function tourist(): View
    {
        return view('dashboards.tourist');
    }

    public function guide(): View
    {
        return view('dashboards.guide');
    }

    public function admin(): View
    {
        return view('dashboards.admin');
    }
}
