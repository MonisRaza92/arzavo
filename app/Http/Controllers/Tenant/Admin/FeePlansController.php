<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\User;
use App\Models\Tenant\AcademicCategory;
use App\Models\Tenant\ClassCourse;
use App\Models\Tenant\Subject;
use App\Models\Tenant\Batch;
use App\Models\Tenant\FeeStructure;
use App\Models\Tenant\FeePlans;
use App\Models\Tenant\FeePayments;
use App\Services\Tenant\StudentFeeService;

class FeePlansController extends Controller
{
    /**
     * Display the Master Fee Plans & Student Fees Ledger.
     */
    public function index(Request $request)
    {
        // 1. All Hierarchy Fee Structures
        $feeStructures = FeeStructure::with(['category', 'classCourse', 'subject', 'batch'])
            ->orderByRaw("FIELD(scope, 'category', 'class', 'subject', 'batch')")
            ->latest('id')
            ->get();

        // 2. Academic data for modal selectors
        $categories = AcademicCategory::where('status', true)
            ->with(['classCourses.subjects.batches.teacher'])
            ->orderBy('order')
            ->get();

        // 3. Students & dynamic fee resolution
        $students = User::where('role', 'student')
            ->with([
                'academicCategory',
                'class',
                'subject',
                'batch.teacher',
                'feePlans',
                'feePayments'
            ])
            ->latest()
            ->get();

        $studentsData = $students->map(function ($s) {
            $fee = StudentFeeService::calculateStudentFee($s);
            $plan = $s->feePlans->sortByDesc('id')->first();

            if ($fee['total_accrued'] <= 0 && $fee['total_paid'] <= 0) {
                $status = 'zero_dues';
            } elseif ($fee['total_due'] <= 0) {
                $status = 'paid';
            } elseif ($fee['total_paid'] > 0) {
                $status = 'partial';
            } else {
                $status = 'unpaid';
            }

            return [
                'id' => $s->id,
                'fname' => $s->fname,
                'lname' => $s->lname,
                'username' => $s->username,
                'email' => $s->email,
                'number' => $s->number,
                'parent_phone' => $s->parent_phone,
                'roll_number' => $s->roll_number,
                'profile_picture' => $s->profile_picture,
                'academic_category_id' => $s->academic_category_id,
                'class_id' => $s->class_id,
                'subject_id' => $s->subject_id,
                'batch_id' => $s->batch_id,
                'category_name' => $s->academicCategory ? $s->academicCategory->name : 'N/A',
                'class_name' => $s->class ? $s->class->name : 'N/A',
                'subject_name' => $s->subject ? $s->subject->name : 'N/A',
                'batch_name' => $s->batch ? $s->batch->name : 'N/A',
                'effective_plan' => [
                    'amount' => $fee['cycle_amount'],
                    'plan_type' => $fee['plan_type'],
                    'due_day' => $fee['due_day'],
                    'source' => $fee['source'],
                    'source_label' => $fee['source_label'],
                    'is_override' => $fee['is_override'],
                    'override_reason' => $fee['override_reason'],
                    'has_plan' => $fee['has_plan'],
                ],
                'total_fee' => $fee['total_accrued'],
                'paid_fee' => $fee['total_paid'],
                'due_fee' => $fee['total_due'],
                'cycles_count' => $fee['cycles_count'],
                'payment_status' => $status,
            ];
        });

        // 4. Student Payment Status Distribution & Recovery Percentages
        $totalStudents = $studentsData->count();
        $paidCount = 0;
        $partialCount = 0;
        $unpaidCount = 0;
        $zeroDuesCount = 0;

        foreach ($studentsData as $st) {
            if ($st['payment_status'] === 'zero_dues') {
                $zeroDuesCount++;
            } elseif ($st['payment_status'] === 'paid') {
                $paidCount++;
            } elseif ($st['payment_status'] === 'partial') {
                $partialCount++;
            } else {
                $unpaidCount++;
            }
        }

        $paidPercent = $totalStudents > 0 ? round(($paidCount / $totalStudents) * 100, 1) : 0;
        $partialPercent = $totalStudents > 0 ? round(($partialCount / $totalStudents) * 100, 1) : 0;
        $unpaidPercent = $totalStudents > 0 ? round(($unpaidCount / $totalStudents) * 100, 1) : 0;
        $zeroDuesPercent = $totalStudents > 0 ? round(($zeroDuesCount / $totalStudents) * 100, 1) : 0;

        // 5. Monthly Accrual & Collection Velocity (Past 6 Months)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthKey = $monthDate->format('Y-m');
            $monthLabel = $monthDate->format('M Y');

            $collectedInMonth = (float) FeePayments::where('status', 'paid')
                ->where(function ($q) use ($monthKey) {
                    $q->where('payment_date', 'like', "{$monthKey}%")
                      ->orWhere('created_at', 'like', "{$monthKey}%");
                })
                ->sum('amount_paid');

            $invoicedInMonth = (float) $studentsData->filter(function ($s) {
                return $s['effective_plan']['has_plan'];
            })->sum('effective_plan.amount');

            $monthlyTrend[] = [
                'label' => $monthLabel,
                'invoiced' => round($invoicedInMonth, 2),
                'collected' => round($collectedInMonth, 2),
            ];
        }

