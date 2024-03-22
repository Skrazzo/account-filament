<?php

namespace App\Filament\Resources\AccountResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PHPUnit\Event\TestSuite\Sorted;

class TransactionRelationManager extends RelationManager
{
    protected static string $relationship = 'transaction';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('value')
                    ->required()
                    ->numeric(),
                Radio::make('spent')
                    ->label('Did you spend this money?')
                    ->boolean()
                    ->inline()
                    ->inlineLabel(false)
                    ->default(true)
                    ->required(),
                TextInput::make('name')
                    ->maxLength(30),
                DatePicker::make('happened_at')
                    ->default(now())
                    ->native(false),
                

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('value')
            ->columns([ 
                TextColumn::make('value')
                    ->weight('bold')
                    ->color(fn (Model $record) => ($record->spent) ? 'danger' : 'success')
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('happened_at')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
