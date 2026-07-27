<?php

namespace App\Filament\Resources\Clientes\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;

class ClienteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nombre')
                    ->label('Nombre'),
                TextEntry::make('whatsapp')
                    ->label('Whatsapp'),
                TextEntry::make('email')
                    ->label('Correo'),
                
            ]);
    }
}