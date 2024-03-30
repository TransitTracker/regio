<?php

namespace App\Filament\Imports\Gtfs;

use App\Models\Gtfs\Stop;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Arr;
use MatanYadaev\EloquentSpatial\Objects\Point;

class StopImporter extends Importer
{
    protected static ?string $model = Stop::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('stop_code')
                ->rules(['max:255']),
            ImportColumn::make('stop_name')
                ->rules(['max:255']),
            ImportColumn::make('stop_lat')
                ->requiredMapping()
                ->guess(['latitude'])
                ->rules(['required', 'decimal:4,15']),
            ImportColumn::make('stop_lon')
                ->requiredMapping()
                ->guess(['longitude'])
                ->rules(['required', 'decimal:4,15']),
            ImportColumn::make('weelchair_boarding')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('stop_desc')
                ->rules(['max:255']),
            ImportColumn::make('stop_position')
                ->requiredMapping()
                ->rules(['max:255']),
            ImportColumn::make('parent_station')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('location_type')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('platform_code')
                ->rules(['max:255']),
            ImportColumn::make('stop_city')
                ->rules(['max:255']),
        ];
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Hidden::make('agency_id')
                ->default(Filament::getTenant()->getKey()),
            \Filament\Forms\Components\Checkbox::make('ignoreSameStopCode')
                ->helperText('This will create duplicated stops with the same stop_code.'),
        ];
    }

    protected function beforeFill(): void
    {
        $this->data['stop_position'] = new Point($this->data['stop_lat'], $this->data['stop_lon']);
        unset($this->data['stop_lat']);
        unset($this->data['stop_lon']);
        info('beforeFill data', $this->data);
    }

    public function resolveRecord(): ?Stop
    {
        if ($this->options['ignoreSameStopCode']) {
            return new Stop(Arr::only($this->options, 'agency_id'));
        }

        $stop = Stop::firstOrNew(['stop_code' => $this->data['stop_code'], 'agency_id' => $this->options['agency_id']]);

        if (!$stop->wasRecentlyCreated) {
            throw new RowImportFailedException("Stop already exists [{$this->data['stop_code']}].");
        }

        return $stop;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your stop import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
