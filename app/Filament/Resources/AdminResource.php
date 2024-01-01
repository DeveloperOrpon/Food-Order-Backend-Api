<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages;
use App\Filament\Resources\AdminResource\RelationManagers;
use App\Models\Admin;
use App\Models\AdminRole;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdminResource extends Resource
{
    protected static ?string $model = Admin::class;



    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static bool $hasTitleCaseModelLabel = true;
    protected static ?string $navigationGroup ='Admin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                TextInput::make('email'),
                TextInput::make('password'),
                TextInput::make('phone'),
                Select::make('admin_role_id')->label('Role')
                    ->options(AdminRole::all()->pluck('name', 'id'))
                    ->searchable(),
                FileUpload::make('image')
                    ->disk('public')
                    ->directory('admin')
                    ->visibility('public')
                    ->maxSize(512)
                    ->enableDownload()
                    ->enableOpen()
                    ->preserveFilenames(),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#ID'),
                ImageColumn::make('image')->disk('public')
                    ->visibility('public'),
                TextColumn::make('name')->label('Name')->searchable()->formatStateUsing(fn(string $state): string => ucwords($state)),
                TextColumn::make('email')->label('Email')->searchable()->formatStateUsing(fn(string $state): string => ucwords($state)),
                TextColumn::make('phone')->label('Phone'),
                TextColumn::make('role.name')->label('Role Name')
                    ->searchable()->formatStateUsing(fn(string $state): string => ucwords($state)),
                BadgeColumn::make("role.name")->colors([
                    'success'
                ])
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
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
