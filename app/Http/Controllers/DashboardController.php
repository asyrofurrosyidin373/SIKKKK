<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\HamaPenyakit;
use App\Models\Gejala;
use App\Models\DeteksiHistory;
use App\Models\Insektisida;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic statistics
        $stats = [
            'total_hama' => HamaPenyakit::hama()->count(),
            'total_penyakit' => HamaPenyakit::penyakit()->count(),
            'total_gejala' => Gejala::count(),
            'total_insektisida' => Insektisida::count(),
        ];
        
        // Detection statistics
        $detectionStats = [
            'today' => DeteksiHistory::today()->count(),
            'this_week' => DeteksiHistory::thisWeek()->count(),
            'this_month' => DeteksiHistory::thisMonth()->count(),
        ];
        
        // Recent detections
        $recentDetections = DeteksiHistory::with(['gejalaUsed'])
                                          ->orderBy('detected_at', 'desc')
                                          ->take(10)
                                          ->get();
        
        // Most common symptoms
        $commonSymptoms = Gejala::withCount(['hamaPenyakit'])
                                ->orderBy('hama_penyakit_count', 'desc')
                                ->take(5)
                                ->get();
        
        // Chart data for detections over time
        $chartData = $this->getDetectionChartData();
        
        return view('dashboard.index', compact(
            'stats', 
            'detectionStats', 
            'recentDetections', 
            'commonSymptoms',
            'chartData'
        ));
    }
    
    private function getDetectionChartData()
    {
        $last7Days = collect();
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = DeteksiHistory::whereDate('detected_at', $date)->count();
            
            $last7Days->push([
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('M j'),
                'count' => $count
            ]);
        }
        
        return $last7Days;
    }
}