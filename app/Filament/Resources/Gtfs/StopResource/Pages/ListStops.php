<?php

namespace App\Filament\Resources\Gtfs\StopResource\Pages;

use App\Filament\Imports\Gtfs\StopImporter;
use App\Filament\Resources\Gtfs\StopResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\Url;

class ListStops extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = StopResource::class;

    #[Url (as: 'municipality')]
    public string $field_stop_city = '';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Stops map')
                ->url(StopResource::getUrl('map')),
            ImportAction::make()
                ->importer(StopImporter::class),
            // Todo: handle automatically position and copy municipality as default value
            CreateAction::make()
                ->modal()
                ->extraAttributes(['id' => 'create-stop'])
                ->successRedirectUrl(StopResource::getUrl()),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
             StopResource\Widgets\StopsMap::class,
        ];
    }
}
