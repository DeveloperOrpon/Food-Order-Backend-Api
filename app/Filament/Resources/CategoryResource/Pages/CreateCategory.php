<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    public final function mutateFormDataBeforeCreate(array $data): array
    {
        $data['updated_by'] = Filament::auth()->user()->id;
        $data['created_by'] = Filament::auth()->user()->id;
        return $data;
    }

    public final function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
