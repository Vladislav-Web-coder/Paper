<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, DashboardService $dashboardService): View
    {
        $user = $request->user();
        $search = $request->input('search');
        $greeting = $user->settings->greeting;

        if ($search) {
            $data = $dashboardService->searchNotes($user, $search);
        } else {
            $data = $dashboardService->getDashboardData($user);
        }

        return view('dashboard', array_merge(
            ['greeting' => $greeting],
            $data
        ));
    }
}
