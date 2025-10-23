<?php

namespace App\Filament\Admin\Resources\ShiftSessionResource\Pages;

use App\Filament\Admin\Resources\ShiftSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewShiftSession extends ViewRecord
{
    protected static string $resource = ShiftSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
