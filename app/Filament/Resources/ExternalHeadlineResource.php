<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExternalHeadlineResource\Pages;
use App\Filament\Resources\ExternalHeadlineResource\RelationManagers;
use App\Models\ExternalHeadline;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExternalHeadlineResource extends Resource
{
    protected static ?string $model = ExternalHeadline::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_featured')
                    ->label('Featured Hero Article')
                    ->helperText('If enabled, this article may be prioritized in the homepage hero section.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_url')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl('https://ui-avatars.com/api/?name=News&color=7F9CF5&background=EBF4FF'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->weight('bold')
                    ->limit(50),
                Tables\Columns\TextColumn::make('newsSource.name')
                    ->label('Source')
                    ->searchable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\ToggleColumn::make('is_featured')
                    ->label('Featured')
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('news_source_id')
                    ->relationship('newsSource', 'name')
                    ->label('Source'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Only'),
            ])
            ->actions([
                Tables\Actions\Action::make('view_original')
                    ->label('Original URL')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (ExternalHeadline $record) => $record->source_url)
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageExternalHeadlines::route('/'),
        ];
    }
}
