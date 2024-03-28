<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Gtfs\Agency;
use Faker\Provider\Text;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Database\Eloquent\Model;

class RegisterAgency extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Create an Agency';
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(6)
            ->schema([
                FileUpload::make('logo')
                    ->avatar()
                    ->columnSpanFull()
                    ->directory('content/agencies'),
                TextInput::make('agency_name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(4),
                TextInput::make('slug')
                    ->maxLength(255)
                    ->required()
                    ->columnSpan(2),
                TextInput::make('agency_url')
                    ->required()
                    ->url()
                    ->maxLength(255)
                    ->columnSpan(3),
                Select::make('agency_timezone')
                    ->required()
                    ->options(timezone_identifiers_list())
                    ->searchable()
                    ->default('America/Toronto')
                    ->columnSpan(3),
                TextInput::make('agency_lang')
                    ->default('fr-CA')
                    ->maxLength(255)
                    ->columnSpan(3),
                TextInput::make('agency_phone')
                    ->tel()
                    ->mask('(999) 999-9999')
                    ->maxLength(255)
                    ->columnSpan(3),
                TextInput::make('agency_fare_url')
                    ->url()
                    ->maxLength(255)
                    ->columnSpan(3),
                TextInput::make('agency_email')
                    ->email()
                    ->maxLength(255)
                    ->columnSpan(3),
                ColorPicker::make('default_color')
                    ->afterStateHydrated(fn (ColorPicker $component, ?string $state) => $component->state("#{$state}"))
                    ->dehydrateStateUsing(fn (string $state): string => str($state)->remove('#'))
                    ->columnSpan(3),
                ColorPicker::make('default_text_color')
                    ->afterStateHydrated(fn (ColorPicker $component, ?string $state) => $component->state("#{$state}"))
                    ->dehydrateStateUsing(fn (string $state): string => str($state)->remove('#'))
                    ->columnSpan(3),
            ]);
    }

    protected function handleRegistration(array $data): Agency
    {
        $agency = Agency::create($data);

        $agency->users()->attach(auth()->user());

        return $agency;
    }
}
