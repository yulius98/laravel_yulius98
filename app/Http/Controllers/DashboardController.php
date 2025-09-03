<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display dashboard
     */
    public function index(string $username)
    {
        $stats = $this->dashboardService->getDashboardStats();
        $quickActions = $this->dashboardService->getQuickActions();

        return view('Dashboard', [
            'title' => $username,
            'username' => $username,
            'jmlrs' => $stats['total_rumah_sakit'],
            'jmlpasien' => $stats['total_pasien'],
            'quick_actions' => $quickActions
        ]);
    }
}
