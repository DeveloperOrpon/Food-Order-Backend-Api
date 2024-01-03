<?php

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Filament\Resources\CompanyResource;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCompany extends EditRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),

        ];
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $country=Country::query()->where("name",$data['country'])->first();
        $state=State::query()->where("name",$data['state'])->first();
        $city=City::query()->where("name",$data['city'])->first();
        $data['country_id']=$country->id;
        $data['state_id']=$state->id;
        $data['city_id']=$city->id;
        return $data;
    }
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Company Information Updated!!')

            ;
    }

}
