<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        $role = $user->role;

        if ($role === 'administrator') {
            return redirect()->route('dashboard')->with('success', 'Login was  successfully.');
        } else if (
            $role === 'hr_manager' ||
            $role === 'team_leader' ||
            $role === 'ceo' ||
            $role === 'cfo') 
        {
            return redirect()->route('request-list')->with('success', 'Login was  successfully.');
        } else {
            return redirect()->route('request')->with('success', 'Login was  successfully.');
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
