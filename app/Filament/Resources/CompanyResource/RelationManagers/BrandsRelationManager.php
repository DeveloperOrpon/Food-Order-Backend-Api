<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BrandsRelationManager extends RelationManager
{
    protected static string $relationship = 'brands';

    protected static ?string $recordTitleAttribute = 'company_id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('brand_name')->required(),
                TextInput::make('brand_email'),
                Select::make('company_id')->label('Company')
                    ->options(Company::all()->pluck('name', 'id'))
                    ->searchable(),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('brand_name')->searchable(),
                Tables\Columns\TextColumn::make('brand_email')->searchable(),
                Tables\Columns\TextColumn::make('company.name')->searchable(),
                Tables\Columns\TextColumn::make('description'),
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
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
///dump
