<?php

namespace App\Filament\Resources\AdminRoleResource\Pages;

use App\Filament\Resources\AdminRoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminRole extends EditRecord
{
    protected static string $resource = AdminRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