        // 6. Category / Stream-wise Revenue distribution
        $categoryStats = $categories->map(function ($cat) use ($studentsData) {
            $catStudents = $studentsData->where('academic_category_id', $cat->id);
            $billed = (float) $catStudents->sum('total_fee');
            $paid = (float) $catStudents->sum('paid_fee');
            $due = (float) $catStudents->sum('due_fee');
            $ratio = $billed > 0 ? round(($paid / $billed) * 100, 1) : 0;

            return [
                'id' => $cat->id,
                'name' => $cat->name,
                'students_count' => $catStudents->count(),
                'billed' => $billed,
                'paid' => $paid,
                'due' => $due,
                'ratio' => $ratio,
            ];
        });

        // 7. Global Academy Fee Summary
        $instituteSummary = StudentFeeService::getInstituteFeeSummary();
        $totalPlanned = $instituteSummary['total_fees'];
        $totalCollected = $instituteSummary['collected_fees'];
        $totalPending = $instituteSummary['pending_fees'];
        $collectionRatio = $instituteSummary['collection_ratio'];
        $activeStructuresCount = $feeStructures->where('is_active', true)->count();
        $overrideCount = $studentsData->where('effective_plan.is_override', true)->count();

        $studentsWithPlans = $studentsData->filter(fn($s) => $s['effective_plan']['has_plan']);
        $avgFee = $studentsWithPlans->count() > 0 ? round($studentsWithPlans->avg(fn($s) => $s['effective_plan']['amount']), 2) : 0;
        $totalDefaultersDue = (float) $studentsData->whereIn('payment_status', ['unpaid', 'partial'])->sum('due_fee');

        $analytics = [
            'total_students' => $totalStudents,
            'paid_count' => $paidCount,
            'paid_percent' => $paidPercent,
            'partial_count' => $partialCount,
            'partial_percent' => $partialPercent,
            'unpaid_count' => $unpaidCount,
            'unpaid_percent' => $unpaidPercent,
            'zero_dues_count' => $zeroDuesCount,
            'zero_dues_percent' => $zeroDuesPercent,
            'average_fee' => $avgFee,
            'total_defaulters_due' => $totalDefaultersDue,
            'monthly_trend' => $monthlyTrend,
            'category_stats' => $categoryStats,
        ];

