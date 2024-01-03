<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Filament\Resources\CountryResource\RelationManagers\CitiesRelationManager;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\Actions\Action;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationGroup ='Brand & Company';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('logo')
                    ->columns(1)
                    ->label('Company Logo')
                    ->directory('company-image')
                    ->storeFileNamesIn('original-file')
                    ->avatar(),
                Forms\Components\Section::make('Company Information')
                    ->description('Please Fill All The Company Information Below')
                    ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('email')->required()->email(),
                        TextInput::make('phone'),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->cols(100),

                    ])->columns(3),
                Forms\Components\Section::make('Address Information')
                    ->description('Please Fill All The Company Address Information Below')
                    ->schema([
                        Forms\Components\Select::make('country_id')
                            ->label('Country')
                            ->options(Country::all()->pluck('name', 'id'))
                            ->searchable()

                            ->preload()
                            ->live()
                            ->afterStateUpdated( static function(Forms\Set $set){
                                $set("state_id",null);
                                $set("city_id",null);
                            })
                            ->required(),
                        Forms\Components\Select::make('state_id')
                            ->label('State')
                            ->options(fn(Forms\Get $get): Collection=>State::query()->where('country_id',$get("country_id"))->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(static fn(Forms\Set $set)=>$set("city_id",null))
                            ->required(),
                        Forms\Components\Select::make('city_id')
                            ->label('State')
                            ->options(fn(Forms\Get $get): Collection=>City::query()->where('state_id',$get("state_id"))->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        Forms\Components\Textarea::make('address')
                        ->label('Full Address')
                            ->rows(3)
                            ->cols(100) ,
                    ])->columns(3),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')->circular()->tooltip('Company Logo'),
                Tables\Columns\TextColumn::make('name')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('phone')->searchable(),
                Tables\Columns\TextColumn::make('address')->searchable(),
                Tables\Columns\TextColumn::make('city')->searchable(),
                Tables\Columns\TextColumn::make('country')->searchable()->badge()->color('success'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\ToggleColumn::make('status'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])->tooltip('Actions'),
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
            RelationManagers\BrandsRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Name' => $record->name,
            "Country" => $record->country
        ];
    }

    /**
     * @throws \Exception
     */
    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('edit')
                ->iconButton()
                ->icon('heroicon-s-pencil')
                ->url(static::getUrl('edit', ['record' => $record])),
            Action::make('index')
                ->iconButton()
                ->icon('heroicon-s-eye')
                ->url(static::getUrl('index', ['record' => $record])),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function canView(Model $record): bool
    {
        return parent::canView($record); // TODO: Change the autogenerated stub
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                ImageEntry::make('logo')->circular()->alignCenter()->alignJustify(),
                Section::make('Company Information')
                    ->description('The Information About Company')
                    ->schema([

                        TextEntry::make('name'),
                        TextEntry::make('email'),
                        TextEntry::make('phone'),
                    ])->columns(3),
                Section::make('Company Address')
                    ->description('The Information About Company Address')
                    ->schema([
                        TextEntry::make('city')->badge(),
                        TextEntry::make('state')->badge(),
                        TextEntry::make('country')->badge() ,

                    ])->columns(3),
            ]);
    }
}
