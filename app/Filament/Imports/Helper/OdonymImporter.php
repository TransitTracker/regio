<?php

namespace App\Filament\Imports\Helper;

use App\Models\Helper\Odonym;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Log;

class OdonymImporter extends Importer
{
    protected static ?string $model = Odonym::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('topo_id')
                ->requiredMapping()
                ->rules(['required', 'integer'])
                ->guess(['Identifiant']),
            ImportColumn::make('toponym')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->guess(['Toponyme']),
            ImportColumn::make('type')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->guess(['Type_entite']),
            ImportColumn::make('municipality')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->guess(['Municipalite']),
            ImportColumn::make('specific')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->guess(['Partie_specifique']),
            ImportColumn::make('generic')
                ->requiredMapping()
                ->rules(['max:255'])
                ->guess(['Partie_generique']),
        ];
    }

    public function resolveRecord(): ?Odonym
    {
        return Odonym::firstOrNew([
            'topo_id' => $this->data['Identifiant'] ?? $this->data['topo_id'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your odonym import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
