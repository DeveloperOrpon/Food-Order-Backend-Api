<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Company;
use App\Models\Country;
use App\Models\Product;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Scalar;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup ='Product';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('English')
                            ->schema([
                                Forms\Components\Section::make('Product Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')->label('Product Title')->required(),
                                        Forms\Components\RichEditor::make('short_description')->label('Description')
                                    ])->icon('heroicon-s-paint-brush'),
                                Forms\Components\Section::make('General Setup')
                                    ->schema([
                                        Forms\Components\Select::make('company_id')
                                            ->label('Company')
                                            ->options(Company::all()->pluck('name', 'id'))
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->required(),
                                        Forms\Components\Select::make('company_id')
                                            ->label('Brand')
                                            ->options(Brand::all()->pluck('brand_name', 'id'))
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->required(),
                                        Forms\Components\Select::make('category-id')
                                            ->label('Category')
                                            ->options(Category::all()->pluck('name', 'id'))
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->required(),
                                        Forms\Components\Select::make('sub-category')
                                            ->label('Sub-Category')
                                            ->options(Category::all()->pluck('name', 'id'))
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->required(),
                                        Forms\Components\Select::make('type')
                                            ->label('Product Type')
                                            ->options(['Manage','Un-Manage'])
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->required(),
                                        Forms\Components\Select::make('units')
                                            ->label('Unit')
                                            ->options(Unit::all()->map(fn($unit) => [
                                                'id' => $unit->id,
                                                'name' => $unit->uniteValue->name . ' (' . $unit->value . ')',
                                            ])->pluck('name', 'id'))
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->required()
                                            ->columns(2),
                                    ])->icon('heroicon-s-wrench-screwdriver')->columns(4),

                                ///price
                                Forms\Components\Section::make('Pricing & others')
                                    ->schema(
                                        [
                                            Forms\Components\TextInput::make('unit_price'),
                                            Forms\Components\TextInput::make('purchase_price'),
                                            Forms\Components\TextInput::make('regular_price'),
                                            Forms\Components\TextInput::make('offer_price'),
                                            Forms\Components\TextInput::make('tax'),
                                            Forms\Components\Select::make('Discount Type')
                                                ->label('Discount Type')
                                                ->options(['Flat','Percent'])
                                                ->searchable()
                                                ->preload()
                                                ->live()
                                                ->required(),
                                            Forms\Components\TextInput::make('Discount'),
                                        ]
                                    )->columns(4)->icon('heroicon-s-currency-dollar'),

                                ///product variant
                                Forms\Components\Section::make('Product Variation Setup')
                                    ->schema([
                                        ColorPicker::make('color'),
                                        Forms\Components\Select::make('Select Attributes')
                                            ->label('Select Attributes')
                                            ->options(Category::all()->pluck('name', 'id'))
                                            ->searchable()
                                            ->multiple()
                                            ->preload()
                                            ->live()
                                            ->required(),
                                    ])->columns(3),
                                ///
                                Fieldset::make('Flash Deal')
                                    ->schema([
                                        DateTimePicker::make('date_on_sale_to'),
                                        DateTimePicker::make('date_on_sale_from')
                                    ]),
                               Forms\Components\Section::make('Product Image')
                                ->schema([
                                    Forms\Components\FileUpload::make('thumbnail'),
                                    FileUpload::make('images')
                                        ->multiple()
                                        ->storeFileNamesIn('attachment_file_names')

                                ])->columns(2)
                            ]),
                        Tabs\Tab::make('Bangla')
                            ->schema([
                                // ...
                            ]),
//                        Tabs\Tab::make('Tab 3')
//                            ->schema([
//                                // ...
//                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
