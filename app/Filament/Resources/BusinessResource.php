<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BusinessResource\Pages;
use App\Models\Business;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Http;

class BusinessResource extends Resource
{
    protected static ?string $model = Business::class;

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('Admin');
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Basic Information')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('description')
                                    ->maxLength(65535)
                                    ->columnSpanFull(),
                            ]),
                        
                        Forms\Components\Section::make('Location & Contact')
                            ->schema([
                                Forms\Components\TextInput::make('address')
                                    ->maxLength(255)
                                    ->columnSpanFull()
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('geocode')
                                            ->icon('heroicon-m-map-pin')
                                            ->action(function (Forms\Get $get, Forms\Set $set) {
                                                $address = $get('address');
                                                if (! $address) {
                                                    return;
                                                }
                
                                                $response = Http::withHeaders([
                                                    'User-Agent' => 'IndoreCityDirectory/1.0',
                                                ])->get('https://nominatim.openstreetmap.org/search', [
                                                    'q' => $address,
                                                    'format' => 'json',
                                                    'limit' => 1,
                                                ]);
                
                                                if ($response->successful() && ! empty($response->json())) {
                                                    $result = $response->json()[0];
                                                    $set('lat', $result['lat']);
                                                    $set('lng', $result['lon']);
                
                                                    Notification::make()
                                                        ->title('Geocoded successfully')
                                                        ->success()
                                                        ->send();
                                                } else {
                                                    Notification::make()
                                                        ->title('Could not find coordinates for this address')
                                                        ->danger()
                                                        ->send();
                                                }
                                            })
                                    ),
                                Forms\Components\TextInput::make('lat')
                                    ->numeric(),
                                Forms\Components\TextInput::make('lng')
                                    ->numeric(),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('website')
                                    ->url()
                                    ->maxLength(255),
                            ])->columns(2),

                        Forms\Components\Section::make('Media')
                            ->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('cover')
                                    ->collection('cover')
                                    ->image()
                                    ->maxSize(2048)
                                    ->columnSpanFull(),
                                Forms\Components\SpatieMediaLibraryFileUpload::make('gallery')
                                    ->collection('gallery')
                                    ->image()
                                    ->multiple()
                                    ->maxSize(2048)
                                    ->panelLayout('grid')
                                    ->columnSpanFull(),
                            ])->collapsible(),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status & Ownership')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'unclaimed' => 'Unclaimed',
                                        'claimed' => 'Claimed',
                                        'published' => 'Published',
                                        'pending_review' => 'Pending Review',
                                        'rejected' => 'Rejected',
                                    ])
                                    ->required()
                                    ->default('pending_review'),
                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Featured Business')
                                    ->default(false),
                                Forms\Components\Select::make('owner_id')
                                    ->relationship('owner', 'name')
                                    ->label('Owner')
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),
                            ]),
                            
                        Forms\Components\Section::make('Categorization')
                            ->schema([
                                Forms\Components\Select::make('categories')
                                    ->relationship('categories', 'name')
                                    ->multiple()
                                    ->preload(),
                                Forms\Components\Select::make('neighborhoods')
                                    ->relationship('neighborhoods', 'name')
                                    ->multiple()
                                    ->preload(),
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
                Tables\Columns\SpatieMediaLibraryImageColumn::make('cover')
                    ->collection('cover')
                    ->circular()
                    ->label('')
                    ->defaultImageUrl('https://ui-avatars.com/api/?name=B&color=7F9CF5&background=EBF4FF'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->weight('bold')
                    ->limit(40),
                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Submitted By')
                    ->default('Guest')
                    ->badge()
                    ->color(fn ($record) => $record->owner_id ? 'info' : 'gray'),
                Tables\Columns\ToggleColumn::make('is_featured')
                    ->label('Featured')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'unclaimed' => 'gray',
                        'claimed', 'published' => 'success',
                        'pending_review' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'unclaimed' => 'Unclaimed',
                        'claimed' => 'Claimed / Published',
                        'pending_review' => 'Pending Review',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Only'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Business $record) => $record->status === 'pending_review')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Business')
                    ->modalDescription(fn (Business $record) => $record->owner_id 
                        ? "Warning: Approving this business will make it public AND grant full ownership/editing rights to {$record->owner->name}. Are you sure you want to proceed?" 
                        : "Approving this business will make it public as an unclaimed listing. Are you sure you want to proceed?")
                    ->action(function (Business $record) {
                        // Support 'published' if that's the new standard, or fallback to claimed
                        $record->update([
                            'status' => 'published' // Assuming 'published' is the modern state based on earlier edits
                        ]);
                        Notification::make()
                            ->title('Business approved')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolists\Infolist $infolist): Infolists\Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Basic Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'unclaimed' => 'gray',
                                'claimed', 'published' => 'success',
                                'pending_review' => 'warning',
                                'rejected' => 'danger',
                                default => 'gray',
                            }),
                        Infolists\Components\IconEntry::make('is_featured')
                            ->boolean()
                            ->label('Featured'),
                        Infolists\Components\TextEntry::make('description')
                            ->columnSpanFull(),
                    ])->columns(3),

                Infolists\Components\Section::make('Location & Contact')
                    ->schema([
                        Infolists\Components\TextEntry::make('address')->columnSpanFull(),
                        Infolists\Components\TextEntry::make('phone'),
                        Infolists\Components\TextEntry::make('website')
                            ->url(fn ($record) => $record->website)
                            ->openUrlInNewTab(),
                    ])->columns(2),

                Infolists\Components\Section::make('Ownership & Categorization')
                    ->schema([
                        Infolists\Components\TextEntry::make('owner.name')
                            ->label('Owner')
                            ->default('None / Unclaimed'),
                        Infolists\Components\TextEntry::make('categories.name')
                            ->badge(),
                        Infolists\Components\TextEntry::make('neighborhoods.name')
                            ->badge(),
                    ])->columns(3),

                Infolists\Components\Section::make('Media')
                    ->schema([
                        Infolists\Components\SpatieMediaLibraryImageEntry::make('cover')
                            ->collection('cover'),
                        Infolists\Components\SpatieMediaLibraryImageEntry::make('gallery')
                            ->collection('gallery')
                            ->circular(),
                    ])->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            BusinessResource\RelationManagers\ReviewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBusinesses::route('/'),
            'create' => Pages\CreateBusiness::route('/create'),
            'view' => Pages\ViewBusiness::route('/{record}'),
            'edit' => Pages\EditBusiness::route('/{record}/edit'),
        ];
    }
}
