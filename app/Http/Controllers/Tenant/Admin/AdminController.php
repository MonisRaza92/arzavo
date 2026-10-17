<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\User;
use App\Models\Tenant\Course;
use App\Models\Tenant\CourseEnrollment;
use App\Models\Tenant\FeePayments;
use App\Models\Tenant\Order;
use App\Models\Tenant\OrderItem;
use App\Models\Tenant\Admission;
use App\Models\Tenant\Book;
use App\Models\Tenant\BookCategory;
use App\Models\Tenant\Inquiry;

class AdminController extends Controller
{
    public function index()
    {
        // =========================================================================
        // 1. REAL USER METRICS (Categorized by Role)
        // =========================================================================
        $students = User::where('role', 'student')->get();
        $teachers = User::where('role', 'teacher')->get();
        $staff = User::where('role', 'staff')->get();
        $admins = User::where('role', 'admin')->get();
        $normalUsers = User::where(function ($q) {
            $q->where('role', 'user')
              ->orWhere('role', 'users')
              ->orWhereNotIn('role', ['student', 'teacher', 'staff', 'admin'])
              ->orWhereNull('role');
        })->get();
        $users = User::all();

        $totalStudents = $students->count();
        $activeStudents = $students->filter(function ($u) {
            return $u->status === 'active' || $u->status === 1 || $u->status === '1';
        })->count();
        $inactiveStudents = max(0, $totalStudents - $activeStudents);

        $totalTeachers = $teachers->count();
        $activeTeachers = $teachers->filter(function ($u) {
            return $u->status === 'active' || $u->status === 1 || $u->status === '1';
        })->count();

        $totalStaff = $staff->count();
        $totalAdmins = $admins->count();
        $totalNormalUsers = $normalUsers->count();
        $totalUsers = $users->count();

        // =========================================================================
        // 2. REAL FINANCIAL & COMMERCE METRICS (SYNCED WITH STUDENT FEE SERVICE)
        // =========================================================================
        $feeSummary = \App\Services\Tenant\StudentFeeService::getInstituteFeeSummary();
        $paidFeeSum = (float) $feeSummary['collected_fees'];
        $pendingFeeSum = (float) $feeSummary['pending_fees'];
        $paidOrderSum = (float) Order::where('payment_status', 'paid')->sum('grand_total');
        $totalRevenue = $paidFeeSum + $paidOrderSum;

        $unpaidOrderSum = (float) Order::where('payment_status', '!=', 'paid')
            ->whereNull('deleted_at')
            ->sum('grand_total');

        $totalPending = max(0, $pendingFeeSum) + max(0, $unpaidOrderSum);

        $totalOrders = Order::count();
        $paidOrders = Order::where('payment_status', 'paid')->count();
        $unpaidOrders = Order::where('payment_status', '!=', 'paid')->whereNull('deleted_at')->count();

        // Books Sales vs Course Sales Breakdown
        $bookSalesSum = (float) OrderItem::where('purchasable_type', 'like', '%Book%')
            ->whereHas('order', function ($q) {
                $q->where('payment_status', 'paid');
            })->sum('total_price');

        $bookCopiesSold = (int) OrderItem::where('purchasable_type', 'like', '%Book%')
            ->whereHas('order', function ($q) {
                $q->where('payment_status', 'paid');
            })->sum('quantity');

        $courseSalesSum = (float) OrderItem::where('purchasable_type', 'like', '%Course%')
            ->whereHas('order', function ($q) {
                $q->where('payment_status', 'paid');
            })->sum('total_price');

        $courseSeatsSold = (int) OrderItem::where('purchasable_type', 'like', '%Course%')
            ->whereHas('order', function ($q) {
                $q->where('payment_status', 'paid');
            })->sum('quantity');

        // Backward compatibility
        $revenue = $totalRevenue;
        $pendingFees = $pendingFeeSum;

        // =========================================================================
        // 3. REAL ACADEMICS, CURRICULUM & LIBRARY METRICS
        // =========================================================================
        $courses = Course::all();
        $totalCourses = $courses->count();
        $publishedCourses = Course::where('status', 'published')->orWhere('status', 1)->count();
        $totalEnrollments = CourseEnrollment::count();

        $books = Book::all();
        $totalBooks = $books->count();
        $bookCategoriesCount = BookCategory::count();

        $totalAdmissions = Admission::count();
        $pendingAdmissions = Admission::where('status', 'pending')->count();
        $approvedAdmissions = Admission::where('status', 'approved')->count();
        $rejectedAdmissions = Admission::where('status', 'rejected')->count();

        $totalInquiries = Inquiry::count();

        // =========================================================================
        // 4. REAL 6-MONTH HISTORICAL TIME-SERIES (Charts)
        // =========================================================================
        $chartMonths = [];
        $chartStudentRegistrations = [];
        $chartUserGrowth = [];
        $chartRevenue = [];
        $chartFeeCollections = [];
        $chartStoreSales = [];

        for ($i = 5; $i >= 0; $i--) {
            $point = now()->subMonths($i);
            $chartMonths[] = $point->format('M');
            $startOfMonth = $point->copy()->startOfMonth();
            $endOfMonth = $point->copy()->endOfMonth();

            // Monthly student signups
            $chartStudentRegistrations[] = User::where('role', 'student')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();

            // Monthly total user registrations
            $chartUserGrowth[] = User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

            // Monthly collected fee payments
            $mFees = (float) FeePayments::where('status', 'paid')
                ->where(function ($q) use ($startOfMonth, $endOfMonth) {
                    $q->whereBetween('payment_date', [$startOfMonth, $endOfMonth])
                        ->orWhere(function ($q2) use ($startOfMonth, $endOfMonth) {
                            $q2->whereNull('payment_date')
                                ->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
                        });
                })->sum('amount_paid');
            $chartFeeCollections[] = round($mFees, 2);

            // Monthly store orders
            $mOrders = (float) Order::where('payment_status', 'paid')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('grand_total');
            $chartStoreSales[] = round($mOrders, 2);

            // Total monthly revenue collected
            $chartRevenue[] = round($mFees + $mOrders, 2);
        }

        $revenueChartData = [
            'labels' => $chartMonths,
            'data'   => $chartRevenue,
        ];

        // Role Distribution Data
        $roleDistribution = [
            'labels' => ['Students', 'Teachers', 'Staff', 'Admins', 'Normal Users'],
            'data'   => [$totalStudents, $totalTeachers, $totalStaff, $totalAdmins, $totalNormalUsers],
        ];

        // =========================================================================
        // 5. REAL ACTIVITY FEEDS & RECENT RECORDS
        // =========================================================================
        $recentStudents = User::where('role', 'student')
            ->latest('id')
            ->take(6)
            ->get();

        $recentTeachers = User::where('role', 'teacher')
            ->latest('id')
            ->take(6)
            ->get();

        $recentNormalUsers = User::where(function ($q) {
            $q->where('role', 'user')
              ->orWhere('role', 'users')
              ->orWhereNotIn('role', ['student', 'teacher', 'staff', 'admin'])
              ->orWhereNull('role');
        })->latest('id')->take(6)->get();

        $recentAdmissions = Admission::with(['user', 'classCourse', 'subject'])
            ->latest('id')
            ->take(5)
            ->get();

        $recentOrders = Order::with('user')
            ->latest('id')
            ->take(6)
            ->get();

        $recentCourses = Course::latest('id')
            ->take(4)
            ->get();

        $recentBooks = Book::latest('id')
            ->take(4)
            ->get();

        return view('tenant.admin.dashboard.index', compact(
            // Users Data
            'students',
            'teachers',
            'staff',
            'admins',
            'normalUsers',
            'users',
            'totalStudents',
            'activeStudents',
            'inactiveStudents',
            'totalTeachers',
            'activeTeachers',
            'totalStaff',
            'totalAdmins',
            'totalNormalUsers',
            'totalUsers',

            // Financial Data
            'totalRevenue',
            'paidFeeSum',
            'paidOrderSum',
            'pendingFeeSum',
            'unpaidOrderSum',
            'totalPending',
            'totalOrders',
            'paidOrders',
            'unpaidOrders',
            'bookSalesSum',
            'bookCopiesSold',
            'courseSalesSum',
            'courseSeatsSold',
            'revenue',
            'pendingFees',

            // Academic & Content Data
            'courses',
            'totalCourses',
            'publishedCourses',
            'totalEnrollments',
            'books',
            'totalBooks',
            'bookCategoriesCount',
            'totalAdmissions',
            'pendingAdmissions',
            'approvedAdmissions',
            'rejectedAdmissions',
            'totalInquiries',

            // Charts Data
            'chartMonths',
            'chartStudentRegistrations',
            'chartUserGrowth',
            'chartRevenue',
            'chartFeeCollections',
            'chartStoreSales',
            'revenueChartData',
            'roleDistribution',

            // Activity Feeds
            'recentStudents',
            'recentTeachers',
            'recentNormalUsers',
            'recentAdmissions',
            'recentOrders',
            'recentCourses',
            'recentBooks'
        ));
    }
}
