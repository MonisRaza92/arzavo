<?php

namespace App\Services\Tenant;

use App\Models\Tenant\User;
use App\Models\Tenant\FeePlans;
use App\Models\Tenant\FeePayments;
use Carbon\Carbon;

class StudentFeeService
{
    /**
     * Resolve effective fee plan/structure for a student.
     * Priority:
     * 1. Student Custom Override (fee_plans with is_override = true)
     * 2. Batch Master Plan (fee_structures with batch_id = student.batch_id)
     * 3. Subject Master Plan (fee_structures with subject_id = student.subject_id)
     * 4. Class Master Plan (fee_structures with class_id = student.class_id)
     * 5. Category Master Plan (fee_structures with academic_category_id = student.academic_category_id)
     * 6. Student direct fee plan (fallback for legacy records)
     */
    public static function resolveEffectivePlan(User $student): array
    {
        // 1. Check for Student Custom Override
        $directPlan = $student->feePlans ? $student->feePlans->sortByDesc('id')->first() : null;
        if ($directPlan && $directPlan->is_override) {
            return [
                'has_plan' => true,
                'source' => 'override',
                'source_label' => 'Custom Override' . ($directPlan->override_reason ? ' (' . $directPlan->override_reason . ')' : ''),
                'plan_type' => $directPlan->plan_type ?: 'monthly',
                'amount' => (float) $directPlan->amount,
                'due_day' => (int) ($directPlan->due_day ?: 1),
                'start_date' => $directPlan->start_date ?: ($student->created_at ? $student->created_at->format('Y-m-d') : now()->format('Y-m-d')),
                'is_override' => true,
                'override_reason' => $directPlan->override_reason,
                'structure_id' => null,
            ];
        }

        // 2. Query Hierarchy Structures (Batch -> Subject -> Class -> Category)
        $batchId = $student->batch_id;
        $subjectId = $student->subject_id;
        $classId = $student->class_id;
        $categoryId = $student->academic_category_id;

        // Level 4: Batch Level Fee Plan
        if ($batchId) {
            $batchPlan = \App\Models\Tenant\FeeStructure::where('scope', 'batch')
                ->where('batch_id', $batchId)
                ->where('is_active', true)
                ->latest()
                ->first();
            if ($batchPlan) {
                return [
                    'has_plan' => true,
                    'source' => 'batch',
                    'source_label' => 'Inherited: Batch (' . ($student->batch?->name ?? 'Batch #' . $batchId) . ')',
                    'plan_type' => $batchPlan->plan_type ?: 'monthly',
                    'amount' => (float) $batchPlan->amount,
                    'due_day' => (int) ($batchPlan->due_day ?: 1),
                    'start_date' => $batchPlan->start_date ?: ($student->created_at ? $student->created_at->format('Y-m-d') : now()->format('Y-m-d')),
                    'is_override' => false,
                    'override_reason' => null,
                    'structure_id' => $batchPlan->id,
                ];
            }
        }

        // Level 3: Subject Level Fee Plan
        if ($subjectId) {
            $subjectPlan = \App\Models\Tenant\FeeStructure::where('scope', 'subject')
                ->where('subject_id', $subjectId)
                ->where('is_active', true)
                ->latest()
                ->first();
            if ($subjectPlan) {
                return [
                    'has_plan' => true,
                    'source' => 'subject',
                    'source_label' => 'Inherited: Subject (' . ($student->subject?->name ?? 'Subject #' . $subjectId) . ')',
                    'plan_type' => $subjectPlan->plan_type ?: 'monthly',
                    'amount' => (float) $subjectPlan->amount,
                    'due_day' => (int) ($subjectPlan->due_day ?: 1),
                    'start_date' => $subjectPlan->start_date ?: ($student->created_at ? $student->created_at->format('Y-m-d') : now()->format('Y-m-d')),
                    'is_override' => false,
                    'override_reason' => null,
                    'structure_id' => $subjectPlan->id,
                ];
            }
        }

        // Level 2: Class Level Fee Plan
        if ($classId) {
            $classPlan = \App\Models\Tenant\FeeStructure::where('scope', 'class')
                ->where('class_id', $classId)
                ->where('is_active', true)
                ->latest()
                ->first();
            if ($classPlan) {
                return [
                    'has_plan' => true,
                    'source' => 'class',
                    'source_label' => 'Inherited: Class (' . ($student->class?->name ?? 'Class #' . $classId) . ')',
                    'plan_type' => $classPlan->plan_type ?: 'monthly',
                    'amount' => (float) $classPlan->amount,
                    'due_day' => (int) ($classPlan->due_day ?: 1),
                    'start_date' => $classPlan->start_date ?: ($student->created_at ? $student->created_at->format('Y-m-d') : now()->format('Y-m-d')),
                    'is_override' => false,
                    'override_reason' => null,
                    'structure_id' => $classPlan->id,
                ];
            }
        }

        // Level 1: Category Level Fee Plan
        if ($categoryId) {
            $catPlan = \App\Models\Tenant\FeeStructure::where('scope', 'category')
                ->where('academic_category_id', $categoryId)
                ->where('is_active', true)
                ->latest()
                ->first();
            if ($catPlan) {
                return [
                    'has_plan' => true,
                    'source' => 'category',
                    'source_label' => 'Inherited: Category (' . ($student->academicCategory?->name ?? 'Category #' . $categoryId) . ')',
                    'plan_type' => $catPlan->plan_type ?: 'monthly',
                    'amount' => (float) $catPlan->amount,
                    'due_day' => (int) ($catPlan->due_day ?: 1),
                    'start_date' => $catPlan->start_date ?: ($student->created_at ? $student->created_at->format('Y-m-d') : now()->format('Y-m-d')),
                    'is_override' => false,
                    'override_reason' => null,
                    'structure_id' => $catPlan->id,
                ];
            }
        }

        // 3. Fallback to Direct Plan (if student has legacy fee_plans record)
        if ($directPlan) {
            return [
                'has_plan' => true,
                'source' => 'direct',
                'source_label' => 'Direct Student Plan',
                'plan_type' => $directPlan->plan_type ?: 'monthly',
                'amount' => (float) $directPlan->amount,
                'due_day' => (int) ($directPlan->due_day ?: 1),
                'start_date' => $directPlan->start_date ?: ($student->created_at ? $student->created_at->format('Y-m-d') : now()->format('Y-m-d')),
                'is_override' => false,
                'override_reason' => null,
                'structure_id' => null,
            ];
        }

        // 4. Default: No Plan Assigned
        return [
            'has_plan' => false,
            'source' => 'none',
            'source_label' => 'No Fee Plan Assigned',
            'plan_type' => 'monthly',
            'amount' => 0.0,
            'due_day' => 1,
            'start_date' => null,
            'is_override' => false,
            'override_reason' => null,
            'structure_id' => null,
        ];
    }

