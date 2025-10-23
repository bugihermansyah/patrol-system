<?php

namespace App\Filament\Admin\Resources\PatrolSessionResource\Pages;

use App\Filament\Admin\Resources\PatrolSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPatrolSessions extends ListRecords
{
    protected static string $resource = PatrolSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
