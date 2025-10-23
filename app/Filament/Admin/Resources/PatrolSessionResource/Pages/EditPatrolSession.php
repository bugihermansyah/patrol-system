<?php

namespace App\Filament\Admin\Resources\PatrolSessionResource\Pages;

use App\Filament\Admin\Resources\PatrolSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPatrolSession extends EditRecord
{
    protected static string $resource = PatrolSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
