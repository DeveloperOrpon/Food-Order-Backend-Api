<?php

namespace App\Filament\Resources\StateResource\RelationManagers;

use App\Models\Country;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'cities';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
               Forms\Components\Section::make('City Information')
                ->description('Enter City Information')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('state_id')
                        ->label('State')
                        ->options(State::all()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->live()
                        ->required(),
                ])->columns(2)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name')->searchable(),
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
            ]);
    }
}
