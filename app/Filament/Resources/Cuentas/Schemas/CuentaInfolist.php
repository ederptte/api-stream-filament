<?php

namespace App\Filament\Resources\Cuentas\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;

class CuentaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('plataforma')
                    ->label('Plataforma'),
                TextEntry::make('correo')
                    ->label('Correo'),
                TextEntry::make('clave')
                    ->label('Clave'),
                
            ]);
    }
}