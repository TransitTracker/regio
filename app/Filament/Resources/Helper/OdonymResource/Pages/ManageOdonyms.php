<?php

namespace App\Filament\Resources\Helper\OdonymResource\Pages;

use App\Filament\Imports\Helper\OdonymImporter;
use App\Filament\Resources\Helper\OdonymResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Str;

class ManageOdonyms extends ManageRecords
{
    protected static string $resource = OdonymResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->importer(OdonymImporter::class)
                ->chunkSize(250)
                ->modalDescription(str('Available from [Données Québec](https://www.donneesquebec.ca/recherche/dataset/odonymes-officiels)')->inlineMarkdown()->toHtmlString()),
            Actions\CreateAction::make(),
        ];
    }
}
