<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Company;
use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?string $navigationIcon = 'heroicon-o-fire';
    protected static ?string $navigationGroup = 'Category & SubCategory';

    protected static ?string $navigationLabel = 'Categories';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Category Information')
                    ->description('Input Category Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required()
                            ->live()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                        Forms\Components\TextInput::make('description'),
                        Forms\Components\TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->live()
                            ->maxLength(255)
                            ->unique(Category::class, 'slug', ignoreRecord: true),
                    ])->columns(3),
                Forms\Components\Section::make('Category And Brand Information')
                    ->description("Category's Brand And Parent Categories Information")
                    ->schema([
                        Select::make('parent_id')->label('Select Parent Category')
                            ->options(Category::all()->pluck('name', 'id'))
                            ->searchable(),
                        Select::make('brand_id')->label('Select Brand')
                            ->options(Brand::all()->pluck('brand_name', 'id'))
                            ->searchable(),
                    ])->columns(2),
                Forms\Components\Section::make("Category Images")
                    ->description('Upload Category Images and Log')
                    ->schema([
                        FileUpload::make('logo')
                            ->columns(1)
                            ->directory('Category-image')
                            ->storeFileNamesIn('original-file'),

                        FileUpload::make('banner')
                            ->columns(1)
                            ->directory('category-image')
                            ->storeFileNamesIn('original-file')

                    ])->columns(2)


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('slug')->color('gray')->searchable(),
                BadgeColumn::make('parentCategory.name')->colors(['info'])  ->label('Parent Category'),
                BadgeColumn::make('brand.brand_name')->color('danger'),
                BadgeColumn::make('updateAdmin.name')  ->label('Updated By')
                    ->colors([
                        'success'
                    ]),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->date(),
                Tables\Columns\ToggleColumn::make('status')->label('Visibility'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])->tooltip('Action')
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success ';
    }
}
