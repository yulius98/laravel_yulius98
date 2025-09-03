<?php

namespace App\Services;

use App\Models\TblRumahSakit;
use App\Models\TblPasien;

class DashboardService
{
    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(): array
    {
        return [
            'total_rumah_sakit' => TblRumahSakit::count(),
            'total_pasien' => TblPasien::count(),
            'current_date' => now()->format('d/m/Y'),
        ];
    }

    /**
     * Get quick actions for dashboard
     */
    public function getQuickActions(): array
    {
        return [
            [
                'title' => 'Kelola Data Rumah Sakit',
                'url' => route('rumahsakit.index', ['user' => auth()->user()->username ?? 'admin']),
                'icon' => 'bi-hospital',
                'class' => 'btn-outline-primary'
            ],
            [
                'title' => 'Kelola Data Pasien',
                'url' => route('pasien.index', ['user' => auth()->user()->username ?? 'admin']),
                'icon' => 'bi-person-lines-fill',
                'class' => 'btn-outline-success'
            ]
        ];
    }
}
