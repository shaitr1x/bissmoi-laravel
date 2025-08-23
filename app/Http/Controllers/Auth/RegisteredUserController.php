<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'city' => ['required', 'string', 'in:Yaoundé,Douala,Bertoua,Garoua,Ngaoundéré'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
        if (config('app.role_signup_enabled')) {
            $rules['role'] = ['required', 'in:client,merchant,admin'];
        }
        $request->validate($rules);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'city' => $request->city,
            'password' => Hash::make($request->password),
        ];
        if (config('app.role_signup_enabled')) {
            $userData['role'] = $request->role;
        }
        $user = User::create($userData);


    // Envoi email admin à chaque inscription - CORRECTION avec gestion d'erreur
    $adminEmails = [
        'noreply@bissmoi.com',
        'jordymbele948@gmail.com',
        'danieltambe522@gmail.com',
        'danielmama881@gmail.com',
        'badoanagabriel94@gmail.com'
    ];
    foreach ($adminEmails as $email) {
        try {
            \Mail::to($email)->send(new \App\Mail\NewUserRegistered($user));
            \Log::info("Email inscription admin envoyé à : {$email}");
        } catch (\Exception $e) {
            \Log::error("Erreur envoi email admin inscription à {$email}: " . $e->getMessage());
        }
    }
    
    // Envoi email de bienvenue à l'utilisateur
    try {
        \Mail::to($user->email)->send(new \App\Mail\WelcomeUser($user));
        \Log::info("Email bienvenue envoyé à : {$user->email}");
    } catch (\Exception $e) {
        \Log::error("Erreur envoi email bienvenue à {$user->email}: " . $e->getMessage());
    }
    
    event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
