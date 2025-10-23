<?php

namespace App\Filament\Admin\Resources\ShiftSessionResource\Pages;

use App\Filament\Admin\Resources\ShiftSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShiftSession extends EditRecord
{
    protected static string $resource = ShiftSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
