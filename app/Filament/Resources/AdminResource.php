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
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;
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
    protected static ?string $navigationGroup = 'Admin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Admin Personal Information')
                    ->description('Fill All The Information About Admin')
                    ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('email')->required(),
                        TextInput::make('password')->required(),
                        TextInput::make('phone')->required(),
                    ])->columns(4),

                Forms\Components\Section::make('Admin Security Information')
                    ->description('Fill All The Information About Admin')
                    ->schema([
                        Select::make('admin_role_id')->label('Role')
                            ->options(AdminRole::all()->pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                        FileUpload::make('image')
                            ->label('Upload Admin Profile')
                            ->required()
                            ->columns(1)
                            ->directory('admin-image')
                            ->storeFileNamesIn('original-file')
                    ])->columns(3),
            ])->columns(4);
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
            'view' => AdminResource\Pages\ViewAdmin::route('/{record}'),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        Split::make([
                            Grid::make(2)
                                ->schema([
                                    Group::make([
                                        TextEntry::make('title'),
                                        TextEntry::make('slug'),
                                        TextEntry::make('published_at')
                                            ->badge()
                                            ->date()
                                            ->color('success'),
                                    ]),
                                    Group::make([
                                       TextEntry::make('author.name'),
                                        TextEntry::make('category.name'),
//                                        SpatieTagsEntry::make('tags'),
                                    ]),
                                ]),
                            ImageEntry::make('image')
                                ->hiddenLabel()
                                ->grow(false),
                        ])->from('lg'),
                    ]),
                Forms\Components\Section::make('Content')
                    ->schema([
                        TextEntry::make('content')
                            ->prose()
                            ->markdown()
                            ->hiddenLabel(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            AdminResource\Pages\ViewAdmin::class,
            AdminResource\Pages\EditAdmin::class
        ]);
    }
}
