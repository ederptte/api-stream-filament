<?php

namespace App\Filament\Resources\Ventas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class VentasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cliente.nombre')
                    ->label('Cliente')
                    ->searchable() 
                    ->sortable(),
                TextColumn::make('perfilCuenta.compra.cuenta.plataforma')
                    ->label('Plataforma')
                    ->searchable() 
                    ->sortable(),
                TextColumn::make('perfilCuenta.nombre_perfil')
                    ->label('Perfil')
                    ->searchable() 
                    ->sortable(),
                TextColumn::make('perfilCuenta.pin')
                    ->label('Pin')
                    ->searchable() 
                    ->sortable(),
                TextColumn::make('precio_venta')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('fecha_venta')
                    ->date()
                    ->sortable(),
                TextColumn::make('fecha_vencimiento')
                    ->date()
                    ->sortable(),

                TextColumn::make('estado_vencimiento')
                    ->label('Estado')
                    ->getStateUsing(function ($record) {
                        if (!$record->fecha_vencimiento) {
                            return 'sin fecha';
                        }

                        $hoy = now()->startOfDay();
                        $vencimiento = $record->fecha_vencimiento->startOfDay();

                        if ($vencimiento->isPast()) {
                            return 'vencido';
                        }

                        if ($hoy->diffInDays($vencimiento, false) <= 3) {
                            return 'por_vencer';
                        }

                        return 'activo';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'activo' => 'success',
                        'por_vencer' => 'warning',
                        'vencido' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'activo' => 'Activo',
                        'por_vencer' => 'Por vencer',
                        'vencido' => 'Vencido',
                        default => 'Sin fecha',
                    })
                    ->sortable(query: fn ($query, string $direction) => $query->orderBy('fecha_vencimiento', $direction)),
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
