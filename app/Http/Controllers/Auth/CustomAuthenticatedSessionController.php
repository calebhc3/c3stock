<?php

namespace App\Http\Controllers\Auth;

use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Http\Requests\LoginRequest;

class CustomAuthenticatedSessionController extends AuthenticatedSessionController
{
    public function store(LoginRequest $request): \Illuminate\Http\RedirectResponse
    {
        // Tentando autenticar o usuário usando o método de autenticação do Fortify
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirecionando baseado no e-mail
            if ($user->email === 'owner@c3stock.com') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/dashboard');
        }

        // Caso a autenticação falhe, retorna com erro
        return back()->withErrors([
            'email' => trans('auth.failed'),
        ]);
    }
}
