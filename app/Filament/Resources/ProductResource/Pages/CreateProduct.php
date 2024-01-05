<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = Str::slug($data['name']??"");
        $data['updated_by'] = Filament::auth()->user()->id;
        $data['created_by'] = Filament::auth()->user()->id;
        $data['category_ids'] = json_encode($data['category_ids']);
        $data['images'] = json_encode($data['images']);
        $data['date_on_sale_to'] = $data['images']??"";
        $data['date_on_sale_from'] = $data['images']??'';
//        dump($data);
        return $data;
    }
}
