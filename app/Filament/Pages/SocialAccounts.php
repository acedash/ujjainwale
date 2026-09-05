<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Auth;

class SocialAccounts extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-share';

    protected static string $view = 'filament.pages.social-accounts';

    protected static ?string $navigationGroup = 'Settings';

    public $connectedAccounts = [];

    public function mount()
    {
        $this->connectedAccounts = SocialAccount::where('user_id', Auth::id())->get();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('connectFacebook')
                ->label('Connect Facebook')
                ->icon('heroicon-o-link')
                ->color('primary')
                ->url(route('socialite.redirect', ['provider' => 'facebook']))
                ->openUrlInNewTab(false),
        ];
    }
}
