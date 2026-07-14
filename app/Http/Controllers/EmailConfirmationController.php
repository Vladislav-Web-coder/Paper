<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EmailConfirmationController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        $newEmail = $request->query('new_email');

        if(User::where('email', $newEmail)->exists()) {
            return redirect()->route('settings.show', ['tab' => 'privacy'])
                ->with('error', 'This email address is already in use.');
        }

        $user->update([
            'email' => $newEmail,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('settings.show', ['tab' => 'privacy'])
            ->with('success', 'Your email address has been successfully updated.');
    }
}
