<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use App\Services\ArticleCategorizerService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListArticles extends ListRecords
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('auto_categorize')
                ->label('Auto-Categorize Articles')
                ->icon('heroicon-o-sparkles')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Auto-Categorize Articles')
                ->modalDescription('Scan and categorize all articles using Ujjain local news and cultural keywords (Dharma, History, Food, Literature, City, Society, Education, Crime, Business, etc.).')
                ->action(function () {
                    ini_set('max_execution_time', '300');
                    if (function_exists('set_time_limit')) {
                        @set_time_limit(300);
                    }
                    $result = app(ArticleCategorizerService::class)->categorizeAll(force: true, sync: true);
                    Notification::make()
                        ->title('Categorization Complete')
                        ->body("Successfully categorized {$result['categorized']} articles ({$result['categories_assigned']} category assignments).")
                        ->success()
                        ->send();
                }),
            Actions\CreateAction::make(),
        ];
    }
}
