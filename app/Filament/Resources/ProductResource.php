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
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
                                    ->debounce()
                                    ->collapsible()
                                    ->schema([
                                        Forms\Components\TextInput::make('name')->label('Product Title')->required(),
                                        Forms\Components\RichEditor::make('details')->label('Description')
                                    ])->icon('heroicon-s-paint-brush'),
                                Forms\Components\Section::make('General Setup')
                                    ->collapsible()
                                    ->schema([
                                        Forms\Components\Select::make('company_id')
                                            ->label('Company')
                                            ->options(Company::all()->pluck('name', 'id'))
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->required(),
                                        Forms\Components\Select::make('brand_id')
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
                                            ->multiple()
                                            ->preload()
                                            ->live()
                                            ->required(),
                                        Forms\Components\Select::make('category_ids')
                                            ->label('Sub-Category')
                                            ->options(Category::all()->pluck('name', 'id'))
                                            ->searchable()
                                            ->preload()
                                            ->multiple()
                                            ->live(),
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
                                    ->collapsible()
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
                                    ->collapsible()
                                    ->icon('heroicon-s-square-3-stack-3d')
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
                                   ->collapsible()
                                   ->icon('heroicon-m-photo')
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
                Tables\Columns\ImageColumn::make('thumbnail')->circular(),
                Tables\Columns\TextColumn::make('name')->limit(20),
                Tables\Columns\TextColumn::make('regular_price')->money('usd')->alignCenter(),
                Tables\Columns\TextColumn::make('purchase_price')->money('usd')->alignCenter(),
                Tables\Columns\TextColumn::make('brand.brand_name')->badge()->color(Color::Orange),
                Tables\Columns\TextColumn::make('company.name')->badge()->color(Color::Orange),
                Tables\Columns\TextColumn::make('unit.description')->badge()->color(Color::Orange),
                Tables\Columns\ToggleColumn::make('status'),
            ])
            ->filters([
                QueryBuilder::make()
                    ->constraints([
                        TextConstraint::make('name'),
                        TextConstraint::make('slug'),
                        TextConstraint::make('sku')
                            ->label('SKU (Stock Keeping Unit)'),
                        TextConstraint::make('barcode')
                            ->label('Barcode (ISBN, UPC, GTIN, etc.)'),
                        TextConstraint::make('description'),
                        NumberConstraint::make('old_price')
                            ->label('Compare at price')
                            ->icon('heroicon-m-currency-dollar'),
                        NumberConstraint::make('price')
                            ->icon('heroicon-m-currency-dollar'),
                        NumberConstraint::make('cost')
                            ->label('Cost per item')
                            ->icon('heroicon-m-currency-dollar'),
                        NumberConstraint::make('qty')
                            ->label('Quantity'),
                        NumberConstraint::make('security_stock'),
                        BooleanConstraint::make('is_visible')
                            ->label('Visibility'),
                        BooleanConstraint::make('featured'),
                        BooleanConstraint::make('backorder'),
                        BooleanConstraint::make('requires_shipping')
                            ->icon('heroicon-m-truck'),
                        DateConstraint::make('published_at'),
                    ])
                    ->constraintPickerColumns(2),
            ], layout: Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->actions([

                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),

                ])->icon('heroicon-m-ellipsis-horizontal')
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

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'sku', 'brand.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var Product $record */

        return [
            'Brand' => optional($record->brand)->brand_name,
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['brand']);
    }

//    public static function getNavigationBadge(): ?string
//    {
//        return static::$model::whereColumn('current_stock', '<', 'security_stock')->count();
//    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
