<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitTypeResource\Pages;
use App\Models\UnitValue;
use Closure;
use Exception;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class UnitTypeResource extends Resource
{
    protected static ?string $model = UnitValue::class;

    protected static ?string $navigationParentItem = 'Unit';

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup ='Business settings';
    protected static ?string $label ='Unit Type';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()
                    ->live()
                    ->live(onBlur: true)

                    ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null)
                    ->rule(static function (Forms\Get $get, Forms\Components\Component $component): \Closure {
                        return static function (string $attribute, $value, Closure $fail) use ($get, $component) {
                            $existingCategory = UnitValue::where('slug', Str::slug($value))
                                ->first();
                            if ($existingCategory && $existingCategory->getKey() !== $component->getRecord()?->getKey()) {
                                $type = ucwords($get('type'));
                                $fail("The {$type} Unit Value \"{$value}\" already exists.");
                            }
                        };
                    })
                ,
                Forms\Components\TextInput::make('slug')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->live()
                    ->hidden(fn(string $operation):bool =>$operation==='edit')
                    ->maxLength(255)
                    ->unique(table: UnitValue::class,column: 'slug')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('slug'),
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
            'index' => Pages\ListUnitTypes::route('/'),
            'create' => Pages\CreateUnitType::route('/create'),
            'edit' => Pages\EditUnitType::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
