<?php

namespace App\Filament\Resources\Gtfs\TripResource\Pages;

use App\Enums\DropOffType;
use App\Enums\PickupType;
use App\Filament\Resources\Gtfs\TripResource;
use App\Models\Gtfs\Stop;
use App\Models\Gtfs\StopTime;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageStopTimes extends ManageRelatedRecords
{
    protected static string $resource = TripResource::class;

    protected static string $relationship = 'stopTimes';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Stop Times';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('stop_id')
                    ->relationship('stop', 'stop_name')
                    ->searchable(['stop_code', 'stop_id', 'stop_name', 'stop_desc'])
                    ->searchDebounce(250)
                    ->getOptionLabelFromRecordUsing(fn (Stop $record): string => "[{$record->stop_code}] {$record->stop_name}")
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TimePicker::make('arrival_time')
                    ->label('Arrival')
                    ->seconds(false)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Forms\Set $set, ?string $state, Forms\Get $get) => $set('departure_time', $state))
                    ->required(),
                Forms\Components\TimePicker::make('departure_time')
                    ->seconds(false)
                    ->label('Departure'),
                Forms\Components\ToggleButtons::make('pickup_type')
                    ->options(PickupType::class)
                    ->default(0)
                    ->required(),
                Forms\Components\ToggleButtons::make('drop_off_type')
                    ->options(DropOffType::class)
                    ->default(0)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('stop_id')
            ->columns([
                Tables\Columns\TextColumn::make('stop.stop_code'),
                Tables\Columns\TextColumn::make('stop.stop_name'),
                Tables\Columns\TextColumn::make('stop.stop_name'),
                Tables\Columns\TextInputColumn::make('arrival_time')
                    ->type('time'),
                Tables\Columns\TextInputColumn::make('departure_time')
                    ->type('time'),
                Tables\Columns\TextColumn::make('pickup_type'),
                Tables\Columns\TextColumn::make('drop_off_type'),

            ])
            ->reorderable('stop_sequence')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('changePickupDropoffType')
                        ->form([
                            Select::make('pickup_type')
                                ->options(PickupType::class),
                            Select::make('drop_off_type')
                                ->options(DropOffType::class),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(
                                fn (StopTime $record) => $record->update([
                                    'pickup_type' => $data['pickup_type'],
                                    'drop_off_type' => $data['drop_off_type'],
                                ]),
                            );
                        }),
                ]),
            ])
            ->paginated(false)
            ->striped();
    }
}
