<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request)
    {
        $validated = $request->validated();
        $user = User::create([
            'name' => $validated["name"],
            'email' => $validated["email"],
            'password' => Hash::make($validated["password"]),
        ]);
        $timezone = $request->cookie('browser_timezone', 'UTC');
        $language = $request->cookie('browser_language', 'en');

        $user->settings = $user->settings->update([
            'timezone' => $timezone,
            'language' => $language,
        ]);

        $user->save();
        event(new Registered($user));
        Auth::login($user);
        return redirect()
            ->route('dashboard');
    }
}
