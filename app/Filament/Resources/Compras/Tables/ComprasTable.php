<?php

namespace App\Filament\Resources\Compras\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ComprasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->withCount(['perfilCuentas as perfiles_vendidos' => function ($q) {
                    $q->where('estado', 'vendido');
                }]);
            })
            ->columns([
            
                TextColumn::make('cuenta.plataforma') 
                    ->label('Plataforma')
                    ->searchable() 
                    ->sortable(),
                TextColumn::make('cuenta.correo') 
                    ->label('Correo')
                    ->searchable() 
                    ->sortable(),
                
                TextColumn::make('fecha_compra')
                    ->date()
                    ->sortable(),

                TextColumn::make('perfiles_vendidos')
                    ->label('Vendidos')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('perfiles_disponibles')
                    ->label('Disponibles')
                    ->getStateUsing(fn ($record) => $record->pantallas - $record->perfiles_vendidos)
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),
                
                TextColumn::make('fecha_vencimiento')
                    ->date()
                    ->sortable(),
                TextColumn::make('estado')
                    ->label('Estado')
                    ->searchable()
                    // 1. Convierte la columna en un Badge estilizado
                    ->badge() 
                    // 2. Define qué color toma la etiqueta según el valor en la base de datos
                    ->color(fn (string $state): string => match ($state) {
                        'disponible', 'activa' => 'success', // Color Verde
                        'vendido', 'ocupado' => 'info',     // Color Azul
                        'mantenimiento' => 'warning',        // Color Amarillo / Naranja
                        'inactivo', 'vencido' => 'danger',   // Color Rojo
                        default => 'gray',                   // Color Gris por defecto
                    }),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                ->label('Ver'),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
