<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    // Fungsi ini sekarang bisa menerima 'google' atau 'github'
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            // Cari berdasarkan email (karena email unik)
            $user = User::where('email', $socialUser->email)->first();

            if ($user) {
                // Update info jika perlu
                $user->update(['avatar' => $socialUser->avatar]);
                Auth::login($user);
            } else {
                // Buat user baru jika belum ada
                $user = User::create([
                    'name' => $socialUser->name ?? $socialUser->nickname,
                    'email' => $socialUser->email,
                    'avatar' => $socialUser->avatar,
                    'password' => Hash::make(Str::random(16)),
                ]);
                $user->assignRole('Mahasiswa');
                Auth::login($user);
            }

            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', "Gagal login dengan $provider: " . $e->getMessage());
        }
    }
}