<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $nombre
 * @property string $whatsapp
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ClienteFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente whereWhatsapp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cliente withoutTrashed()
 */
	class Cliente extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $cuenta_id
 * @property numeric $precio_compra
 * @property \Illuminate\Support\Carbon $fecha_compra
 * @property int $perfiles
 * @property int $dias_duracion
 * @property \Illuminate\Support\Carbon|null $fecha_vencimiento
 * @property string|null $nota
 * @property string $estado
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cuenta|null $cuenta
 * @property-read mixed $pantallas_disponibles
 * @property-read mixed $proximo_vencimiento
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PerfilCuenta> $perfilCuentas
 * @property-read int|null $perfil_cuentas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Venta> $ventas
 * @property-read int|null $ventas_count
 * @method static \Database\Factories\CompraFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Compra newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Compra newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Compra onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Compra query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Compra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Compra whereCuentaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Compra whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Compra whereDiasDuracion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Compra whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Compra whereFechaCompra($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Compra whereFechaVencimiento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Compra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Compra whereNota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Compra wherePerfiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Compra wherePrecioCompra($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Compra whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Compra withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Compra withoutTrashed()
 */
	class Compra extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $plataforma
 * @property string $correo
 * @property string $clave
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Compra> $compras
 * @property-read int|null $compras_count
 * @method static \Database\Factories\CuentaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuenta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuenta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuenta onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuenta query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuenta whereClave($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuenta whereCorreo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuenta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuenta whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuenta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuenta wherePlataforma($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuenta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuenta withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuenta withoutTrashed()
 */
	class Cuenta extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $compra_id
 * @property string $nombre_perfil
 * @property string|null $pin
 * @property string|null $dispositivo_autorizado
 * @property string $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Compra|null $compra
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Venta> $ventas
 * @property-read int|null $ventas_count
 * @method static \Database\Factories\PerfilCuentaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PerfilCuenta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PerfilCuenta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PerfilCuenta query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PerfilCuenta whereCompraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PerfilCuenta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PerfilCuenta whereDispositivoAutorizado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PerfilCuenta whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PerfilCuenta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PerfilCuenta whereNombrePerfil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PerfilCuenta wherePin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PerfilCuenta whereUpdatedAt($value)
 */
	class PerfilCuenta extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string $password
 * @property string $status
 * @property string|null $last_login_at
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $cliente_id
 * @property int $perfil_cuenta_id
 * @property numeric $precio_venta
 * @property \Illuminate\Support\Carbon $fecha_venta
 * @property \Illuminate\Support\Carbon $fecha_vencimiento
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cliente|null $cliente
 * @property-read \App\Models\PerfilCuenta $perfilCuenta
 * @method static \Database\Factories\VentaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta whereFechaVencimiento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta whereFechaVenta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta wherePerfilCuentaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta wherePrecioVenta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta withoutTrashed()
 */
	class Venta extends \Eloquent {}
}

