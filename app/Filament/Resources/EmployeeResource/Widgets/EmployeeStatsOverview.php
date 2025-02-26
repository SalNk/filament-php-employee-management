<?php

namespace App\Filament\Resources\EmployeeResource\Widgets;

use App\Models\Country;
use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class EmployeeStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $rdc = Country::where('country_code', '243')->withCount('employees')->first();
        return [
            Stat::make('All employees', Employee::count()),
            // ->description("Here's a total employees")
            // ->descriptionIcon('heroicon-o-rectangle-stack')
            // ->color('success'),
            // Stat::make($rdc->name . ' Employees', $rdc->employees_count),
            Stat::make('Unique views', '191,6k'),
        ];
    }
}
