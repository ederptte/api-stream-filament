<?php

namespace App\Filament\Resources\Compras\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class CompraForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('cuenta_id')
                ->label('plataforma')
                ->relationship(name: 'cuenta', titleAttribute: 'plataforma') // 👈 La columna de tu tabla 'cuentas' que quieres que el usuario busque (ej: 'plataforma' o 'correo')
                ->searchable() // 🚀 Activa la barra de búsqueda en tiempo real en la base de datos
                ->preload()    // Carga las primeras opciones automáticamente al abrir el menú desplegable
                ->required()
                ->native(false)
                ->placeholder('Selecciona Plataforma')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->plataforma} — ({$record->correo})"),

                TextInput::make('precio_compra')
                    ->required()
                    ->numeric(),
                DatePicker::make('fecha_compra')
                    ->required(),
                TextInput::make('pantallas')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->minValue(1)
                    ->default(1),
                Select::make('dias_duracion')
                    ->required()
                    ->integer()
                    ->minValue(1)
                    ->default(30),
                    
                
                Textarea::make('nota')
                    ->columnSpanFull(),
                
            ]);
    }
}
