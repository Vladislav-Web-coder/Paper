<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function __invoke(Request $request): RedirectResponse|View
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
        return view('welcome');
    }
}
