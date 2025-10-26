<?php

namespace App\Filament\Pages;

use App\Models\Location;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section as ComponentsSection;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    use BaseDashboard\Concerns\HasFiltersForm;

    public function filtersForm(Form $schema): Form
    {
        return $schema
            ->schema([
                ComponentsSection::make()
                    ->schema([
                        Select::make('location_id')
                            ->label('Location')
                            ->options(Location::query()->pluck('name', 'id')->toArray())
                            ->searchable()
                            ->placeholder('Select a location'),
                    ])
                    ->columns(3),
            ]);
    }
}