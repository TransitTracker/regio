<?php

namespace App\Filament\Resources\Helper\OdonymResource\Pages;

use App\Filament\Imports\Helper\OdonymImporter;
use App\Filament\Resources\Helper\OdonymResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageOdonyms extends ManageRecords
{
    protected static string $resource = OdonymResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->importer(OdonymImporter::class)
                ->chunkSize(250),
            Actions\CreateAction::make(),
        ];
    }
}
