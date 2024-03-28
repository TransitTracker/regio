<?php

namespace App\Filament\Resources\Gtfs\StopResource\Pages;

use App\Filament\Resources\Gtfs\StopResource;
use App\Models\Gtfs\Stop;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Collection;

class StopsMap extends Page implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected static string $resource = StopResource::class;

    protected static string $view = 'filament.resources.gtfs.stop-resource.pages.stops-map';

    public Collection $stops;

    public function mount()
    {
        $this->stops = Stop::all(['stop_position', 'stop_code', 'stop_id', 'stop_name']);
    }

    public function createStop(): Action
    {
        return CreateAction::make();
//        return CreateAction::make()
//                ->model(Stop::class)
//                ->form([
//                    TextInput::make('stop_name'),
//                ]);
    }
}
