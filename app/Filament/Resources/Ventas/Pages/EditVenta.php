<?php

namespace App\Filament\Resources\Ventas\Pages;

use App\Filament\Resources\Ventas\VentaResource;
use App\Models\PerfilCuenta;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVenta extends EditRecord
{
    protected static string $resource = VentaResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (!empty($data['perfil_cuenta_id'])) {
            $perfil = PerfilCuenta::with('compra.cuenta')->find($data['perfil_cuenta_id']);

            if ($perfil?->compra?->cuenta) {
                // Reconstruimos los campos virtuales para que el Select los muestre ya seleccionados
                $data['plataforma_seleccionada'] = strtolower($perfil->compra->cuenta->plataforma);
                $data['compra_id'] = $perfil->compra_id;
                $data['pin'] = $perfil->pin;
            }
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $pin = $data['pin'] ?? null;
        $perfilCuentaId = $data['perfil_cuenta_id'] ?? null;

        unset($data['plataforma_seleccionada'], $data['compra_id'], $data['pin']);

        if ($pin && $perfilCuentaId) {
            PerfilCuenta::where('id', $perfilCuentaId)->update(['pin' => $pin]);
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}