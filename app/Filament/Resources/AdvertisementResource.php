<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdvertisementResource\Pages;
use App\Models\Advertisement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AdvertisementResource extends Resource
{
    protected static ?string $model = Advertisement::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Advertisements';

    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole(['Admin', 'Editor']) ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Advertisement Information')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. Shree Mahakal Heritage Hotel'),
                                Forms\Components\Select::make('placement')
                                    ->options(Advertisement::PLACEMENTS)
                                    ->required()
                                    ->native(false)
                                    ->helperText('Select where this ad appears on the portal.'),
                                Forms\Components\TextInput::make('subtitle')
                                    ->maxLength(255)
                                    ->placeholder('e.g. Special Pilgrim Offer • 4.9 ★'),
                                Forms\Components\TextInput::make('badge_text')
                                    ->maxLength(100)
                                    ->default('Ad · विज्ञापन')
                                    ->placeholder('e.g. Sponsored, Special Offer, प्रायोजक'),
                                Forms\Components\Textarea::make('description')
                                    ->rows(3)
                                    ->maxLength(1000)
                                    ->placeholder('Short promotional message or highlights...'),
                            ]),

                        Forms\Components\Section::make('Call to Action & Contact')
                            ->schema([
                                Forms\Components\TextInput::make('cta_text')
                                    ->maxLength(100)
                                    ->placeholder('e.g. Book Room Now, Order Now, Call Lab'),
                                Forms\Components\TextInput::make('cta_url')
                                    ->maxLength(500)
                                    ->placeholder('https://... or tel:+919827012345'),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(50)
                                    ->placeholder('+91 98270 12345'),
                                Forms\Components\TextInput::make('icon')
                                    ->maxLength(20)
                                    ->placeholder('e.g. 🛕, 🍧, 🩺, 💎, 🚕, 🕉️')
                                    ->helperText('Emoji or symbol shown in cards & side banners.'),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('AdSense / Custom Code (Optional)')
                            ->description('If using Google AdSense or raw HTML script, paste it here to override standard layout.')
                            ->collapsed()
                            ->schema([
                                Forms\Components\Textarea::make('ad_code')
                                    ->rows(4)
                                    ->placeholder('<script async src="..."></script>'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Banner Image')
                            ->description('Upload or provide image URL. For Billboard banners, recommended size is 1200x320px.')
                            ->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                                    ->collection('image')
                                    ->image()
                                    ->helperText('Upload banner image file (PNG, JPG, WebP).'),
                                Forms\Components\TextInput::make('image_url')
                                    ->label('Or Banner Image URL')
                                    ->maxLength(1000)
                                    ->placeholder('https://domain.com/banner.jpg')
                                    ->helperText('Optional direct external image link if not uploading.'),
                            ]),

                        Forms\Components\Section::make('Publishing & Scheduling')
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true)
                                    ->helperText('Inactive ads will not render on the portal.'),
                                Forms\Components\TextInput::make('order')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Lower numbers appear first within the same placement.'),
                                Forms\Components\DateTimePicker::make('starts_at')
                                    ->label('Start Date')
                                    ->nullable(),
                                Forms\Components\DateTimePicker::make('ends_at')
                                    ->label('End Date')
                                    ->nullable(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->collection('image')
                    ->rounded()
                    ->label('Image'),
                Tables\Columns\TextColumn::make('icon')
                    ->label('')
                    ->width('30px'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->weight('bold')
                    ->limit(35),
                Tables\Columns\TextColumn::make('placement')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'billboard' => 'warning',
                        'sponsor_card' => 'info',
                        'in_feed' => 'success',
                        'left_rail' => 'primary',
                        'right_rail' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => Advertisement::PLACEMENTS[$state] ?? $state)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('placement')
                    ->options(Advertisement::PLACEMENTS),
                Tables\Filters\TernaryFilter::make('is_active')
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
            'index' => Pages\ListAdvertisements::route('/'),
            'create' => Pages\CreateAdvertisement::route('/create'),
            'edit' => Pages\EditAdvertisement::route('/{record}/edit'),
        ];
    }
}
