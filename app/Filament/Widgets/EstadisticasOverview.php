<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use App\Models\Compra;
use App\Models\PerfilCuenta;
use App\Models\Venta;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EstadisticasOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Dinero: excluimos canceladas de todos los cálculos financieros
        $ingresos = Venta::where('estado', '!=', 'cancelada')->sum('precio_venta');
        $gastos = Compra::where('estado', '!=', 'cancelada')->sum('precio_compra');
        $ganancia = $ingresos - $gastos;

        // Conteos
        $totalClientes = Cliente::count();
        $comprasActivas = Compra::where('estado', 'activa')->count();
        $ventasActivas = Venta::where('estado', 'activa')->count();
        $perfilesDisponibles = PerfilCuenta::where('estado', 'disponible')->count();
        $perfilesVendidos = PerfilCuenta::where('estado', 'vendido')->count();
        $perfilesMantenimiento = PerfilCuenta::where('estado', 'mantenimiento')->count();

        // Ventas por vencer (3 días) y vencidas, solo entre las activas
        $porVencer = Venta::where('estado', 'activa')
            ->whereDate('fecha_vencimiento', '>=', now())
            ->whereDate('fecha_vencimiento', '<=', now()->addDays(3))
            ->count();

        $vencidas = Venta::where('estado', 'activa')
            ->whereDate('fecha_vencimiento', '<', now())
            ->count();

        return [
            Stat::make('Ingresos totales', '$' . number_format($ingresos, 0, ',', '.'))
                ->color('success')
                ->icon('heroicon-o-arrow-trending-up'),

            Stat::make('Gastos totales', '$' . number_format($gastos, 0, ',', '.'))
                ->color('danger')
                ->icon('heroicon-o-arrow-trending-down'),

            Stat::make('Ganancia', '$' . number_format($ganancia, 0, ',', '.'))
                ->color($ganancia >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Clientes', $totalClientes)
                ->color('info')
                ->icon('heroicon-o-users'),

            Stat::make('Compras activas', $comprasActivas)
                ->color('info')
                ->icon('heroicon-o-shopping-cart'),

            Stat::make('Ventas activas', $ventasActivas)
                ->color('info')
                ->icon('heroicon-o-currency-dollar'),

            Stat::make('Perfiles disponibles', $perfilesDisponibles)
                ->color('success')
                ->icon('heroicon-o-check-circle'),

            Stat::make('Perfiles vendidos', $perfilesVendidos)
                ->color('gray')
                ->icon('heroicon-o-user-circle'),

            Stat::make('Perfiles en mantenimiento', $perfilesMantenimiento)
                ->color('warning')
                ->icon('heroicon-o-wrench-screwdriver'),

            Stat::make('Por vencer (≤3 días)', $porVencer)
                ->color('warning')
                ->icon('heroicon-o-clock'),

            Stat::make('Vencidas', $vencidas)
                ->color('danger')
                ->icon('heroicon-o-exclamation-triangle'),
        ];
    }
}