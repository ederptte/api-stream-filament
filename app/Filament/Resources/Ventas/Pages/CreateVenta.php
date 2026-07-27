<?php

namespace App\Filament\Resources\Ventas\Pages;

use App\Filament\Resources\Ventas\VentaResource;
use App\Models\PerfilCuenta;
use Filament\Resources\Pages\CreateRecord;

class CreateVenta extends CreateRecord
{
    protected static string $resource = VentaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $pin = $data['pin'] ?? null;
        $perfilCuentaId = $data['perfil_cuenta_id'] ?? null;

        // Quitamos los campos auxiliares que no son columnas de "ventas"
        unset($data['plataforma_seleccionada'], $data['pin']);

        // Guardamos el pin en el perfil real, no en la venta
        if ($pin && $perfilCuentaId) {
            PerfilCuenta::where('id', $perfilCuentaId)->update(['pin' => $pin]);
        }

        return $data;
    }
}