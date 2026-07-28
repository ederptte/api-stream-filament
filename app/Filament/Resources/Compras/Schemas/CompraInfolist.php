<?php

namespace App\Filament\Resources\Compras\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Builder;

class CompraInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('cuenta.plataforma')
                    ->label('Plataforma'),
                TextEntry::make('cuenta.correo')
                    ->label('Correo'),
                TextEntry::make('precio_compra')
                    ->label('Precio Compra'),
                TextEntry::make('fecha_compra')
                    ->label('Fecha de Compra'),
                TextEntry::make('pantallas')
                    ->label('Pantallas'),
                TextEntry::make('perfiles_vendidos')
                    ->label('Perfiles Vendidos')
                    ->getStateUsing(fn ($record) => $record->perfilCuentas()->where('estado', 'vendido')->count())
                    ->badge()
                    ->color('info'),
                TextEntry::make('perfiles_disponibles')
                    ->label('Perfiles Disponibles')
                    ->getStateUsing(fn ($record) => $record->pantallas - $record->perfiles_vendidos)
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),
                TextEntry::make('fecha_vencimiento')
                    ->date(),
                TextEntry::make('estado')
                    ->label('Estado')
                    // 1. Convierte la columna en un Badge estilizado
                    ->badge() 
                    // 2. Define qué color toma la etiqueta según el valor en la base de datos
                    ->color(fn (string $state): string => match ($state) {
                        'Disponible', 'activa' => 'success', // Color Verde
                        'vendido', 'ocupado' => 'info',     // Color Azul
                        'mantenimiento' => 'warning',        // Color Amarillo / Naranja
                        'inactivo', 'vencido' => 'danger',
                        'renovada' => 'info', // 👈 nuevo   // Color Rojo
                        default => 'gray',                   // Color Gris por defecto
                    }),
                    
            ]);
    }
}