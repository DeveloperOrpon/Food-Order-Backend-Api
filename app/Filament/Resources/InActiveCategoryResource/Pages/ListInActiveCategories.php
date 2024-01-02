<?php

namespace App\Filament\Resources\InActiveCategoryResource\Pages;

use App\Filament\Resources\InActiveCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInActiveCategories extends ListRecords
{
    protected static string $resource = InActiveCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
//            Actions\ViewAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [

            'InActive' => ListRecords\Tab::make()->query(fn ($query) => $query->where('status', '0')),
            'Active' => ListRecords\Tab::make()->query(fn ($query) => $query->where('status', '1')),
            null => ListRecords\Tab::make('All'),
        ];
    }
}
