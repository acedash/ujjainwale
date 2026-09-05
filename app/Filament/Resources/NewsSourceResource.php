<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsSourceResource\Pages;
use App\Filament\Resources\NewsSourceResource\RelationManagers;
use App\Models\NewsSource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NewsSourceResource extends Resource
{
    protected static ?string $model = NewsSource::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('Admin');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('feed_url')
                    ->required()
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('website_url')
                    ->url()
                    ->maxLength(255),
                Forms\Components\SpatieMediaLibraryFileUpload::make('logo')
                    ->collection('logo')
                    ->image()
                    ->maxSize(1024),
                Forms\Components\Select::make('default_category_id')
                    ->relationship('defaultCategory', 'name')
                    ->label('Default Category')
                    ->nullable()
                    ->helperText('All headlines from this source will be automatically assigned to this category.'),
                Forms\Components\Toggle::make('active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('logo')
                    ->collection('logo')
                    ->circular()
                    ->label('')
                    ->defaultImageUrl('https://ui-avatars.com/api/?name=News&color=7F9CF5&background=EBF4FF'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('feed_url')
                    ->limit(50)
                    ->color('gray'),
                Tables\Columns\ToggleColumn::make('active'),
            ])
            ->defaultSort('name', 'asc')
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Active Only'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsSources::route('/'),
            'create' => Pages\CreateNewsSource::route('/create'),
            'edit' => Pages\EditNewsSource::route('/{record}/edit'),
        ];
    }
}
