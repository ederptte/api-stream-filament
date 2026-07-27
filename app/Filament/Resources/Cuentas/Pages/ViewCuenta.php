<?php

namespace App\Filament\Resources\Cuentas\Pages;

use App\Filament\Resources\Cuentas\CuentaResource;
use Filament\Actions\EditAction; // En v4 las acciones de cabecera usan este namespace
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ViewCuenta extends ViewRecord
{
    protected static string $resource = CuentaResource::class;

    // Agrega un botón de "Editar" arriba a la derecha cuando estén viendo al cliente
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label('Actualizar'),
                
        ];

        
    }

    protected static ?string $title = 'DETALLES DE CUENTA';

}