    /**
     * Calculate cumulative fee metrics for a specific student.
     * Accrues monthly/recurring cycles from start_date to current month so unpaid dues carry over.
     */
    public static function calculateStudentFee(User $student): array
    {
        $effective = self::resolveEffectivePlan($student);
        $payments = $student->feePayments ? $student->feePayments : collect();
        $paidFee = (float) $payments->where('status', 'paid')->sum('amount_paid');

        if (!$effective['has_plan']) {
            $totalAccrued = (float) $payments->sum('amount');
            if ($totalAccrued < $paidFee) {
                $totalAccrued = $paidFee;
            }
            $dueFee = max(0, $totalAccrued - $paidFee);

            return [
                'has_plan' => false,
                'plan_type' => 'none',
                'cycle_amount' => 0.0,
                'cycles_count' => 0,
                'total_accrued' => round($totalAccrued, 2),
                'total_paid' => round($paidFee, 2),
                'total_due' => round($dueFee, 2),
                'due_day' => null,
                'next_due_date' => null,
                'source' => 'none',
                'source_label' => 'No Fee Plan Assigned',
                'is_override' => false,
                'override_reason' => null,
                'structure_id' => null,
                'statement' => [],
            ];
        }

        $cycleAmount = (float) $effective['amount'];
        $planType = strtolower($effective['plan_type'] ?: 'monthly');
        $dueDay = (int) ($effective['due_day'] ?: 1);

        $startDate = $effective['start_date']
            ? Carbon::parse($effective['start_date'])->startOfDay()
            : ($student->created_at ? Carbon::parse($student->created_at)->startOfDay() : now()->startOfDay());

        $endDate = null;
        $directPlan = $student->feePlans ? $student->feePlans->sortByDesc('id')->first() : null;
        if ($directPlan && $directPlan->end_date) {
            $endDate = Carbon::parse($directPlan->end_date)->endOfDay();
        }
        $now = now();

        $statement = [];
        $cyclesCount = 0;

        if ($planType === 'onetime') {
            $cyclesCount = 1;
            $dueDate = $startDate->copy()->day(min($dueDay, $startDate->daysInMonth));
            $statement[] = [
                'cycle_name' => 'One-Time Enrollment Fee',
                'period' => $startDate->format('M Y'),
                'due_date' => $dueDate->format('Y-m-d'),
                'billed' => $cycleAmount,
                'is_current' => true,
            ];
        } elseif ($planType === 'yearly') {
            $curr = $startDate->copy()->startOfYear();
            $target = $now->copy()->startOfYear();
            if ($endDate && $target->gt($endDate)) {
                $target = $endDate->copy()->startOfYear();
            }

            while ($curr->lte($target)) {
                $cyclesCount++;
                $dueDate = $curr->copy()->month(min(12, $startDate->month))->day(min($dueDay, 28));
                $statement[] = [
                    'cycle_name' => 'Annual Tuition (' . $curr->format('Y') . ')',
                    'period' => $curr->format('Y'),
                    'due_date' => $dueDate->format('Y-m-d'),
                    'billed' => $cycleAmount,
                    'is_current' => $curr->year === $now->year,
                ];
                $curr->addYear();
            }
        } elseif ($planType === 'quarterly') {
            $curr = $startDate->copy()->startOfMonth();
            $target = $now->copy()->startOfMonth();
            if ($endDate && $target->gt($endDate)) {
                $target = $endDate->copy()->startOfMonth();
            }

            while ($curr->lte($target)) {
                $cyclesCount++;
                $quarterEnd = $curr->copy()->addMonths(2);
                $dueDate = $curr->copy()->day(min($dueDay, $curr->daysInMonth));
                $statement[] = [
                    'cycle_name' => 'Quarterly (' . $curr->format('M') . ' - ' . $quarterEnd->format('M Y') . ')',
                    'period' => $curr->format('M Y'),
                    'due_date' => $dueDate->format('Y-m-d'),
                    'billed' => $cycleAmount,
                    'is_current' => ($now->between($curr, $quarterEnd->copy()->endOfMonth())),
                ];
                $curr->addMonths(3);
            }
        } else {
            // Default: Monthly recurring
            $curr = $startDate->copy()->startOfMonth();
            $target = $now->copy()->startOfMonth();
            if ($endDate && $target->gt($endDate)) {
                $target = $endDate->copy()->startOfMonth();
            }

            while ($curr->lte($target)) {
                $cyclesCount++;
                $dueDate = $curr->copy()->day(min($dueDay, $curr->daysInMonth));
                $statement[] = [
                    'cycle_name' => 'Monthly Tuition (' . $curr->format('F Y') . ')',
                    'period' => $curr->format('M Y'),
                    'due_date' => $dueDate->format('Y-m-d'),
                    'billed' => $cycleAmount,
                    'is_current' => ($curr->format('Y-m') === $now->format('Y-m')),
                ];
                $curr->addMonth();
            }
        }

        if ($cyclesCount === 0) {
            $cyclesCount = 1;
            $statement[] = [
                'cycle_name' => ucfirst($planType) . ' Tuition (' . $startDate->format('M Y') . ')',
                'period' => $startDate->format('M Y'),
                'due_date' => $startDate->format('Y-m-d'),
                'billed' => $cycleAmount,
                'is_current' => true,
            ];
        }

        $totalAccrued = $cyclesCount * $cycleAmount;
        $totalDue = max(0, $totalAccrued - $paidFee);

        // Distribute paid fees chronologically across statement cycles
        $remainingPaid = $paidFee;
        $cumulativeBilled = 0;
        foreach ($statement as &$cycle) {
            $cumulativeBilled += $cycle['billed'];
            $cycle['cumulative_billed'] = $cumulativeBilled;

            if ($remainingPaid >= $cycle['billed']) {
                $cycle['paid'] = $cycle['billed'];
                $cycle['balance'] = 0.0;
                $cycle['status'] = 'paid';
                $remainingPaid -= $cycle['billed'];
            } elseif ($remainingPaid > 0) {
                $cycle['paid'] = $remainingPaid;
                $cycle['balance'] = round($cycle['billed'] - $remainingPaid, 2);
                $cycle['status'] = 'partial';
                $remainingPaid = 0;
            } else {
                $cycle['paid'] = 0.0;
                $cycle['balance'] = $cycle['billed'];
                $cycleDueDate = Carbon::parse($cycle['due_date']);
                $cycle['status'] = ($cycleDueDate->lt($now)) ? 'overdue' : 'due';
            }
        }
        unset($cycle);

        // Next due date calculation
        $nextDueDate = null;
        if ($planType === 'monthly') {
            $nextDueCarbon = now()->day <= $dueDay
                ? now()->day($dueDay)
                : now()->addMonth()->day(min($dueDay, now()->addMonth()->daysInMonth));
            $nextDueDate = $nextDueCarbon->format('Y-m-d');
        }

        return [
            'has_plan' => true,
            'plan_type' => $planType,
            'cycle_amount' => round($cycleAmount, 2),
            'cycles_count' => $cyclesCount,
            'total_accrued' => round($totalAccrued, 2),
            'total_paid' => round($paidFee, 2),
            'total_due' => round($totalDue, 2),
            'due_day' => $dueDay,
            'next_due_date' => $nextDueDate,
            'source' => $effective['source'] ?? 'direct',
            'source_label' => $effective['source_label'] ?? 'Standard Plan',
            'is_override' => $effective['is_override'] ?? false,
            'override_reason' => $effective['override_reason'] ?? null,
            'structure_id' => $effective['structure_id'] ?? null,
            'statement' => array_reverse($statement), // newest first
        ];
    }

    /**
     * Compute cumulative fee summary for the entire academy/tenant.
     * Kept 100% in sync across Students list, Student profile, and Dashboard.
     */
    public static function getInstituteFeeSummary(): array
    {
        $students = User::where('role', 'student')
            ->with(['feePlans', 'feePayments'])
            ->get();

        $totalAccrued = 0.0;
        $totalCollected = 0.0;
        $totalPending = 0.0;

        foreach ($students as $student) {
            $fee = self::calculateStudentFee($student);
            $totalAccrued += $fee['total_accrued'];
            $totalCollected += $fee['total_paid'];
            $totalPending += $fee['total_due'];
        }

        $collectionRatio = ($totalAccrued > 0)
            ? round(($totalCollected / $totalAccrued) * 100, 1)
            : 0;

        return [
            'total_students' => $students->count(),
            'active_students' => $students->where('status', 'active')->count(),
            'total_fees' => round($totalAccrued, 2),
            'collected_fees' => round($totalCollected, 2),
            'pending_fees' => round($totalPending, 2),
            'collection_ratio' => $collectionRatio,
        ];
    }
}
