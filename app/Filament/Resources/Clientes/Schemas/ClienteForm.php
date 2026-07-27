<?php

namespace App\Filament\Resources\Clientes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema; // <-- Nota este cambio fundamental en v4

class ClienteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),

                TextInput::make('whatsapp')
                    ->required()
                    ->numeric()
                    ->Length(10)
                    ->unique(table: 'clientes', column: 'whatsapp', ignoreRecord: true)
                    ->validationMessages([
                    'numeric' => 'El campo WhatsApp debe contener solo números.',
                    'length' => 'El número de WhatsApp debe tener exactamente 10 dígitos.',
                    'unique' => 'Este número de WhatsApp ya se encuentra registrado.',
                    ]),

                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(table: 'clientes', column: 'email', ignoreRecord: true)
                    ->maxLength(255)
                     ->validationMessages([
                    'unique' => 'Este email ya se encuentra registrado.',
                    ]),
            ]);
    }
}
