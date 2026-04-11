<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class GuideSettingsController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();

        abort_unless($user !== null, 401);
        abort_unless(in_array($user->role, ['guide', 'tour_guide'], true), 403);

        return view('dashboards.guide-settings');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user !== null, 401);
        abort_unless(in_array($user->role, ['guide', 'tour_guide'], true), 403);

        $validated = $request->validateWithBag('guidePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'confirmed'],
        ]);

        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        return back()->with('status', 'guide-password-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user !== null, 401);
        abort_unless(in_array($user->role, ['guide', 'tour_guide'], true), 403);

        $request->validateWithBag('guideDeletion', [
            'password' => ['required', 'current_password'],
            'confirm_data_deletion' => ['accepted'],
        ]);

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
