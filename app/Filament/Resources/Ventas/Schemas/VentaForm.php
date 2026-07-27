<?php

namespace App\Filament\Resources\Ventas\Schemas;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Cuenta;
use App\Models\PerfilCuenta;
use App\Models\Compra;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class VentaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('cliente_id')
                    ->label('Cliente')
                    ->relationship(name: 'cliente', titleAttribute: 'nombre')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->native(false)
                    ->placeholder('Selecciona Cliente'),

                Select::make('plataforma_seleccionada')
                    ->label('Plataforma')
                    ->placeholder('Elija una plataforma...')
                    ->native(false)
                    ->required()
                    ->dehydrated(false) // 👈 este campo es solo auxiliar, no existe en la tabla ventas
                    ->options([
                        'netflix' => 'Netflix',
                        'prime video' => 'Prime Video',
                        'disney_plus' => 'Disney+',
                        'spotify' => 'Spotify',
                        'hbo_max' => 'Max (HBO)',
                    ])
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('compra_id', null);
                        $set('perfil_cuenta_id', null);
                    }),

                // 3. SELECCIONAR CORREO DE LA COMPRA
                Select::make('compra_id')
                    ->label('Correo de la Cuenta')
                    ->placeholder(fn (Get $get) => $get('plataforma_seleccionada')
                        ? 'Seleccione el correo disponible...'
                        : '⚠️ Primero elija una plataforma'
                    )
                    ->native(false)
                    ->required()
                    ->disabled(fn (Get $get) => !$get('plataforma_seleccionada'))
                    ->dehydrated(true) // 👈 clave: sin esto, compra_id no se guarda al enviar el form
                    ->live()
                    ->afterStateUpdated(fn (Set $set) => $set('perfil_cuenta_id', null))
                    ->options(function (Get $get,  $record) {
                        $plataforma = $get('plataforma_seleccionada');
                        if (!$plataforma) return [];

                        return Compra::query()
                            ->whereHas('cuenta', function (Builder $q) use ($plataforma) {
                                $q->where('plataforma', mb_strtoupper($plataforma, 'UTF-8'));
                            })
                            ->where(function (Builder $q) use ($record) {
                                $q->where(function (Builder $activa) {
                                    $activa->where('estado', 'activa')
                                        ->whereHas('perfilCuentas', function (Builder $subQ) {
                                            $subQ->where('estado', 'disponible');
                                        });
                                });

                                // Incluye también la compra ya asignada a esta venta, aunque ya no tenga perfiles disponibles
                                if ($record?->perfilCuenta) {
                                    $q->orWhere('id', $record->perfilCuenta->compra_id);
                                }
                            })
                            ->get()
                            ->mapWithKeys(fn ($compra) => [$compra->id => $compra->cuenta?->correo ?? "Compra N° {$compra->id}"])
                            ->toArray();
                    }),
                
                Select::make('perfil_cuenta_id')
                    ->label('Perfil')
                    ->placeholder(fn (Get $get) => $get('compra_id')
                        ? 'Seleccione el perfil disponible...'
                        : '⚠️ Primero elija el correo de la cuenta'
                    )
                    ->native(false)
                    ->required()
                    ->disabled(fn (Get $get) => !$get('compra_id'))
                    ->dehydrated(true)
                    ->live()
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        $perfil = $state ? PerfilCuenta::find($state) : null;
                        $set('pin', $perfil?->pin);
                    })
                    ->options(function (Get $get, $record) {
                        $compraId = $get('compra_id');
                        if (!$compraId) return [];

                        return PerfilCuenta::query()
                            ->where('compra_id', $compraId)
                            ->where(function ($q) use ($record) {
                                $q->where('estado', 'disponible');

                                if ($record) {
                                    $q->orWhere('id', $record->perfil_cuenta_id);
                                }
                            })
                            ->get()
                            ->mapWithKeys(fn ($perfil) => [$perfil->id => $perfil->nombre_perfil])
                            ->toArray();
                    }),

                TextInput::make('pin')
                    ->label('PIN del perfil')
                    ->placeholder('Ej: 1234')
                    ->required()
                    ->maxLength(4)
                    ->dehydrated(true),

                TextInput::make('precio_venta')
                    ->required()
                    ->numeric(),
                
                

                DatePicker::make('fecha_venta')
                    ->required(),

            ]);
    }
}