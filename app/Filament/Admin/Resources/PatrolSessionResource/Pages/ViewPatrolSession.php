<?php

namespace App\Filament\Admin\Resources\PatrolSessionResource\Pages;

use App\Filament\Admin\Resources\PatrolSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPatrolSession extends ViewRecord
{
    protected static string $resource = PatrolSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
