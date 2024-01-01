<?php

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Filament\Resources\CompanyResource;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $country=Country::query()->where("id",$data['country_id'])->first();
        $state=State::query()->where("id",$data['state_id'])->first();
        $city=City::query()->where("id",$data['city_id'])->first();
        $data['country'] = $country->name;
        $data['state'] = $state->name;
        $data['city'] = $city->name;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
