<?php

namespace App\Filament\Pages;

use App\Exports;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Process;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as Formats;

class GenerateGtfs extends Page implements HasActions
{
    use InteractsWithActions;

    protected static ?string $navigationIcon = 'mdi-cloud-download';
    protected static ?string $navigationGroup = 'GTFS Creator';
    protected static ?int $navigationSort = 7;
    protected static string $view = 'filament.pages.generate-gtfs';

    public array $files = [
        [ 'exporter' => Exports\AgenciesExport::class, 'file' => 'agency.txt' ],
        [ 'exporter' => Exports\ServicesExport::class, 'file' => 'calendar.txt' ],
        [ 'exporter' => Exports\FrequenciesExport::class, 'file' => 'frequencies.txt' ],
        [ 'exporter' => Exports\RoutesExport::class, 'file' => 'routes.txt' ],
        [ 'exporter' => Exports\ShapesExport::class, 'file' => 'shapes.txt' ],
        [ 'exporter' => Exports\StopTimesExport::class, 'file' => 'stop_times.txt' ],
        [ 'exporter' => Exports\StopsExport::class, 'file' => 'stops.txt' ],
        [ 'exporter' => Exports\TimetablePagesExport::class, 'file' => 'timetable_pages.txt' ],
        [ 'exporter' => Exports\TimetablesExport::class, 'file' => 'timetables.txt' ],
        [ 'exporter' => Exports\TripsExport::class, 'file' => 'trips.txt' ],
    ];

    public ?string $currentlyGenerating = null;

    public function generateAction(): Action
    {
        return Action::make('generate')
            ->action(function () {
                foreach ($this->files as $file) {
                    $this->currentlyGenerating = $file['file'];
                    Excel::store(new $file['exporter'](), "regio/{$file['file']}", 'public', Formats::CSV);
                    $this->currentlyGenerating = null;
                }

                Notification::make()
                    ->title('GTFS files generated.')
                    ->success()
                    ->send();
            });
    }
}
