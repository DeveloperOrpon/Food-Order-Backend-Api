<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BrandsRelationManager extends RelationManager
{
    protected static string $relationship = 'brands';

    protected static ?string $recordTitleAttribute = 'company_id';


    public function form(Form $form): Form
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
