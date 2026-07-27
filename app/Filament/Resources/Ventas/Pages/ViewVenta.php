<?php

namespace App\Filament\Resources\Ventas\Pages;

use App\Filament\Resources\Clientes\ClienteResource;
use App\Filament\Resources\Compras\CompraResource;
use App\Filament\Resources\Cuentas\CuentaResource;
use App\Filament\Resources\Ventas\VentaResource;
use Filament\Actions\EditAction; // En v4 las acciones de cabecera usan este namespace
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ViewVentas extends ViewRecord
{
    protected static string $resource = VentaResource::class;

    // Agrega un botón de "Editar" arriba a la derecha cuando estén viendo al cliente
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
                
        ];

        
    }

    protected static ?string $title = 'DETALLE DE VENTA';

}
