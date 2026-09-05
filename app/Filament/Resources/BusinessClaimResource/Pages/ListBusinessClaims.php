<?php

namespace App\Filament\Resources\BusinessClaimResource\Pages;

use App\Filament\Resources\BusinessClaimResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBusinessClaims extends ListRecords
{
    protected static string $resource = BusinessClaimResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
