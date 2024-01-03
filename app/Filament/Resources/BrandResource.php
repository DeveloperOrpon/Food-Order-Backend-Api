<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource\RelationManagers;
use App\Models\Brand;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-fire';
    protected static ?string $navigationGroup = 'Brand & Company';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('brand_logo')
                    ->disk('public')
                    ->directory('brand')
                    ->visibility('public')
                    ->maxSize(512)
                    ->enableDownload()
                    ->avatar()
                    ->required()
                    ->preserveFilenames(),
                Forms\Components\Section::make('Brand Information')
                    ->description('Input All The Information About Brand')
                    ->schema([
                        TextInput::make('brand_name')->required(),
                        TextInput::make('brand_email')->required()->email(),
                        Select::make('company_id')->label('Company')->required()
                            ->options(Company::all()->pluck('name', 'id'))
                            ->searchable(),
                    ])->columns(3),
                Forms\Components\MarkdownEditor::make('description')->columnSpanFull()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('brand_logo')->defaultImageUrl(asset('images/logo.png'))->label('Logo')->circular(),
                Tables\Columns\TextColumn::make('brand_name')->searchable(),
                Tables\Columns\TextColumn::make('brand_email')->searchable(),
                Tables\Columns\TextColumn::make('company.name')->searchable()->badge(),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('created_at')->date()->label('Create Time')->badge()->color(Color::Cyan),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
