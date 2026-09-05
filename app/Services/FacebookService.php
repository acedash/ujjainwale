<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookService
{
    /**
     * Post an article to Facebook.
     */
    public function postArticle(Article $article): bool
    {
        $pageId = config('services.facebook.page_id');
        
        // Find the connected Facebook account for the author, or fallback to the first connected Facebook account in the system
        $socialAccount = \App\Models\SocialAccount::where('provider', 'facebook')
                            ->where('user_id', $article->author_id)
                            ->first();

        if (!$socialAccount) {
            $socialAccount = \App\Models\SocialAccount::where('provider', 'facebook')->first();
        }

        if (!$pageId || !$socialAccount || !$socialAccount->access_token) {
            Log::error('Facebook posting failed: Missing Page ID or no Facebook account connected.');
            return false;
        }

        $userAccessToken = $socialAccount->access_token;

        try {
            // First, get the page access token using the user access token
            $accountsUrl = "https://graph.facebook.com/v19.0/me/accounts?access_token={$userAccessToken}";
            $accountsResponse = Http::get($accountsUrl);
            
            $pageAccessToken = null;
            if ($accountsResponse->successful() && isset($accountsResponse->json()['data'])) {
                foreach ($accountsResponse->json()['data'] as $page) {
                    if ($page['id'] == $pageId) {
                        $pageAccessToken = $page['access_token'];
                        break;
                    }
                }
            }

            // Fallback to user token if page token not found (might fail if not permitted)
            $accessToken = $pageAccessToken ?? $userAccessToken;
            $url = "https://graph.facebook.com/v19.0/{$pageId}/feed";

            // Prepare the post content
            $message = $article->title . "\n\n" . $article->dek;

            $payload = [
                'message' => $message,
                'link' => route('articles.show', $article->slug),
                'access_token' => $accessToken,
            ];

            $response = Http::post($url, $payload);

            if ($response->successful()) {
                Log::info("Successfully posted article to Facebook: {$article->title}");
                return true;
            } else {
                Log::error('Facebook posting failed. Response: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Facebook posting failed with exception: ' . $e->getMessage());
            return false;
        }
    }
}
