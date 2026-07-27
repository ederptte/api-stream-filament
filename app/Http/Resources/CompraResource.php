<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompraResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'cuenta_id' => $this->cuenta_id,
        'precio_compra' => $this->precio_compra,
        'fecha_compra' => $this->fecha_compra,
        'perfiles' => $this->perfiles,
        'dias_duracion' => $this->dias_duracion,
        'fecha_vencimiento' => $this->fecha_vencimiento,
        'nota' => $this->nota,
        'estado' => $this->estado,

        'cuenta' => new CuentaResource($this->whenLoaded('cuenta')),

        // NUEVO: la lista de perfiles de esta compra
        'perfiles_compra' => $this->whenLoaded('perfilCuentas', function () {
            return $this->perfilCuentas->map(function ($perfil) {
                return [
                    'id' => $perfil->id,
                    'nombre_perfil' => $perfil->nombre_perfil,
                    'estado' => $perfil->estado,
                ];
            });
        }),

        'perfiles_vendidos' => $this->perfiles_vendidos ?? 0,
        'proximo_vencimiento' => $this->proximo_vencimiento,

        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
    ];
}
}
