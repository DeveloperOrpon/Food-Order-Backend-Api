<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateBrand extends CreateRecord
{
    protected static string $resource = BrandResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = Str::slug($data['brand_name']??"");
        $data['updated_by'] = Filament::auth()->user()->id;
        $data['created_by'] = Filament::auth()->user()->id;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
