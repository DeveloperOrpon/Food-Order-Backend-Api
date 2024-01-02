<?php

namespace App\Filament\Resources\InActiveCategoryResource\Pages;

use App\Filament\Resources\InActiveCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInActiveCategory extends EditRecord
{
    protected static string $resource = InActiveCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
