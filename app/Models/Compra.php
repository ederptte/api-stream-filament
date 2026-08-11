<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Compra extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'compras';

    // 👇 Bandera para saltar la auto-creación de perfiles (usada al renovar,
    // donde el código de la Action ya genera los perfiles manualmente)
    public static bool $skipPerfilesAutoCreate = false;

    protected $fillable = [
        'cuenta_id',
        'precio_compra',
        'fecha_compra',
        'pantallas',
        'nota',
        'estado',
        'dias_duracion',
        'fecha_vencimiento',
    ];

    protected $casts = [
        'fecha_compra' => 'date',
        'fecha_vencimiento' => 'date',
    ];

    /**
     * Calcula automáticamente fecha_vencimiento = fecha_compra + dias_duracion,
     * solo si no viene ya definida manualmente.
     */
    protected static function booted()
    {
        static::creating(function ($compra) {
            if ($compra->fecha_compra && $compra->dias_duracion && !$compra->fecha_vencimiento) {
                $compra->fecha_vencimiento = Carbon::parse($compra->fecha_compra)
                    ->addDays($compra->dias_duracion)
                    ->format('Y-m-d');
            }
        });

        static::updating(function ($compra) {
            if ($compra->isDirty(['fecha_compra', 'dias_duracion']) && $compra->fecha_compra && $compra->dias_duracion) {
                $compra->fecha_vencimiento = Carbon::parse($compra->fecha_compra)
                    ->addDays($compra->dias_duracion)
                    ->format('Y-m-d');
            }
        });

        static::created(function ($compra) {
            if (static::$skipPerfilesAutoCreate) {
                return; // 👈 evita perfiles fantasma al renovar
            }

            for ($i = 1; $i <= $compra->pantallas; $i++) {
                PerfilCuenta::create([
                    'compra_id' => $compra->id,
                    'nombre_perfil' => "Perfil {$i}",
                    'pin' => '',
                    'dispositivo_autorizado' => '',
                    'estado' => 'disponible',
                ]);
            }
        });
    }

    public function getPantallasDisponiblesAttribute()
    {
        if (array_key_exists('perfiles_vendidos', $this->attributes)) {
            return $this->pantallas - ($this->attributes['perfiles_vendidos'] ?? 0);
        }

        return $this->pantallas - $this->ventas()->count();
    }

    public function getEstadoActualAttribute()
    {
        if (in_array($this->estado, ['cancelada', 'renovada'])) {
            return $this->estado;
        }

        if ($this->fecha_vencimiento && $this->fecha_vencimiento->isPast()) {
            return 'vencida';
        }

        return $this->estado;
    }

    public function getProximoVencimientoAttribute()
    {
        return $this->perfilCuentas()
            ->where('estado', 'vendido')
            ->join('ventas', 'ventas.perfil_cuenta_id', '=', 'perfil_cuentas.id')
            ->whereNull('ventas.deleted_at')
            ->min('ventas.fecha_vencimiento');
    }

    public function perfilCuentas()
    {
        return $this->hasMany(PerfilCuenta::class, 'compra_id');
    }

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class);
    }

    public function ventas()
    {
        return $this->hasManyThrough(
            Venta::class,
            PerfilCuenta::class,
            'compra_id',
            'perfil_cuenta_id',
            'id',
            'id'
        );
    }
}