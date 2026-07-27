<?php

namespace App\Filament\Resources\Clientes\RelationManagers;

use App\Filament\Resources\Ventas\VentaResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action; // [!code ++] // En Filament V4 todas las acciones usan este único namespace global
use Filament\Support\Enums\IconSize; // [!code ++] // Opcional, si deseas formatear tamaños en V4

class VentasRelationManager extends RelationManager
{
    protected static string $relationship = 'ventas';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->modifyQueryUsing(fn ($query) => $query->with('perfilCuenta.compra.cuenta'))
            ->columns([
                TextColumn::make('perfilCuenta.compra.cuenta.plataforma')->label('Plataforma'),
                TextColumn::make('perfilCuenta.compra.cuenta.correo')->label('Correo'),
                TextColumn::make('perfilCuenta.nombre_perfil')->label('Perfil'),
                TextColumn::make('precio_venta')->label('Precio')->numeric(),
                TextColumn::make('fecha_venta')->label('Fecha venta')->date(),
                TextColumn::make('fecha_vencimiento')->label('Fecha Vencimiento')->date(),

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
                
            ])
            ->actions([ // [!code ++]
                Action::make('verVenta') // [!code ++]
                    ->label('Ver') // [!code ++]
                    ->icon('heroicon-m-eye') // [!code ++]
                    ->url(fn ($record): string => VentaResource::getUrl('view', ['record' => $record])), // [!code ++]
            ]) // [!code ++]
            ->defaultSort('fecha_venta', 'desc');
    }
}
