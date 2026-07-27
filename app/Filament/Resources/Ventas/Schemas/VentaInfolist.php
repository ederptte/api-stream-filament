<?php

namespace App\Filament\Resources\Ventas\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;

class VentaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('cliente.nombre')
                    ->label('Cliente'),
                TextEntry::make('perfilCuenta.compra.cuenta.plataforma')
                    ->label('Plataforma'),
                TextEntry::make('perfilCuenta.compra.cuenta.correo')
                    ->label('Correo'),
                TextEntry::make('perfilCuenta.nombre_perfil')
                    ->label('Perfil'),
                TextEntry::make('perfilCuenta.pin')
                    ->label('PIN'),
                TextEntry::make('precio_venta')
                    ->label('Precio')
                    ->numeric(),
                TextEntry::make('fecha_venta')
                    ->label('Fecha de venta')
                    ->date(),
                TextEntry::make('fecha_vencimiento')
                    ->label('Vence')
                    ->date(),

                TextEntry::make('estado')
                    ->label('Estado de la venta')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'activa' => 'success',
                        'cancelada' => 'danger',
                        'renovada' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'activa' => 'Activa',
                        'cancelada' => 'Cancelada',
                        'renovada' => 'Renovada',
                        default => $state,
                    }),

                TextEntry::make('estado_vencimiento')
                    ->label('Vencimiento')
                    ->getStateUsing(function ($record) {
                        if ($record->estado !== 'activa') {
                            return $record->estado; // cancelada / renovada
                        }

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
                        'cancelada', 'renovada' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'activo' => 'Activo',
                        'por_vencer' => 'Por vencer',
                        'vencido' => 'Vencido',
                        'cancelada', 'renovada' => '—',
                        default => 'Sin fecha',
                    }),
            ]);
    }
}