        return view('tenant.admin.students.fees.index', compact(
            'feeStructures',
            'categories',
            'students',
            'studentsData',
            'totalPlanned',
            'totalCollected',
            'totalPending',
            'collectionRatio',
            'activeStructuresCount',
            'overrideCount',
            'analytics'
        ));
    }

    /**
     * Store a new Hierarchy Master Fee Plan.
     */
    public function storePlan(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'scope' => 'required|in:category,class,subject,batch',
            'academic_category_id' => 'nullable|integer|exists:academic_categories,id',
            'class_id' => 'nullable|integer|exists:class_courses,id',
            'subject_id' => 'nullable|integer|exists:subjects,id',
            'batch_id' => 'nullable|integer|exists:batches,id',
            'plan_type' => 'required|in:monthly,quarterly,yearly,onetime',
            'amount' => 'required|numeric|min:0',
            'due_day' => 'required|integer|between:1,31',
            'start_date' => 'nullable|date',
            'description' => 'nullable|string|max:500',
        ]);

        $scope = $validated['scope'];
        $categoryId = !empty($validated['academic_category_id']) ? (int)$validated['academic_category_id'] : null;
        $classId = !empty($validated['class_id']) ? (int)$validated['class_id'] : null;
        $subjectId = !empty($validated['subject_id']) ? (int)$validated['subject_id'] : null;
        $batchId = !empty($validated['batch_id']) ? (int)$validated['batch_id'] : null;

        // Auto-assign scope targets to prevent orphan records
        if ($scope === 'category' && empty($categoryId)) {
            return redirect()->back()->with('error', 'Please select a Category for this category-level plan.');
        }
        if ($scope === 'class' && empty($classId)) {
            return redirect()->back()->with('error', 'Please select a Class for this class-level plan.');
        }
        if ($scope === 'subject' && empty($subjectId)) {
            return redirect()->back()->with('error', 'Please select a Subject for this subject-level plan.');
        }
        if ($scope === 'batch' && empty($batchId)) {
            return redirect()->back()->with('error', 'Please select a Batch for this batch-level plan.');
        }

        // Clean hierarchy targets according to scope
        if ($scope === 'category') {
            $classId = null;
            $subjectId = null;
            $batchId = null;
        } elseif ($scope === 'class') {
            $subjectId = null;
            $batchId = null;
        } elseif ($scope === 'subject') {
            $batchId = null;
        }

        FeeStructure::create([
            'title' => !empty($validated['title']) ? $validated['title'] : null,
            'scope' => $scope,
            'academic_category_id' => $categoryId,
            'class_id' => $classId,
            'subject_id' => $subjectId,
            'batch_id' => $batchId,
            'plan_type' => $validated['plan_type'],
            'amount' => (float) $validated['amount'],
            'due_day' => (int) $validated['due_day'],
            'start_date' => !empty($validated['start_date']) ? $validated['start_date'] : null,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
            'description' => !empty($validated['description']) ? $validated['description'] : null,
        ]);

        return redirect()->back()->with('success', 'Master Fee Plan created successfully! Eligible students will now inherit this fee.');
    }

    /**
     * Update an existing Hierarchy Master Fee Plan.
     */
    public function updatePlan(Request $request, $id)
    {
        $plan = FeeStructure::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'scope' => 'required|in:category,class,subject,batch',
            'academic_category_id' => 'nullable|integer',
            'class_id' => 'nullable|integer',
            'subject_id' => 'nullable|integer',
            'batch_id' => 'nullable|integer',
            'plan_type' => 'required|in:monthly,quarterly,yearly,onetime',
            'amount' => 'required|numeric|min:0',
            'due_day' => 'required|integer|between:1,31',
            'start_date' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'description' => 'nullable|string|max:500',
        ]);

        $scope = $validated['scope'];
        $categoryId = !empty($validated['academic_category_id']) ? (int)$validated['academic_category_id'] : null;
        $classId = !empty($validated['class_id']) ? (int)$validated['class_id'] : null;
        $subjectId = !empty($validated['subject_id']) ? (int)$validated['subject_id'] : null;
        $batchId = !empty($validated['batch_id']) ? (int)$validated['batch_id'] : null;

        if ($scope === 'category') {
            $classId = null;
            $subjectId = null;
            $batchId = null;
        } elseif ($scope === 'class') {
            $subjectId = null;
            $batchId = null;
        } elseif ($scope === 'subject') {
            $batchId = null;
        }

        $plan->update([
            'title' => !empty($validated['title']) ? $validated['title'] : $plan->title,
            'scope' => $scope,
            'academic_category_id' => $categoryId,
            'class_id' => $classId,
            'subject_id' => $subjectId,
            'batch_id' => $batchId,
            'plan_type' => $validated['plan_type'],
            'amount' => (float) $validated['amount'],
            'due_day' => (int) $validated['due_day'],
            'start_date' => !empty($validated['start_date']) ? $validated['start_date'] : $plan->start_date,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : $plan->is_active,
            'description' => !empty($validated['description']) ? $validated['description'] : null,
        ]);

        return redirect()->back()->with('success', 'Fee plan updated successfully. Inherited fees adjusted across all matching students.');
    }

    /**
     * Delete a Hierarchy Master Fee Plan.
     */
    public function deletePlan($id)
    {
        $plan = FeeStructure::findOrFail($id);
        $plan->delete();

        return redirect()->back()->with('success', 'Fee plan deleted. Students have cascaded back to their parent tier fee.');
    }

    /**
     * Set or reset a Custom Student Fee Override.
     */
    public function saveStudentOverride(Request $request, $studentId)
    {
        $student = User::where('role', 'student')->findOrFail($studentId);

        // Reset to Inherit from Hierarchy
        if ($request->input('action') === 'reset') {
            $existing = FeePlans::where('student_id', $student->id)->first();
            if ($existing) {
                $existing->delete();
            }
            return redirect()->back()->with('success', "Student {$student->fname}'s fee reset to inherit automatically from curriculum hierarchy.");
        }

        // Apply Custom Override
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'plan_type' => 'required|in:monthly,quarterly,yearly,onetime',
            'due_day' => 'required|integer|between:1,31',
            'override_reason' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
        ]);

        FeePlans::updateOrCreate(
            ['student_id' => $student->id],
            [
                'is_override' => true,
                'override_reason' => !empty($validated['override_reason']) ? $validated['override_reason'] : 'Custom Exception / Scholarship',
                'amount' => (float) $validated['amount'],
                'plan_type' => $validated['plan_type'],
                'due_day' => (int) $validated['due_day'],
                'start_date' => !empty($validated['start_date']) ? $validated['start_date'] : now()->toDateString(),
            ]
        );

        return redirect()->back()->with('success', "Custom fee override applied for {$student->fname} successfully.");
    }
}
