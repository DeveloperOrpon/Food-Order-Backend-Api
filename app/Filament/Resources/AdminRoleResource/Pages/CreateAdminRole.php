<?php

namespace App\Filament\Resources\AdminRoleResource\Pages;

use App\Filament\Resources\AdminRoleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAdminRole extends CreateRecord
{
    protected static string $resource = AdminRoleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
