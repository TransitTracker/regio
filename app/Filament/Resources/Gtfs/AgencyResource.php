<?php

namespace App\Filament\Resources\Gtfs;

use App\Filament\Resources\Gtfs\AgencyResource\Pages;
use App\Filament\Resources\Gtfs\AgencyResource\RelationManagers;
use App\Models\Gtfs\Agency;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AgencyResource extends Resource
{
    protected static ?string $model = Agency::class;

    protected static ?string $navigationIcon = 'gmdi-other-houses';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('agency_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('agency_url')
                    ->required()
                    ->url()
                    ->maxLength(255),
                Forms\Components\Select::make('agency_timezone')
                    ->required()
                    ->options(timezone_identifiers_list())
                    ->searchable()
                    ->default('America/Toronto'),
                Forms\Components\TextInput::make('agency_lang')
                    ->default('fr-CA')
                    ->maxLength(255),
                Forms\Components\TextInput::make('agency_phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('agency_fare_url')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('agency_email')
                    ->email()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('agency_name'),
                Tables\Columns\TextColumn::make('agency_url'),
                Tables\Columns\TextColumn::make('agency_timezone'),
                Tables\Columns\TextColumn::make('agency_lang'),
                Tables\Columns\TextColumn::make('agency_phone'),
                Tables\Columns\TextColumn::make('agency_fare_url'),
                Tables\Columns\TextColumn::make('agency_email'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgencies::route('/'),
            'create' => Pages\CreateAgency::route('/create'),
            'edit' => Pages\EditAgency::route('/{record}/edit'),
        ];
    }
}
