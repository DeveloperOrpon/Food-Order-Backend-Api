<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminRoleResource\Pages;
use App\Filament\Resources\AdminRoleResource\RelationManagers;
use App\Models\AdminRole;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdminRoleResource extends Resource
{
    protected static ?string $model = AdminRole::class;


    protected static ?string $navigationIcon = 'heroicon-o-eye';

    protected static ?string $navigationGroup ='Admin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                TextInput::make('module_access'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#ID'),
                Tables\Columns\TextColumn::make('name')->label('Role Name'),
                Tables\Columns\TextColumn::make('module_access')->label('Module Access'),
                Tables\Columns\TextColumn::make('module_access')->label('Module Access'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAdminRoles::route('/'),
            'create' => Pages\CreateAdminRole::route('/create'),
            'edit' => Pages\EditAdminRole::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
