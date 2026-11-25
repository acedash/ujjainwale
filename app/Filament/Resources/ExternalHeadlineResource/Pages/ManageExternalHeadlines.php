<?php

namespace App\Filament\Resources\ExternalHeadlineResource\Pages;

use App\Filament\Resources\ExternalHeadlineResource;
use App\Services\HeadlineCategorizerService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManageExternalHeadlines extends ManageRecords
{
    protected static string $resource = ExternalHeadlineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('auto_categorize')
                ->label('Auto-Categorize Headlines')
                ->icon('heroicon-o-sparkles')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Auto-Categorize Headlines')
                ->modalDescription('Scan and categorize all headlines using Ujjain local news keywords (Mahakal, Dharma, City, Crime, Politics, Business, Weather, Sports).')
                ->action(function () {
                    ini_set('max_execution_time', '300');
                    if (function_exists('set_time_limit')) {
                        @set_time_limit(300);
                    }
                    $count = app(HeadlineCategorizerService::class)->categorizeAll(onlyUncategorized: true);
                    Notification::make()
                        ->title('Categorization Complete')
                        ->body("Categorized {$count} headlines successfully.")
                        ->success()
                        ->send();
                }),
            Actions\CreateAction::make(),
        ];
    }
}
