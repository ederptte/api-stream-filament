<?php

namespace App\Filament\Resources\Compras\Pages;

use App\Filament\Resources\Compras\CompraResource;
use App\Models\Compra;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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

            Action::make('renovarCompra')
                ->label('Renovar')
                ->icon(Heroicon::OutlinedArrowPathRoundedSquare)
                ->color('success')
                ->visible(fn () => $this->record->estado === 'activa')
                ->schema([
                    TextInput::make('precio_compra')
                        ->label('Precio de la nueva compra')
                        ->required()
                        ->numeric()
                        ->default(fn () => $this->record->precio_compra),

                    DatePicker::make('fecha_compra')
                        ->label('Fecha de compra')
                        ->required()
                        ->default(now()),

                    Textarea::make('nota')
                        ->label('Nota')
                        ->default(fn () => "Renovación de compra #{$this->record->id}")
                        ->columnSpanFull(),
                ])
                ->action(function (array $data) {
                    $compraVieja = $this->record;

                    $nuevaCompra = DB::transaction(function () use ($compraVieja, $data) {
                        $perfilesVendidos = $compraVieja->perfilCuentas()->where('estado', 'vendido')->get();

                        $nueva = Compra::create([
                            'cuenta_id' => $compraVieja->cuenta_id,
                            'precio_compra' => $data['precio_compra'],
                            'fecha_compra' => $data['fecha_compra'],
                            'pantallas' => $perfilesVendidos->count(),
                            'nota' => $data['nota'],
                            'estado' => 'activa',
                        ]);

                        // Mueve los perfiles vendidos a la nueva compra, sin tocar su estado ni fecha_vencimiento
                        $compraVieja->perfilCuentas()
                            ->where('estado', 'vendido')
                            ->update(['compra_id' => $nueva->id]);

                        // Los perfiles que quedaron sin vender ya no aplican
                        $compraVieja->perfilCuentas()->where('estado', 'disponible')->delete();

                        $compraVieja->update(['estado' => 'renovada']);

                        return $nueva;
                    });

                    Notification::make()
                        ->title('Cuenta renovada correctamente')
                        ->success()
                        ->send();

                    $this->redirect(CompraResource::getUrl('view', ['record' => $nuevaCompra->id]));
                }),
        ];
    }

    protected static ?string $title = 'DETALLES DE COMPRA';
}