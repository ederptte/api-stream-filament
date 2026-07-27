<?php

namespace App\Filament\Resources\Cuentas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema; // <-- Nota este cambio fundamental en v4
use Filament\Forms\Components\Select;

class CuentaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                select::make('plataforma')
                    ->label('plataforma')
                    ->required()
                    ->native(false)
                    ->placeholder('Selecciona una plataforma')
                    ->options([
                        'netflix' => 'Netflix',
                        'prime video' => 'Prime Video',
                        'disney plus' => 'Disney Plus',
                        'hbo max' => 'Hbo Max',
                    ]),
                
                TextInput::make('correo')
                    ->email()
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('clave')
                    ->required()
                    ->maxLength(255),

            ]);
    }
}
