<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieTagsInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Content')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                        if (($get('slug') ?? '') !== Str::slug($old)) {
                                            return;
                                        }
                                        $set('slug', Str::slug($state));
                                    }),
                                Forms\Components\Textarea::make('dek')
                                    ->label('Subtitle / Summary')
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                Forms\Components\RichEditor::make('body')
                                    ->columnSpanFull()
                                    ->toolbarButtons([
                                        'attachFiles',
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'codeBlock',
                                        'h2',
                                        'h3',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'undo',
                                    ]),
                            ]),
                            
                        Forms\Components\Section::make('Media')
                            ->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('featured_image')
                                    ->collection('featured_image')
                                    ->customProperties(fn (Get $get) => ['alt' => $get('featured_image_alt')])
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('featured_image_alt')
                                    ->label('Featured Image Alt Text')
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                            ]),
                            
                        Forms\Components\Section::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Article::class, 'slug', ignoreRecord: true),
                                Forms\Components\TextInput::make('meta_title')
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('meta_description')
                                    ->maxLength(255),
                            ])
                            ->collapsed(),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Publishing')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'in_review' => 'In Review',
                                        'scheduled' => 'Scheduled',
                                        'published' => 'Publish',
                                    ])
                                    ->required()
                                    ->default('draft')
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                        if ($state === 'published' && empty($get('published_at'))) {
                                            $set('published_at', now()->toDateTimeString());
                                        } elseif (in_array($state, ['draft', 'in_review'])) {
                                            $set('published_at', null);
                                        }
                                    }),
                                Forms\Components\DateTimePicker::make('published_at')
                                    ->required(fn (Get $get) => $get('status') === 'scheduled')
                                    ->visible(fn (Get $get) => $get('status') === 'scheduled'),
                                Forms\Components\Placeholder::make('published_at_static')
                                    ->label('Published at')
                                    ->content('Will be published immediately when saved')
                                    ->visible(fn (Get $get) => $get('status') === 'published'),
                                Forms\Components\Toggle::make('post_to_facebook')
                                    ->label('Post to Facebook Page')
                                    ->default(false)
                                    ->helperText('If checked, this article will be posted to the official Facebook page when published.'),
                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Featured Hero Article')
                                    ->helperText('If enabled, this article may be prioritized in the homepage hero section.'),
                                Forms\Components\Select::make('author_id')
                                    ->label('Author')
                                    ->relationship('author', 'name', function (\Illuminate\Database\Eloquent\Builder $query) {
                                        if (auth()->user()->hasRole(['Admin', 'Editor'])) {
                                            return $query->role(['Admin', 'Editor', 'Author']); // Only show staff, not business owners
                                        }
                                        return $query->where('id', auth()->id());
                                    })
                                    ->default(fn() => auth()->id())
                                    ->required(),
                                Forms\Components\Select::make('categories')
                                    ->relationship('categories', 'name')
                                    ->multiple()
                                    ->preload(),
                                SpatieTagsInput::make('tags')
                                    ->label('Tags (e.g. blog, news, politics)'),
                                Forms\Components\Select::make('neighborhoods')
                                    ->relationship('neighborhoods', 'name')
                                    ->multiple()
                                    ->preload(),
                            ]),
                            
                        Forms\Components\Section::make('Mentions')
                            ->description('Tag local businesses mentioned in this article.')
                            ->schema([
                                Forms\Components\Select::make('mentionedBusinesses')
                                    ->label('Businesses')
                                    ->relationship('mentionedBusinesses', 'name')
                                    ->multiple()
                                    ->searchable()
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
                Tables\Columns\SpatieMediaLibraryImageColumn::make('featured_image')
                    ->collection('featured_image')
                    ->circular()
                    ->label(''),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->weight('bold')
                    ->limit(50),
                Tables\Columns\TextColumn::make('author.name')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'in_review' => 'warning',
                        'scheduled' => 'info',
                        'published' => 'success',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_featured')
                    ->label('Featured')
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'in_review' => 'In Review',
                        'scheduled' => 'Scheduled',
                        'published' => 'Published',
                    ]),
                Tables\Filters\SelectFilter::make('author_id')
                    ->relationship('author', 'name')
                    ->label('Author'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Only'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('Author') && ! auth()->user()->hasRole(['Admin', 'Editor'])) {
            $query->where('author_id', auth()->id());
        }

        return $query;
    }
}
