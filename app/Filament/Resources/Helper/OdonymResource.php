<?php

namespace App\Filament\Resources\Helper;

use App\Filament\Resources\Helper\OdonymResource\Pages;
use App\Filament\Resources\Helper\OdonymResource\RelationManagers;
use App\Models\Helper\Odonym;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OdonymResource extends Resource
{
    protected static ?string $model = Odonym::class;

    protected static ?string $navigationIcon = 'gmdi-label';
    protected static ?string $navigationGroup = 'Helper';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('toponym')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('municipality')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('specific')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('generic')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('topo_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('toponym')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('municipality')
                    ->searchable(),
                Tables\Columns\TextColumn::make('specific')
                    ->searchable(),
                Tables\Columns\TextColumn::make('generic')
                    ->searchable(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageOdonyms::route('/'),
        ];
    }
}
