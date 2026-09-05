<?php

namespace App\Filament\Resources\ExternalHeadlineResource\Pages;

use App\Filament\Resources\ExternalHeadlineResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageExternalHeadlines extends ManageRecords
{
    protected static string $resource = ExternalHeadlineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
