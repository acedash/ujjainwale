<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ReviewResource;
use App\Models\Review;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingReviews extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->hasRole('Admin');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Review::query()->where('is_approved', false)
            )
            ->columns([
                Tables\Columns\TextColumn::make('business.name'),
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('rating'),
                Tables\Columns\TextColumn::make('body')->limit(50),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->button()
                    ->color('success')
                    ->action(fn (Review $record) => $record->update(['is_approved' => true])),
                Tables\Actions\Action::make('manage')
                    ->label('Manage in Reviews')
                    ->url(ReviewResource::getUrl('index')),
            ])
            ->heading('Pending Reviews');
    }
}
