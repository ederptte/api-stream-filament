<?php

namespace App\Filament\Resources\Ventas\Pages;

use App\Filament\Resources\Clientes\ClienteResource;
use App\Filament\Resources\Compras\CompraResource;
use App\Filament\Resources\Cuentas\CuentaResource;
use App\Filament\Resources\Ventas\VentaResource;
use App\Models\Compra;
use App\Models\PerfilCuenta;
use App\Models\Venta;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\DB;

class ViewVentas extends ViewRecord
{
    protected static string $resource = VentaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label('Actualizar'),

            Action::make('enviarWhatsapp')
                ->label('Enviar Datos')
                ->color('success')
                ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                ->url(function () {
                    $venta = $this->record;
                    $perfil = $venta->perfilCuenta;
                    $cuenta = $perfil?->compra?->cuenta;

                    $mensaje = "Hola {$venta->cliente->nombre}, aquí tienes los datos de tu cuenta:\n\n"
                        . "📺 Plataforma: {$cuenta?->plataforma}\n"
                        . "📧 Correo: {$cuenta?->correo}\n"
                        . "🔑 Clave: {$cuenta?->clave}\n"
                        . "👤 Perfil: {$perfil?->nombre_perfil}\n"
                        . "🔢 PIN: {$perfil?->pin}\n"
                        . "📅 Vence: " . $venta->fecha_vencimiento?->format('d/m/Y');

                    $whatsapp = $venta->cliente->whatsapp;

                    return "https://wa.me{$whatsapp}?text=" . urlencode($mensaje);
                })
                ->openUrlInNewTab(),
            
            Action::make('enviarVencimiento')
                ->label('Enviar Vencimiento')
                ->color('success')
                ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                ->url(function () {
                    $venta = $this->record;
                    $perfil = $venta->perfilCuenta;
                    $cuenta = $perfil?->compra?->cuenta;

                    $mensaje = "Hola {$venta->cliente->nombre}, tu pantalla de {$cuenta?->plataforma} vence {$venta->fecha_vencimiento?->format('d/m/Y')}";
                    $whatsapp = $venta->cliente->whatsapp;

                    return "https://wa.me{$whatsapp}?text=" . urlencode($mensaje);
                })
                ->openUrlInNewTab(),

            Action::make('verCliente')
                ->label('Ver cliente')
                ->icon(Heroicon::OutlinedUser)
                ->color('gray')
                ->url(fn () => ClienteResource::getUrl('view', ['record' => $this->record->cliente_id])),

            Action::make('verCompra')
                ->label('Ver compra')
                ->icon(Heroicon::OutlinedUser)
                ->color('gray')
                ->url(fn () => CompraResource::getUrl('view', ['record' => $this->record->perfilCuenta?->compra_id])),

            Action::make('cambiarPerfil')
                ->label('Cambiar perfil')
                ->icon(Heroicon::OutlinedArrowPath)
                ->color('warning')
                ->visible(fn () => $this->record->estado === 'activa')
                ->schema([
                    Select::make('plataforma_nueva')
                        ->label('Plataforma')
                        ->native(false)
                        ->required()
                        ->live(onBlur: true) // Evita consultas en tiempo real infinitas
                        ->options([
                            'netflix' => 'Netflix',
                            'prime video' => 'Prime Video',
                            'disney_plus' => 'Disney+',
                            'spotify' => 'Spotify',
                            'hbo_max' => 'Max (HBO)',
                        ])
                        ->afterStateUpdated(fn (Set $set) => $set('compra_nueva_id', null)),

                    Select::make('compra_nueva_id')
                        ->label('Correo de la cuenta')
                        ->native(false)
                        ->required()
                        ->live(onBlur: true) // Evita consultas en tiempo real infinitas
                        ->disabled(fn (Get $get) => !$get('plataforma_nueva'))
                        ->afterStateUpdated(fn (Set $set) => $set('perfil_nuevo_id', null))
                        ->options(function (Get $get) {
                            $plataforma = $get('plataforma_nueva');
                            if (!$plataforma) return [];

                            return Compra::query()
                                ->with(['cuenta']) // Alivia el proceso N+1 en Render gratis
                                ->whereHas('cuenta', fn ($q) => $q->where('plataforma', mb_strtoupper($plataforma, 'UTF-8')))
                                ->where('estado', 'activa')
                                ->whereHas('perfilCuentas', fn ($q) => $q->where('estado', 'disponible'))
                                ->get()
                                ->mapWithKeys(fn ($compra) => [$compra->id => $compra->cuenta?->correo ?? "Compra N° {$compra->id}"])
                                ->toArray();
                        }),

                    Select::make('perfil_nuevo_id')
                        ->label('Perfil disponible')
                        ->native(false)
                        ->required()
                        ->disabled(fn (Get $get) => !$get('compra_nueva_id'))
                        ->options(function (Get $get) {
                            $compraId = $get('compra_nueva_id');
                            if (!$compraId) return [];

                            return PerfilCuenta::query()
                                ->where('compra_id', $compraId)
                                ->where('estado', 'disponible')
                                ->get()
                                ->mapWithKeys(fn ($perfil) => [$perfil->id => $perfil->nombre_perfil])
                                ->toArray();
                        }),

                    Select::make('estado_perfil_viejo')
                        ->label('¿Qué hacer con el perfil/cuenta actual?')
                        ->native(false)
                        ->required()
                        ->default('mantenimiento')
                        ->options([
                            'mantenimiento' => 'Dañado / en mantenimiento',
                            'disponible' => 'Ya está reparado, dejarlo disponible',
                        ]),
                ])
                ->action(function (array $data) {
                    $perfilViejo = $this->record->perfilCuenta;
                    $perfilNuevo = PerfilCuenta::find($data['perfil_nuevo_id']);

                    DB::transaction(function () use ($perfilViejo, $perfilNuevo, $data) {
                        $perfilViejo?->update(['estado' => $data['estado_perfil_viejo']]);
                        $perfilNuevo->update(['estado' => 'vendido']);
                        $this->record->update(['perfil_cuenta_id' => $perfilNuevo->id]);
                    });

                    Notification::make()
                        ->title('Perfil cambiado correctamente')
                        ->success()
                        ->send();

                    $this->fillForm();
                }),

            Action::make('renovarVenta')
                ->label('Renovar')
                ->icon(Heroicon::OutlinedArrowPathRoundedSquare)
                ->color('success')
                ->visible(fn () => $this->record->estado === 'activa')
                ->schema([
                    TextInput::make('precio_venta')
                        ->label('Precio de la renovación')
                        ->required()
                        ->numeric()
                        ->default(fn () => $this->record->precio_venta),

                    DatePicker::make('nueva_fecha_vencimiento')
                        ->label('Nueva Fecha de Vencimiento')
                        ->required()
                        ->default(fn () => $this->record->fecha_vencimiento?->addMonth() ?? now()->addMonth()),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'precio_venta' => $data['precio_venta'],
                        'fecha_vencimiento' => $data['nueva_fecha_vencimiento'],
                    ]);

                    Notification::make()
                        ->title('Venta renovada correctamente')
                        ->success()
                        ->send();

                    $this->fillForm();
                }),
        ];
    }
}
