<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Attendance;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Filament\Resources\AttendanceResource\Widgets\AttendanceStatsOverview;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('employee_id')
                            ->label('Employé')
                            ->relationship('employee', 'full_name')
                            ->searchable()
                            ->required(),

                        DatePicker::make('date')
                            ->label('Date de présence')
                            ->required(),

                        TimePicker::make('check_in')
                            ->label('Heure d’arrivée')
                            ->nullable(),

                        TimePicker::make('check_out')
                            ->label('Heure de départ')
                            ->nullable(),

                        Select::make('status')
                            ->label('Statut')
                            ->options([
                                'present' => 'Présent',
                                'absent' => 'Absent',
                                'late' => 'En retard',
                            ])
                            ->default('present')
                            ->required(),

                        Textarea::make('notes')
                            ->label('Remarques')
                            ->nullable(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([10, 30, 50, 100, 'all'])
            ->defaultPaginationPageOption(30)
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label('Employé')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->date('D d M Y')
                    ->sortable(),

                TextColumn::make('check_in')
                    ->label('Arrivée')
                    ->time(),

                TextColumn::make('check_out')
                    ->label('Départ')
                    ->time(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'present' => 'success',
                        'absent' => 'danger',
                        'late' => 'warning',
                    })
                    ->formatStateUsing(function ($state) {
                        $translations = [
                            'present' => 'Présent',
                            'absent' => 'Absent',
                            'late' => 'Retard',
                        ];

                        return $translations[$state] ?? $state;
                    }),

                TextColumn::make('notes')
                    ->label('Remarques')
                    ->limit(30)
                    ->tooltip(fn($record) => $record->notes),
            ])
            ->filters([
                Filter::make('today')
                    ->label('Aujourd\'hui')
                    ->query(fn($query) => $query->whereDate('date', today()))
                    ->default(), // Appliquer ce filtre par défaut

                Filter::make('date_range')
                    ->label('Plage de dates')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')->label('Date de début'),
                        Forms\Components\DatePicker::make('end_date')->label('Date de fin'),
                    ])
                    ->query(
                        fn($query, $data) => $query
                            ->when($data['start_date'], fn($q) => $q->whereDate('date', '>=', $data['start_date']))
                            ->when($data['end_date'], fn($q) => $q->whereDate('date', '<=', $data['end_date']))
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            AttendanceStatsOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
