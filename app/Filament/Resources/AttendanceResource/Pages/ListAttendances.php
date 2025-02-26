<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\AttendanceResource;
use App\Filament\Resources\AttendanceResource\Widgets\AttendanceStatsOverview;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


    public function getHeaderWidgets(): array
    {
        return [
            AttendanceStatsOverview::class
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Tous'),
            'Présent' => Tab::make()->query(fn($query) => $query->where('status', 'present')),
            'Absent' => Tab::make()->query(fn($query) => $query->where('status', 'absent')),
            'Retard' => Tab::make()->query(fn($query) => $query->where('status', 'late')),
        ];
    }
}
