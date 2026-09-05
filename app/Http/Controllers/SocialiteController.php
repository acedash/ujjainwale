<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     */
    public function redirect($provider)
    {
        if ($provider === 'facebook') {
            config(['services.facebook.redirect' => url("/auth/{$provider}/callback")]);
            return Socialite::driver('facebook')->setScopes([
                'pages_manage_posts', 
                'pages_read_engagement', 
                'pages_show_list',
                'public_profile'
            ])->redirect();
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     */
    public function callback($provider)
    {
        try {
            if ($provider === 'facebook') {
                config(['services.facebook.redirect' => url("/auth/{$provider}/callback")]);
            }
            
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            Log::error("Socialite Error ({$provider}): " . $e->getMessage());
            // Using Filament's redirect to dashboard if in filament
            return redirect(route('filament.admin.pages.social-accounts'))->with('error', 'Failed to authenticate with ' . $provider);
        }

        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }

        $expiresIn = null;
        if (isset($socialUser->expiresIn) && $socialUser->expiresIn) {
            $expiresIn = now()->addSeconds($socialUser->expiresIn);
        }

        $refreshToken = $socialUser->refreshToken ?? null;

        SocialAccount::updateOrCreate(
            [
                'user_id' => $user->id,
                'provider' => $provider,
                'provider_user_id' => $socialUser->getId(),
            ],
            [
                'access_token' => $socialUser->token,
                'refresh_token' => $refreshToken,
                'token_expires_at' => $expiresIn,
                'meta' => [
                    'nickname' => $socialUser->getNickname(),
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'avatar' => $socialUser->getAvatar(),
                ],
            ]
        );

        return redirect(route('filament.admin.pages.social-accounts'))->with('success', ucfirst($provider) . ' account connected successfully.');
    }
}
