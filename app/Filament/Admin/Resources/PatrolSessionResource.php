<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PatrolSessionResource\Pages;
use App\Filament\Admin\Resources\PatrolSessionResource\RelationManagers\PatrolCheckpointsRelationManager;
use App\Models\PatrolSession;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Infolists\Components\ViewEntry;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PatrolSessionResource extends Resource
{
    protected static ?string $model = PatrolSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'Working Sessions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('shift_session_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('open_flag')
                    ->numeric(),
                Forms\Components\DateTimePicker::make('start_at')
                    ->required(),
                Forms\Components\TextInput::make('start_lat')
                    ->numeric(),
                Forms\Components\TextInput::make('start_lon')
                    ->numeric(),
                Forms\Components\DateTimePicker::make('end_at'),
                Forms\Components\TextInput::make('end_lat')
                    ->numeric(),
                Forms\Components\TextInput::make('end_lon')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('shiftSession.shift.name')
                    ->sortable(),
                TextColumn::make('shiftSession.user.name'),
                TextColumn::make('status'),
                TextColumn::make('open_flag')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('start_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('start_lat')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('start_lon')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('end_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_lat')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('end_lon')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('shiftSession.user.name')
                    ->label('Guard')
                    ->weight(FontWeight::Bold),
                TextEntry::make('shiftSession.shift.name')
                    ->label('Shift')
                    ->weight(FontWeight::Bold),
                TextEntry::make('start_at')
                    ->label('Start Time')
                    ->dateTime(),
                TextEntry::make('end_at')
                    ->label('End Time')
                    ->dateTime(),
                TextEntry::make('status')
                    ->label('Status'),
                TextEntry::make('duration')
                    ->label('Duration')
                    ->state(function ($record) {
                        $start = \Carbon\Carbon::parse($record->start_at);
                        $end = $record->end_at ? \Carbon\Carbon::parse($record->end_at) : now();

                        $diffInMinutes = $start->diffInMinutes($end);
                        $hours = floor($diffInMinutes / 60);
                        $minutes = $diffInMinutes % 60;

                        $formatted = sprintf('%02d:%02d', $hours, $minutes);

                        return $record->end_at
                            ? $formatted
                            : $formatted . ' (Ongoing)';
                    })
                    ->color(fn($record) => $record->end_at ? '' : 'success'),
                Section::make('Patrol Route Map')
                    ->collapsible()
                    ->schema([
                        ViewEntry::make('map')
                            ->label('Patrol Path')
                            ->view('components.patrol-map')
                            ->viewData(fn($record) => [
                                'start' => [
                                    'lat' => $record->start_lat,
                                    'lon' => $record->start_lon,
                                    'time' => $record->start_at,
                                ],
                                'end' => [
                                    'lat' => $record->end_lat,
                                    'lon' => $record->end_lon,
                                    'time' => $record->end_at,
                                ],
                                'patrolCheckpoints' => $record->patrolCheckpoints()
                                    ->whereNotNull('lat')
                                    ->whereNotNull('lon')
                                    ->orderBy('scanned_at')
                                    ->get(['lat', 'lon', 'scanned_at']),
                            ]),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PatrolCheckpointsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatrolSessions::route('/'),
            'create' => Pages\CreatePatrolSession::route('/create'),
            'view' => Pages\ViewPatrolSession::route('/{record}'),
            'edit' => Pages\EditPatrolSession::route('/{record}/edit'),
        ];
    }
}
