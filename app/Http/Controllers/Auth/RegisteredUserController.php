<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:191',
                Rule::unique('users', 'email')->whereNull('deleted_at'),
            ],
            'alamat' => ['required', 'string', 'max:200'],
            'nomor_hp' => ['required', 'string', 'max:15', 'regex:/^08[0-9]{8,11}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $usernameBase = Str::slug(Str::before($request->email, '@'), '_');
        $usernameBase = $usernameBase !== '' ? $usernameBase : 'pelanggan';
        $username = $usernameBase;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $usernameBase . '_' . $counter;
            $counter++;
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $username,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'nomor_hp' => $request->nomor_hp,
            'password' => Hash::make($request->password),
            'role_id' => 2, // otomatis pelanggan
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
