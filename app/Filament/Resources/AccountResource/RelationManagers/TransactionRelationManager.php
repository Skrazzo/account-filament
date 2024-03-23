<?php

namespace App\Filament\Resources\AccountResource\RelationManagers;

use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TransactionRelationManager extends RelationManager
{
    protected static string $relationship = 'transaction';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('value')
                    ->placeholder('5')
                    ->required()
                    ->numeric()
                    ->suffix('EUR'),
                Radio::make('spent')
                    ->label('Did you spend this money?')
                    ->boolean()
                    ->inline()
                    ->inlineLabel(false)
                    ->default(true)
                    ->required(),
                TextInput::make('name')
                    ->placeholder('Bought two picas')
                    ->maxLength(30),
                DatePicker::make('happened_at')
                    ->format('Y-m-d H:i:s')
                    ->default(now())
                    ->required()
                    ->native(false),
                

            ]);
    }

    public function table(Table $table): Table
    {
        return $table->modifyQueryUsing(fn (Builder $query) => $query->orderBy('happened_at', 'desc'))
            ->recordTitleAttribute('value')
            ->columns([ 
                
                TextColumn::make('name')
                    ->placeholder('No name')
                    ->searchable(),
                TextColumn::make('value')
                    ->weight('bold')
                    ->color(fn (Model $record) => ($record->value <= 0) ? 'danger' : 'success')
                    ->sortable()
                    ->summarize(Sum::make()->money('EUR')),
                TextColumn::make('happened_at')->date('Y-m-d'),
                
            ])
            ->filters([
                Filter::make('happens_at')
                    ->form([
                        DatePicker::make('created_from')->label('From'),
                        DatePicker::make('created_until')->label('Till'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('happened_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('happened_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        
                        if ($data['created_from'] ?? null) {
                            $indicators[] = Indicator::make('From ' . Carbon::parse($data['created_from'])->toFormattedDateString())
                                ->removeField('created_from');
                        }
                
                        if ($data['created_until'] ?? null) {
                            $indicators[] = Indicator::make('Till ' . Carbon::parse($data['created_until'])->toFormattedDateString())
                                ->removeField('created_until');
                        }
                
                        return $indicators;
                    }),
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
