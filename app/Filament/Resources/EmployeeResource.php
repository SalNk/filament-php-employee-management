<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Country;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EmployeeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Filament\Resources\EmployeeResource\Widgets\EmployeeStatsOverview;
use App\Models\City;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $label = "Employés";


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('full_name')
                    ->label('Nom complet')
                    ->required(),

                TextInput::make('address')
                    ->label('Adresse')
                    ->required(),

                TextInput::make('zip_code')
                    ->label('Code postal')
                    ->required()
                    ->maxLength(6),

                DatePicker::make('birth_day')
                    ->label('Date de naissance')
                    ->required(),

                DatePicker::make('date_hired')
                    ->label('Date d\'embauche')
                    ->required(),

                Select::make('country_id')
                    ->label('Pays')
                    ->required()
                    ->options(Country::all()->pluck('name', 'id')->toArray())
                    ->reactive()
                    ->afterStateUpdated(fn(callable $set) => $set('city_id', null)),

                Select::make('city_id')
                    ->label('Ville')
                    ->required()
                    ->options(function (callable $get) {
                        $country = Country::find($get('country_id'));
                        if (!$country) {
                            return City::all()->pluck('name', 'id');
                        }
                        return $country->cities->pluck('name', 'id');
                    })
                    ->reactive(),

                Select::make('department_id')
                    ->label('Département')
                    ->required()
                    ->relationship(name: 'department', titleAttribute: 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('full_name')
                    ->label('Nom complet')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('department.name')
                    ->label('Département')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('date_hired')
                    ->label('Date d\'embauche')
                    ->sortable()
                    ->date('D d M Y')
                    ->searchable(),

                TextColumn::make('country.name')
                    ->label('Pays')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('department')
                    ->relationship('department', 'name')
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
            EmployeeStatsOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
