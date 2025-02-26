<?php

namespace App\Filament\Resources\EmployeeResource\Widgets;

use App\Models\Country;
use App\Models\Employee;
use App\Models\Attendance;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class EmployeeStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total des employés', Employee::count())
                ->description("Nombre total d'employés enregistrés")
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),

            Stat::make('Présences aujourd\'hui', Attendance::whereDate('date', today())->count())
                ->description("Employés ayant pointé aujourd'hui")
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('info'),

            Stat::make('Absents aujourd\'hui', Attendance::whereDate('date', today())->where('status', 'absent')->count())
                ->description("Employés absents aujourd'hui")
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),

            Stat::make('Retards aujourd\'hui', Attendance::whereDate('date', today())->where('status', 'late')->count())
                ->description("Employés arrivés en retard aujourd'hui")
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Total des présences', Attendance::count())
                ->description("Nombre total d'enregistrements de présence")
                ->descriptionIcon('heroicon-o-clipboard-document-check')
                ->color('primary'),
        ];
    }
}
