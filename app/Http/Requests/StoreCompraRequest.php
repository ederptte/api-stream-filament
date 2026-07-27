<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompraRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'cuenta_id'      => ['required', 'exists:cuentas,id'], // Verifica que el id ingresado exista de verdad en cuentas
            'precio_compra'  => ['required', 'numeric', 'min:0'],
            'fecha_compra'   => ['required', 'date'],
            'pantallas'      => ['required', 'integer', 'min:1'],
            'dias_duracion'  => ['required', 'integer', 'min:8'],
            'nota'           => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'cuenta_id.required'     => 'Error: Debes seleccionar una cuenta.',
            'cuenta_id.exists'       => 'Error: La cuenta seleccionada no existe.',
            'precio_compra.required' => 'Error: El precio de compra es obligatorio.',
            'precio_compra.numeric'  => 'Error: El precio de compra debe ser un número.',
            'precio_compra.min'      => 'Error: El precio de compra no puede ser negativo.',
            'fecha_compra.required'  => 'Error: La fecha de compra es obligatoria.',
            'fecha_compra.date'      => 'Error: La fecha de compra no es válida.',
            'perfiles.required'     => 'Error: Debes indicar cuantos perfiles tiene la compra.',
            'perfiles.integer'      => 'Error: Los perfiles deben ser un número entero.',
            'perfiles.min'          => 'Error: Debe haber al menos 1 perfil.',
            'dias_duracion.required' => 'Error: Debes indicar la duración de la cuenta.',
            'dias_duracion.min'       => 'Error: La duración solo puede ser 8 o 30 días.',
        ];
    }
}
