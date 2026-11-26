<?php

namespace App\Filament\Resources\ExternalHeadlineResource\Pages;

use App\Filament\Resources\ExternalHeadlineResource;
use App\Services\HeadlineCategorizerService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Artisan;

class ManageExternalHeadlines extends ManageRecords
{
    protected static string $resource = ExternalHeadlineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('fetch_news')
                ->label('Fetch Live News')
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->tooltip('Fetch new articles from configured RSS feeds')
                ->action(function () {
                    ini_set('max_execution_time', '300');
                    if (function_exists('set_time_limit')) {
                        @set_time_limit(300);
                    }
                    Artisan::call('app:fetch-headlines');
                    Notification::make()
                        ->title('Live News Updated')
                        ->body('Fetched and categorized latest news headlines.')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('auto_categorize')
                ->label('Auto-Categorize All Headlines')
                ->icon('heroicon-o-sparkles')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Auto-Categorize All Headlines')
                ->modalDescription('Scan all headlines to detect "उज्जैन समाचार" (Ujjain News), Mahakal/Dharma, Crime, Politics, Business, Weather, and Sports.')
                ->action(function () {
                    ini_set('max_execution_time', '300');
                    if (function_exists('set_time_limit')) {
                        @set_time_limit(300);
                    }
                    $count = app(HeadlineCategorizerService::class)->categorizeAll(onlyUncategorized: false);
                    Notification::make()
                        ->title('Categorization Complete')
                        ->body("Successfully categorized {$count} headlines.")
                        ->success()
                        ->send();
                }),

            Actions\CreateAction::make(),
        ];
    }
}
