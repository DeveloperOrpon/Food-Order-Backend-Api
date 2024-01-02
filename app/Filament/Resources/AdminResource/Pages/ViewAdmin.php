<?php

namespace App\Filament\Resources\AdminResource\Pages;

use App\Filament\Resources\AdminResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewAdmin extends ViewRecord
{
    protected static string $resource = AdminResource::class;

    public function getTitle(): string | Htmlable
    {
        return $this->record->title;
    }

    protected function getActions(): array
    {
        return [];
    }
}
