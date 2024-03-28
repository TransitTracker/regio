<?php

namespace App\Filament\Exports\Gtfs;

use App\Models\Gtfs\Calendar;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class CalendarExporter extends Exporter
{
    protected static ?string $model = Calendar::class;

    public function getFormats(): array
    {
        return [ ExportFormat::Csv ];
    }

    public static function getColumns(): array
    {
        // TODO: Delete or fix
        return [
            ExportColumn::make('service_id')
                ->label('service_id'),
            ExportColumn::make('monday')
                ->label('monday')
                ->state(fn (Calendar $record): string => $record->monday ? '1': '0'),
            ExportColumn::make('tuesday')
                ->label('tuesday')
                ->formatStateUsing(fn (bool $state): string => $state ? '1' : '0'),
            ExportColumn::make('wednesday')
                ->label('wednesday')
                ->formatStateUsing(fn (bool $state): string => $state ? '1' : '0'),
            ExportColumn::make('thursday')
                ->label('thursday')
                ->formatStateUsing(fn (bool $state): string => $state ? '1' : '0'),
            ExportColumn::make('friday')
                ->label('friday')
                ->formatStateUsing(fn (bool $state): string => $state ? '1' : '0'),
            ExportColumn::make('saturday')
                ->label('saturday')
                ->formatStateUsing(fn (bool $state): string => $state ? '1' : '0'),
            ExportColumn::make('sunday')
                ->label('sunday')
                ->formatStateUsing(fn (bool $state): string => $state ? '1' : '0'),
            ExportColumn::make('start_date')
                ->label('start_date'),
            ExportColumn::make('end_date')
                ->label('end_date'),
//            ExportColumn::make('agency.agency_id')
//                ->enabledByDefault(false),
//            ExportColumn::make('created_at')
//                ->enabledByDefault(false),
//            ExportColumn::make('updated_at')
//                ->enabledByDefault(false),
//            ExportColumn::make('service_description')
//                ->enabledByDefault(false),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your calendar export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
