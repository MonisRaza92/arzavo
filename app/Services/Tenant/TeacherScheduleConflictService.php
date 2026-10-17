<?php

namespace App\Services\Tenant;

use App\Models\Tenant\Subject;
use App\Models\Tenant\BatchSubject;
use App\Models\Tenant\User;

class TeacherScheduleConflictService
{
    /**
     * Standard list of days abbreviation (Mon - Sun)
     */
    const ALL_DAYS = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

    /**
     * Parse a time string into timestamp seconds on a base date.
     *
     * @param string|null $time
     * @return int|null
     */
    public function parseTime(?string $time): ?int
    {
        if (empty($time)) {
            return null;
        }

        $clean = trim($time);
        $ts = strtotime("2000-01-01 {$clean}");

        return $ts !== false ? $ts : null;
    }

    /**
     * Normalize days into a list of 3-letter day codes (e.g., ['Mon', 'Tue']).
     *
     * @param mixed $days
     * @return array
     */
    public function normalizeDays($days): array
    {
        if (empty($days)) {
            return self::ALL_DAYS;
        }

        if (is_array($days)) {
            $rawList = $days;
        } elseif (is_string($days)) {
            $trimmed = trim($days);
            if (str_starts_with($trimmed, '[') && str_ends_with($trimmed, ']')) {
                $decoded = json_decode($trimmed, true);
                $rawList = is_array($decoded) ? $decoded : explode(',', $trimmed);
            } else {
                $rawList = explode(',', $trimmed);
            }
        } else {
            return self::ALL_DAYS;
        }

        $cleaned = array_filter(array_map('trim', $rawList));
        if (empty($cleaned)) {
            return self::ALL_DAYS;
        }

        return array_values(array_unique(array_map(function ($d) {
            return substr(ucfirst(strtolower($d)), 0, 3);
        }, $cleaned)));
    }

    /**
     * Check if two time intervals on common days collide.
     * Overlap condition: startA < endB && endA > startB
     *
     * @param int $startA
     * @param int $endA
     * @param array $daysA
     * @param int $startB
     * @param int $endB
     * @param array $daysB
     * @return bool
     */
    public function isOverlapping(int $startA, int $endA, array $daysA, int $startB, int $endB, array $daysB): bool
    {
        if ($endA <= $startA || $endB <= $startB) {
            return false;
        }

        $commonDays = array_intersect($daysA, $daysB);
        if (empty($commonDays)) {
            return false;
        }

        // Strict overlap: startA < endB && endA > startB
        return ($startA < $endB) && ($endA > $startB);
    }

    /**
     * Check single allocation conflict for a teacher.
     *
     * @param int $teacherId
     * @param string|null $startTime
     * @param string|null $endTime
     * @param mixed $days
     * @param string|null $excludeType ('subject' or 'batch_subject')
     * @param int|null $excludeId
     * @return array|null Conflict details if collision exists, null otherwise
     */
    public function checkConflict($teacherId, ?string $startTime, ?string $endTime, $days = null, ?string $excludeType = null, ?int $excludeId = null): ?array
    {
        if (!$teacherId || empty($startTime) || empty($endTime)) {
            return null;
        }

        $targetStart = $this->parseTime($startTime);
        $targetEnd = $this->parseTime($endTime);

        if (!$targetStart || !$targetEnd || $targetEnd <= $targetStart) {
            return null;
        }

        $targetDays = $this->normalizeDays($days);

        $teacher = User::find($teacherId);
        $teacherName = $teacher ? trim("{$teacher->fname} {$teacher->lname}") : 'Selected Teacher';

        // 1. Check against other Subjects assigned to this teacher
        $subjectQuery = Subject::where('teacher_id', $teacherId)
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->where('start_time', '!=', '')
            ->where('end_time', '!=', '')
            ->with('classCourse');

        if ($excludeType === 'subject' && $excludeId) {
            $subjectQuery->where('id', '!=', $excludeId);
        }

        $assignedSubjects = $subjectQuery->get();

        foreach ($assignedSubjects as $subj) {
            $sStart = $this->parseTime($subj->start_time);
            $sEnd = $this->parseTime($subj->end_time);

            if (!$sStart || !$sEnd || $sEnd <= $sStart) {
                continue;
            }

            $sDays = $this->normalizeDays($subj->days);

            if ($this->isOverlapping($targetStart, $targetEnd, $targetDays, $sStart, $sEnd, $sDays)) {
                $className = $subj->classCourse ? $subj->classCourse->name : 'Class';
                $sStartStr = date('h:i A', $sStart);
                $sEndStr = date('h:i A', $sEnd);
                $commonDays = array_intersect($targetDays, $sDays);
                $dayStr = (count($commonDays) >= 7) ? 'Daily' : implode(', ', $commonDays);

                return [
                    'conflict' => true,
                    'teacher_id' => $teacherId,
                    'teacher_name' => $teacherName,
                    'conflicting_subject_name' => $subj->name,
                    'conflicting_class_name' => $className,
                    'start_time' => $sStartStr,
                    'end_time' => $sEndStr,
                    'days' => $dayStr,
                    'message' => "Schedule Conflict: Teacher {$teacherName} is already busy teaching '{$subj->name}' ({$className}) from {$sStartStr} to {$sEndStr} ({$dayStr}). Please allocate a subject scheduled after {$sEndStr} or assign another teacher."
                ];
            }
        }

        // 2. Check against other BatchSubjects assigned to this teacher
        $batchSubjQuery = BatchSubject::where('teacher_id', $teacherId)
            ->with(['batch.classCourse', 'subject']);

        if ($excludeType === 'batch_subject' && $excludeId) {
            $batchSubjQuery->where('id', '!=', $excludeId);
        }

        if ($excludeType === 'subject' && $excludeId) {
            // Exclude batch periods of this exact subject
            $batchSubjQuery->where('subject_id', '!=', $excludeId);
        }

        $assignedBatchSubjects = $batchSubjQuery->get();

        foreach ($assignedBatchSubjects as $bs) {
            $bStart = $this->parseTime($bs->effective_start_time);
            $bEnd = $this->parseTime($bs->effective_end_time);

            if (!$bStart || !$bEnd || $bEnd <= $bStart) {
                continue;
            }

            $bDays = $this->normalizeDays($bs->effective_days);

            if ($this->isOverlapping($targetStart, $targetEnd, $targetDays, $bStart, $bEnd, $bDays)) {
                $subName = $bs->subject ? $bs->subject->name : 'Batch Period';
                $batchName = $bs->batch ? $bs->batch->name : 'Batch';
                $className = $bs->batch?->classCourse ? $bs->batch->classCourse->name : '';
                $contextName = $className ? "{$batchName} &bull; {$className}" : $batchName;

                $bStartStr = date('h:i A', $bStart);
                $bEndStr = date('h:i A', $bEnd);
                $commonDays = array_intersect($targetDays, $bDays);
                $dayStr = (count($commonDays) >= 7) ? 'Daily' : implode(', ', $commonDays);

                return [
                    'conflict' => true,
                    'teacher_id' => $teacherId,
                    'teacher_name' => $teacherName,
                    'conflicting_subject_name' => $subName,
                    'conflicting_class_name' => $contextName,
                    'start_time' => $bStartStr,
                    'end_time' => $bEndStr,
                    'days' => $dayStr,
                    'message' => "Schedule Conflict: Teacher {$teacherName} is already busy teaching '{$subName}' in {$contextName} from {$bStartStr} to {$bEndStr} ({$dayStr}). Please allocate a subject scheduled after {$bEndStr} or assign another teacher."
                ];
            }
        }

        return null;
    }

