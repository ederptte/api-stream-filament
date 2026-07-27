<?php

namespace App\Filament\Resources\Clientes\Pages;

use App\Filament\Resources\Clientes\ClienteResource;
use Filament\Actions\EditAction; // En v4 las acciones de cabecera usan este namespace
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ViewCliente extends ViewRecord
{
    protected static string $resource = ClienteResource::class;

    // Agrega un botón de "Editar" arriba a la derecha cuando estén viendo al cliente
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label('Actualizar'),

            Action::make('enviarWhatsapp')
                ->label('Enviar WhatsApp')
                ->color('success') // Color verde característico
                ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                // Generamos la URL usando el campo 'whatsapp' del cliente actual ($this->record)
                ->url(fn () => "https://wa.me/57" . $this->record->whatsapp)
                // 🚀 ESTA LÍNEA HACE LA MAGIA DE ABRIR EN OTRA PESTAÑA:
                ->openUrlInNewTab(), 
                
        ];
    }

    public function getTitle(): string | Htmlable
    {
        return 'DETALLES DE ' . $this->getRecordTitle();
    }
}
