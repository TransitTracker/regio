<?php

namespace App\Filament\Resources\Gtfs;

use App\Enums\RouteType;
use App\Filament\Resources\Gtfs\RouteResource\Pages;
use App\Models\Gtfs\Agency;
use App\Models\Gtfs\Route;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class RouteResource extends Resource
{
    protected static ?string $model = Route::class;

    protected static ?string $navigationIcon = 'gmdi-alt-route';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('agency_id')
                    ->required()
                    ->relationship('agency', 'agency_name')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('route_short_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('route_long_name')
                    ->maxLength(255),
                Forms\Components\Select::make('route_type')
                    ->required()
                    ->options(RouteType::options()),
                Forms\Components\TextInput::make('route_url')
                    ->maxLength(255)
                    ->url(),
                Forms\Components\ColorPicker::make('route_color'),
                Forms\Components\ColorPicker::make('route_text_color'),
                Forms\Components\TextInput::make('route_sort_order')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('route_id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('agency.agency_name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ColorColumn::make('route_color')
                    ->copyable()
                    ->label(__('Color')),
                Tables\Columns\TextColumn::make('route_short_name')
                    ->sortable()
                    ->searchable()
                    ->label(__('Short name')),
                Tables\Columns\TextColumn::make('route_long_name')
                    ->sortable()
                    ->searchable()
                    ->label(__('Long name')),
                Tables\Columns\IconColumn::make('route_type')
                    ->icon(fn (int $state): string => RouteType::from($state)->icon())
                    ->sortable()
                    ->label(__('Type')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('agency')
                    ->options(
                        Agency::select(['agency_id', 'agency_name'])->get()->mapWithKeys(function (Agency $item) {
                            return [$item->agency_id => $item->agency_name];
                        })->all()
                    )
                    ->attribute('agency_id'),
                Tables\Filters\SelectFilter::make('route_type')
                    ->options(RouteType::options())
                    ->attribute('agency_id'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ReplicateAction::make(),
            ])
            ->defaultGroup('agency.agency_name')
            ->groups([
                'agency.agency_name',
                'route_type',
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
            'index' => Pages\ListRoutes::route('/'),
            'create' => Pages\CreateRoute::route('/create'),
            'edit' => Pages\EditRoute::route('/{record}/edit'),
        ];
    }
}