    /**
     * Check conflicts when assigning multiple subjects at once to a teacher.
     *
     * @param int $teacherId
     * @param array $subjectIds
     * @return array|null
     */
    public function checkBulkSubjectConflicts($teacherId, array $subjectIds): ?array
    {
        if (!$teacherId || empty($subjectIds)) {
            return null;
        }

        $teacher = User::find($teacherId);
        $teacherName = $teacher ? trim("{$teacher->fname} {$teacher->lname}") : 'Selected Teacher';

        $subjects = Subject::whereIn('id', $subjectIds)
            ->with('classCourse')
            ->get();

        // 1. Pairwise collision among the selected subjects themselves
        $count = $subjects->count();
        for ($i = 0; $i < $count; $i++) {
            $sA = $subjects[$i];
            $startA = $this->parseTime($sA->start_time);
            $endA = $this->parseTime($sA->end_time);

            if (!$startA || !$endA || $endA <= $startA) {
                continue;
            }

            $daysA = $this->normalizeDays($sA->days);

            for ($j = $i + 1; $j < $count; $j++) {
                $sB = $subjects[$j];
                $startB = $this->parseTime($sB->start_time);
                $endB = $this->parseTime($sB->end_time);

                if (!$startB || !$endB || $endB <= $startB) {
                    continue;
                }

                $daysB = $this->normalizeDays($sB->days);

                if ($this->isOverlapping($startA, $endA, $daysA, $startB, $endB, $daysB)) {
                    $classA = $sA->classCourse ? $sA->classCourse->name : 'Class A';
                    $classB = $sB->classCourse ? $sB->classCourse->name : 'Class B';
                    $startAStr = date('h:i A', $startA);
                    $endAStr = date('h:i A', $endA);
                    $startBStr = date('h:i A', $startB);
                    $endBStr = date('h:i A', $endB);

                    return [
                        'conflict' => true,
                        'teacher_id' => $teacherId,
                        'teacher_name' => $teacherName,
                        'message' => "Schedule Conflict: Cannot assign both '{$sA->name}' ({$classA}) [{$startAStr} - {$endAStr}] and '{$sB->name}' ({$classB}) [{$startBStr} - {$endBStr}] to Teacher {$teacherName}. Their schedules overlap. Please deselect one of them."
                    ];
                }
            }

            // 2. Also check each selected subject against any assigned BatchSubjects of this teacher
            $batchSubjects = BatchSubject::where('teacher_id', $teacherId)
                ->whereNotIn('subject_id', $subjectIds)
                ->with(['batch.classCourse', 'subject'])
                ->get();

            foreach ($batchSubjects as $bs) {
                $bStart = $this->parseTime($bs->effective_start_time);
                $bEnd = $this->parseTime($bs->effective_end_time);

                if (!$bStart || !$bEnd || $bEnd <= $bStart) {
                    continue;
                }

                $bDays = $this->normalizeDays($bs->effective_days);

                if ($this->isOverlapping($startA, $endA, $daysA, $bStart, $bEnd, $bDays)) {
                    $subName = $bs->subject ? $bs->subject->name : 'Batch Period';
                    $batchName = $bs->batch ? $bs->batch->name : 'Batch';
                    $bStartStr = date('h:i A', $bStart);
                    $bEndStr = date('h:i A', $bEnd);
                    $classA = $sA->classCourse ? $sA->classCourse->name : 'Class';

                    return [
                        'conflict' => true,
                        'teacher_id' => $teacherId,
                        'teacher_name' => $teacherName,
                        'message' => "Schedule Conflict: Teacher {$teacherName} is already teaching '{$subName}' in {$batchName} from {$bStartStr} to {$bEndStr}. Cannot assign '{$sA->name}' ({$classA}) scheduled for " . date('h:i A', $startA) . " to " . date('h:i A', $endA) . "."
                    ];
                }
            }
        }

        return null;
    }
}
