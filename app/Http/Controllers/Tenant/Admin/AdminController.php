<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Financial Stats
        $revenue = '1234567';
        $pendingFees = '1234567';

        // 2. Academic Stats
        $totalEnrollments = \App\Models\Tenant\CourseEnrollment::count();
        $activeStudents = \App\Models\Tenant\User::where('role', 'student')->where('status', 1)->count();
        $totalCourses = \App\Models\Tenant\Course::count();

        // 3. Content Stats
        $totalQuizzes = \App\Models\Tenant\Content::where('type', 'quiz')->count();
        $totalAssignments = \App\Models\Tenant\Content::where('type', 'assignment')->count();

        // 4. Chart Data (Mocking monthly data for demonstration if no real historical data exists easily)
        // In a real scenario, you'd group by created_at
        $revenueChartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'data' => [12000, 19000, 15000, 25000, 22000, 30000] // Replace with real DB query if needed
        ];

        return view('tenant.admin.dashboard.index', compact(
            'revenue',
            'pendingFees',
            'totalEnrollments',
            'activeStudents',
            'totalCourses',
            'totalQuizzes',
            'totalAssignments',
            'revenueChartData'
        ));
    }
}
