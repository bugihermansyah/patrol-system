<?php

namespace App\Filament\Admin\Resources\ShiftSessionResource\Pages;

use App\Filament\Admin\Resources\ShiftSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShiftSessions extends ListRecords
{
    protected static string $resource = ShiftSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
