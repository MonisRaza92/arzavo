<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\User;
use App\Models\Tenant\Order;
use App\Models\Tenant\AcademicCategory;
use App\Models\Tenant\Admission;
use App\Models\Tenant\FeePlans;

class UsersController extends Controller
{
    public function adminUsers()
    {
        $users = User::where('role', 'user')
            ->with(['orders.items', 'entitlements', 'enrolledCourses'])
            ->latest()
            ->get();

        $payingUsersCount = $users->filter(function ($u) {
            return ($u->orders && $u->orders->where('payment_status', 'paid')->count() > 0)
                || ($u->entitlements && $u->entitlements->count() > 0);
        })->count();

        $totalUserSales = Order::where('payment_status', 'paid')->sum('grand_total');

        // Categories with classes, subjects & batches for Promote modal cascading selector
        $categories = AcademicCategory::where('status', true)
            ->with([
                'classCourses' => function ($q) {
                    $q->where('status', true)->orderBy('order')->orderBy('id');
                },
                'classCourses.subjects' => function ($q) {
                    $q->where('status', true)->orderBy('order')->orderBy('id');
                },
                'classCourses.subjects.batches' => function ($q) {
                    $q->where('status', true)->with('teacher')->orderBy('order')->orderBy('id');
                }
            ])
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        // Map structured user records for front-end consumption
        $usersData = $users->map(function ($u) {
            $paidOrders = $u->orders ? $u->orders->where('payment_status', 'paid') : collect();
            return [
                'id' => $u->id,
                'fname' => $u->fname,
                'lname' => $u->lname,
                'username' => $u->username,
                'email' => $u->email,
                'number' => $u->number,
                'status' => $u->status,
                'profile_picture' => $u->profile_picture,
                'paid_orders_count' => $paidOrders->count(),
                'total_spent' => (float) $paidOrders->sum('grand_total'),
                'entitlements_count' => $u->entitlements ? $u->entitlements->count() : 0,
                'joined_formatted' => $u->created_at ? $u->created_at->format('M d, Y') : 'N/A',
            ];
        });

        return view('tenant.admin.users.users', compact('users', 'usersData', 'payingUsersCount', 'totalUserSales', 'categories'));
    }

    /**
     * Promote Normal User to Student with Academic & Fee setup
     */
    public function promoteToStudent(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:users,id',
            'academic_category_id' => 'nullable|exists:academic_categories,id',
            'class_id' => 'nullable|exists:class_courses,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'batch_id' => 'nullable|exists:batches,id',
            'aadhaar_number' => 'nullable|string|max:30',
            'fee_plan_type' => 'nullable|string|in:monthly,quarterly,yearly,onetime',
            'fee_amount' => 'nullable|numeric|min:0',
            'fee_due_day' => 'nullable|integer|min:1|max:31',
        ]);

        $user = User::findOrFail($request->input('id'));

        $user->update([
            'role' => 'student',
            'admission_status' => 'approved',
            'enrolled_at' => now(),
            'academic_category_id' => $request->academic_category_id,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'batch_id' => $request->batch_id,
            'aadhaar_number' => $request->aadhaar_number ?: $user->aadhaar_number,
        ]);

        // Create approved Admission record
        Admission::create([
            'user_id' => $user->id,
            'academic_category_id' => $request->academic_category_id,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'aadhaar_number' => $request->aadhaar_number ?: $user->aadhaar_number,
            'status' => 'approved',
            'applied_at' => now(),
            'approved_at' => now(),
            'admin_remarks' => 'Promoted to student from Users directory',
        ]);

        // Setup Fee Plan if amount provided
        if ($request->filled('fee_amount') && (float) $request->fee_amount > 0) {
            FeePlans::updateOrCreate(
                ['student_id' => $user->id],
                [
                    'plan_type' => $request->fee_plan_type ?? 'monthly',
                    'amount' => $request->fee_amount,
                    'start_date' => now()->toDateString(),
                    'due_day' => $request->fee_due_day ?? 10,
                ]
            );
        }

        return redirect()->back()->with('success', "User '{$user->fname} {$user->lname}' has been successfully promoted to Student!");
    }

    public function updateUserRole(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:users,id',
        ]);

        $user = User::findOrFail($request->input('id'));
        $user->update([
            'role' => 'student',
            'admission_status' => 'approved',
            'enrolled_at' => $user->enrolled_at ?: now(),
        ]);

        return redirect()->back()->with('success', "User '{$user->fname}' role changed to Student");
    }

    public function updateUserStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:users,id',
        ]);

        $user = User::findOrFail($request->input('id'));
        $newStatus = $user->status === 'active' ? 'suspended' : 'active';
        $user->update(['status' => $newStatus]);

        return redirect()->back()->with('success', "User '{$user->fname}' status updated to {$newStatus}");
    }
}
