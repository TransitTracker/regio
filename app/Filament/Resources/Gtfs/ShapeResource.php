<?php

namespace App\Filament\Resources\Gtfs;

use App\Filament\Resources\Gtfs\ShapeResource\Pages;
use App\Filament\Resources\Gtfs\ShapeResource\RelationManagers;
use App\Filament\Resources\Gtfs\ShapeResource\Widgets\ShapesMap;
use App\Models\Gtfs\Shape;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShapeResource extends Resource
{
    protected static ?string $model = Shape::class;

    protected static ?string $navigationIcon = 'gmdi-route';
    protected static ?string $navigationGroup = 'GTFS Creator';
    protected static ?string $navigationParentItem = 'Trips';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('shape_id'),
                Tables\Columns\TextColumn::make('agency.agency_name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageShapes::route('/'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            // ShapesMap::class,
        ];
    }
}
