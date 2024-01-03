<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Imports\CategoryImporter;
use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->importer(CategoryImporter::class)
                ->color(Color::Green)
            ->label('Import CSV ')
            ,
            Actions\CreateAction::make(),
        ];
    }
}