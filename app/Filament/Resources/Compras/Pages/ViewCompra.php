<?php

namespace App\Filament\Resources\Compras\Pages;

use App\Filament\Resources\Compras\CompraResource;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class ViewCompra extends ViewRecord
{
    protected static string $resource = CompraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label('Actualizar'),

            Action::make('cancelarCompra')
                ->label('Cancelar compra')
                ->icon(Heroicon::OutlinedXCircle)
                ->color('danger')
                ->requiresConfirmation()
                ->modalDescription('Esto cancelará la compra. Si tenía perfiles vendidos, quedarán eliminados. El historial se conserva.')
                ->visible(fn () => $this->record->estado !== 'cancelada')
                ->action(function () {
                    $tieneVendidos = $this->record->perfilCuentas()->where('estado', 'vendido')->exists();

                    DB::transaction(function () {
                        $this->record->perfilCuentas()->delete();
                        $this->record->update(['estado' => 'cancelada']);
                    });

                    Notification::make()
                        ->title($tieneVendidos
                            ? 'Compra anulada. Atención: tenía perfiles vendidos que fueron eliminados.'
                            : 'Compra anulada correctamente.')
                        ->warning()
                        ->send();
                }),
        ];
    }

    protected static ?string $title = 'DETALLES DE COMPRA';
}