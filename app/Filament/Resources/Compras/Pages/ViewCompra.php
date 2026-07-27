<?php

namespace App\Filament\Resources\Compras\Pages;

use App\Filament\Resources\Compras\CompraResource;
use Filament\Actions\EditAction; // En v4 las acciones de cabecera usan este namespace
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ViewCompra extends ViewRecord
{
    protected static string $resource = CompraResource::class;

    // Agrega un botón de "Editar" arriba a la derecha cuando estén viendo al cliente
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label('Actualizar'),

                
        ];
    }

    protected static ?string $title = 'DETALLES DE COMPRA';
}
