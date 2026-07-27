<?php

namespace App\Filament\Resources\Ventas\Pages;

use App\Filament\Resources\Clientes\ClienteResource;
use App\Filament\Resources\Compras\CompraResource;
use App\Filament\Resources\Cuentas\CuentaResource;
use App\Filament\Resources\Ventas\VentaResource;
use App\Models\PerfilCuenta;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ViewRecord;
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

                    return "https://wa.me/57{$whatsapp}?text=" . urlencode($mensaje);
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
                ->visible(fn () => $this->record->estado !== 'cancelada')
                ->schema([
                    Select::make('perfil_nuevo_id')
                        ->label('Nuevo perfil')
                        ->native(false)
                        ->required()
                        ->options(function () {
                            $compraId = $this->record->perfilCuenta?->compra_id;
                            if (!$compraId) return [];

                            return PerfilCuenta::query()
                                ->where('compra_id', $compraId)
                                ->where('estado', 'disponible')
                                ->get()
                                ->mapWithKeys(fn ($perfil) => [$perfil->id => $perfil->nombre_perfil])
                                ->toArray();
                        }),
                    Select::make('estado_perfil_viejo')
                        ->label('¿Qué hacer con el perfil actual?')
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

                    $this->fillForm(); // refresca la vista con el nuevo perfil
                }),

            Action::make('cancelarVenta')
                ->label('Cancelar venta')
                ->icon(Heroicon::OutlinedXCircle)
                ->color('danger')
                ->requiresConfirmation()
                ->modalDescription('Esto cancelará la venta y liberará el perfil para poder venderlo de nuevo. El historial se conserva.')
                ->visible(fn () => $this->record->estado !== 'cancelada')
                ->action(function () {
                    $this->record->update(['estado' => 'cancelada']);
                    $this->record->perfilCuenta?->update(['estado' => 'disponible']);

                    Notification::make()
                        ->title('Venta cancelada. El perfil quedó disponible de nuevo.')
                        ->warning()
                        ->send();
                }),
        ];
    }

    protected static ?string $title = 'DETALLE DE VENTA';
}