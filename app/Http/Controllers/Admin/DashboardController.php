<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\AdminController;
use App\Models\Klien;
use App\Models\Permohonan;
use App\Models\Dokumen;
use Carbon\Carbon;

class DashboardController extends AdminController
{
    public function index()
    {
        $this->data['title'] = 'Dashboard';

        // Load common data
        $this->loadBasicStats();

        // Load role-specific data
        if (backpack_user()->hasRole('klien')) {
            $this->loadClientSpecificData();
        } else {
            $this->loadAdminSpecificData();
        }

        return view(backpack_view('dashboard'), $this->data);
    }

    protected function loadBasicStats()
    {
        $this->data['total_klien'] = Klien::count();
        $this->data['total_permohonan'] = Permohonan::count();
        $this->data['permohonan_baru'] = Permohonan::where('status', 1)->count();

        // Permohonan status breakdown
        $this->data['status_counts'] = [
            'draft' => Permohonan::where('status', 0)->count(),
            'terkirim' => Permohonan::where('status', 1)->count(),
            'diproses' => Permohonan::where('status', 2)->count(),
            'selesai' => Permohonan::where('status', 3)->count(),
        ];
    }

    protected function loadClientSpecificData()
    {
        $klienId = backpack_user()->klien->id_klien;

        // Client's permohonan stats
        $this->data['client_permohonan'] = [
            'total' => Permohonan::where('id_klien', $klienId)->count(),
            'draft' => Permohonan::where([
                ['id_klien', $klienId],
                ['status', 0]
            ])->count(),
            'proses' => Permohonan::where([
                ['id_klien', $klienId],
                ['status', 2]
            ])->count(),
        ];

        // Recent documents needing approval
        $this->data['dokumen_pending'] = Dokumen::with(['permohonan', 'jenisDokumen'])
            ->whereHas('permohonan', function($query) use ($klienId) {
                $query->where('id_klien', $klienId);
            })
            ->where('status', 0)
            ->orderBy('created_at', 'asc')
            ->limit(5)
            ->get();

        // Recent completed permohonan
        $this->data['recent_completed'] = Permohonan::where([
                ['id_klien', $klienId],
                ['status', 3]
            ])
            ->orderBy('updated_at', 'desc')
            ->limit(3)
            ->get();
    }

    protected function loadAdminSpecificData()
    {
        // Pending documents for admin
        $this->data['dokumen_pending'] = Dokumen::with(['permohonan.klien', 'jenisDokumen'])
            ->where('status', 0)
            ->orderBy('created_at', 'asc')
            ->limit(5)
            ->get();

        // Recent activities
        $this->data['recent_permohonan'] = Permohonan::with('klien')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Monthly permohonan stats
        $this->data['monthly_stats'] = $this->getMonthlyStats();
    }

    protected function getMonthlyStats()
    {
        $stats = [];
        $months = 6; // Number of months to show

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('Y-m');

            $stats[$date->format('M Y')] = [
                'total' => Permohonan::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'completed' => Permohonan::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('status', 3)
                    ->count(),
            ];
        }

        return $stats;
    }
}
