<?php
namespace App\Filament\Admin\Widgets;

use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use App\Models\ShiftSession;
use App\Models\PatrolSession;

class ShiftStatsOverviewWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $locationId = $this->filters['location_id'] ?? null;

        // Query shift_sessions (relasi: shift_sessions → shift → location)
        $shiftQuery = ShiftSession::whereNull('ended_at');

        if ($locationId) {
            $shiftQuery->whereHas('shift.location', function ($q) use ($locationId) {
                $q->where('id', $locationId);
            });
        }

        $activeShiftCount = $shiftQuery->count();

        // Query patrol_sessions (relasi: patrol_sessions → shift_session → shift → location)
        $patrolQuery = PatrolSession::where('status', 'active');

        if ($locationId) {
            $patrolQuery->whereHas('shiftSession.shift.location', function ($q) use ($locationId) {
                $q->where('id', $locationId);
            });
        }

        $activePatrolCount = $patrolQuery->count();

        return [
            Stat::make('Active Shift Sessions', $activeShiftCount)
                ->icon('heroicon-o-user')
                ->color('success')
                ->description('Currently active shifts'),

            Stat::make('Active Patrol Sessions', $activePatrolCount)
                ->icon('heroicon-o-newspaper')
                ->color('warning')
                ->description('Currently active patrols'),
        ];
    }
}
