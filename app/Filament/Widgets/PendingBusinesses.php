<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BusinessResource;
use App\Models\Business;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingBusinesses extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->hasRole('Admin');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Business::query()->where('status', 'pending_review')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('address')->limit(30),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->button()
                    ->color('success')
                    ->action(fn (Business $record) => $record->update(['status' => 'claimed'])),
                Tables\Actions\Action::make('edit')
                    ->url(fn (Business $record): string => BusinessResource::getUrl('edit', ['record' => $record])),
            ])
            ->heading('Pending Business Listings');
    }
}
