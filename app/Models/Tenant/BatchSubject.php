<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchSubject extends Model
{
    use HasFactory;

    protected $connection = 'tenant';
    protected $table = 'batch_subjects';

    protected $fillable = [
        'batch_id',
        'subject_id',
        'teacher_id',
        'start_time',
        'end_time',
        'days',
        'room_number',
        'order',
    ];

    /**
     * Parent batch
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    /**
     * Associated subject module
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Assigned teacher (can override subject's default teacher)
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Effective Teacher: returns assigned teacher, or falls back to subject's default teacher
     */
    public function getEffectiveTeacherAttribute()
    {
        return $this->teacher ?: $this->subject?->teacher;
    }

    /**
     * Effective Start Time: returns batch period start_time, or falls back to subject's start_time
     */
    public function getEffectiveStartTimeAttribute()
    {
        return $this->start_time ?: $this->subject?->start_time;
    }

    /**
     * Effective End Time: returns batch period end_time, or falls back to subject's end_time
     */
    public function getEffectiveEndTimeAttribute()
    {
        return $this->end_time ?: $this->subject?->end_time;
    }

    /**
     * Effective Days: returns batch period days, or falls back to subject's days
     */
    public function getEffectiveDaysAttribute()
    {
        return $this->days ?: $this->subject?->days;
    }

    /**
     * Period duration in minutes
     */
    public function getDurationMinutesAttribute()
    {
        $start = $this->effective_start_time;
        $end = $this->effective_end_time;
        if (!$start || !$end) return 0;

        $startTs = strtotime("2000-01-01 {$start}");
        $endTs = strtotime("2000-01-01 {$end}");
        if ($endTs > $startTs) {
            return round(($endTs - $startTs) / 60);
        }
        return 0;
    }
}
