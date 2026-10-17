<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Batch extends Model
{
    use HasFactory;

    protected $connection = 'tenant';
    protected $table = 'batches';

    protected $fillable = [
        'class_course_id',
        'subject_id',
        'teacher_id',
        'name',
        'code',
        'start_time',
        'end_time',
        'days',
        'room_number',
        'max_students',
        'status',
        'description',
        'total_duration_minutes',
        'break_duration_minutes',
        'order',
    ];

    protected $casts = [
        'status' => 'boolean',
        'max_students' => 'integer',
        'total_duration_minutes' => 'integer',
        'break_duration_minutes' => 'integer',
    ];

    /**
     * Batch belongs to a Class/Course
     */
    public function classCourse()
    {
        return $this->belongsTo(ClassCourse::class, 'class_course_id');
    }

    /**
     * Primary or legacy Subject (optional when batch has multiple subjects)
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Batch MUST have an assigned Head Mentor / Class Teacher
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Multiple Subject periods scheduled in this Batch
     */
    public function batchSubjects()
    {
        return $this->hasMany(BatchSubject::class, 'batch_id')->orderBy('order')->orderBy('start_time');
    }

    /**
     * Many-to-many subjects linked through batch_subjects
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'batch_subjects', 'batch_id', 'subject_id')
            ->withPivot('teacher_id', 'start_time', 'end_time', 'days', 'room_number', 'order')
            ->withTimestamps();
    }

    /**
     * Students enrolled in this batch
     */
    public function students()
    {
        return $this->hasMany(User::class, 'batch_id')->where('role', 'student');
    }

    /**
     * Automatically recalculate batch start time, end time, total span and break duration
     * based on its subject periods schedule.
     */
    public function recalculateSchedule()
    {
        $periods = $this->batchSubjects()->with('subject')->get();
        if ($periods->isEmpty()) {
            return;
        }

        $minStart = null;
        $maxEnd = null;
        $totalClassMinutes = 0;
        $allDays = [];

        // Sort periods by start_time
        $sortedPeriods = $periods->filter(fn($p) => !empty($p->effective_start_time) && !empty($p->effective_end_time))
            ->sortBy(fn($p) => strtotime("2000-01-01 {$p->effective_start_time}"))
            ->values();

        foreach ($sortedPeriods as $p) {
            $sTime = $p->effective_start_time;
            $eTime = $p->effective_end_time;
            $sTs = strtotime("2000-01-01 {$sTime}");
            $eTs = strtotime("2000-01-01 {$eTime}");

            if ($minStart === null || $sTs < strtotime("2000-01-01 {$minStart}")) {
                $minStart = $sTime;
            }
            if ($maxEnd === null || $eTs > strtotime("2000-01-01 {$maxEnd}")) {
                $maxEnd = $eTime;
            }

            if ($eTs > $sTs) {
                $totalClassMinutes += round(($eTs - $sTs) / 60);
            }

            // Collect days
            $pDays = array_filter(array_map('trim', explode(',', $p->effective_days ?? '')));
            foreach ($pDays as $d) {
                if (!in_array($d, $allDays)) {
                    $allDays[] = $d;
                }
            }
        }

        $totalSpanMinutes = 0;
        $breakMinutes = 0;

        if ($minStart && $maxEnd) {
            $minTs = strtotime("2000-01-01 {$minStart}");
            $maxTs = strtotime("2000-01-01 {$maxEnd}");
            if ($maxTs > $minTs) {
                $totalSpanMinutes = round(($maxTs - $minTs) / 60);
                $breakMinutes = max(0, $totalSpanMinutes - $totalClassMinutes);
            }
        }

        $dayOrder = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        usort($allDays, fn($a, $b) => array_search($a, $dayOrder) <=> array_search($b, $dayOrder));

        $updateData = [
            'start_time' => $minStart,
            'end_time' => $maxEnd,
            'total_duration_minutes' => $totalSpanMinutes,
            'break_duration_minutes' => $breakMinutes,
        ];

        if (!empty($allDays)) {
            $updateData['days'] = implode(', ', $allDays);
        }

        $this->updateQuietly($updateData);
    }

    /**
     * Human-friendly formatted schedule duration
     */
    public function getFormattedDurationAttribute()
    {
        $total = $this->total_duration_minutes ?? 0;
        $break = $this->break_duration_minutes ?? 0;

        if ($total <= 0) {
            return 'Flexible Hours';
        }

        $hours = floor($total / 60);
        $mins = $total % 60;
        $str = '';
        if ($hours > 0) $str .= "{$hours}h ";
        if ($mins > 0) $str .= "{$mins}m";
        $str = trim($str);

        if ($break > 0) {
            $bHours = floor($break / 60);
            $bMins = $break % 60;
            $bStr = '';
            if ($bHours > 0) $bStr .= "{$bHours}h ";
            if ($bMins > 0) $bStr .= "{$bMins}m";
            $bStr = trim($bStr);
            return "{$str} (with {$bStr} break)";
        }

        return $str;
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
