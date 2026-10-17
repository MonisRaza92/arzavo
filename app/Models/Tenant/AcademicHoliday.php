<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant\User;
use App\Models\Tenant\StaffAttendance;
use App\Models\Tenant\StudentAttendance;

class AcademicHoliday extends Model
{
    use HasFactory;

    protected $connection = 'tenant';
    protected $table = 'academic_holidays';

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'type',
        'scope',
        'description',
        'status',
        'academic_year',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'status'     => 'boolean',
    ];

    /**
     * Scope for active holidays only
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope for a specific academic or calendar year
     */
    public function scopeForYear($query, $year)
    {
        return $query->whereYear('start_date', $year)
            ->orWhereYear('end_date', $year);
    }

    /**
     * Check if a specific date is a declared active holiday
     *
     * @param string|\Carbon\Carbon $date
     * @param string $targetScope 'all', 'student', or 'staff'
     * @return AcademicHoliday|null
     */
    public static function forDate($date, string $targetScope = 'all'): ?self
    {
        $dateStr = ($date instanceof Carbon) ? $date->format('Y-m-d') : date('Y-m-d', strtotime($date));

        $query = self::active()
            ->whereDate('start_date', '<=', $dateStr)
            ->whereDate('end_date', '>=', $dateStr);

        if ($targetScope === 'student') {
            $query->whereIn('scope', ['all', 'students_only']);
        } elseif ($targetScope === 'staff') {
            $query->whereIn('scope', ['all', 'staff_only']);
        }

        return $query->first();
    }

    /**
     * Quick boolean check if a date is a holiday
     */
    public static function isHoliday($date, string $targetScope = 'all'): bool
    {
        return self::forDate($date, $targetScope) !== null;
    }

    /**
     * Generate or synchronize weekly recurring off days (Sunday, Saturday, Friday, etc.)
     * 
     * @param int $year
     * @param array<int> $daysOfWeek Array of Carbon dayOfWeek integers (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
     * @param string $scope 'all' | 'future'
     * @return array ['added' => int, 'removed' => int]
     */
    public static function syncWeeklyOffs(int $year, array $daysOfWeek, string $scope = 'all'): array
    {
        $dayNames = [
            0 => 'Sunday Weekly Off',
            1 => 'Monday Weekly Off',
            2 => 'Tuesday Weekly Off',
            3 => 'Wednesday Weekly Off',
            4 => 'Thursday Weekly Off',
            5 => 'Friday Weekly Off',
            6 => 'Saturday Weekly Off',
        ];

        $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
        $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();

        if ($scope === 'future' && $year == (int) date('Y')) {
            $startDate = now()->startOfDay();
        }

        $added = 0;
        $removed = 0;

        // Fetch existing weekly_off records in the date window
        $existingWeeklyOffs = self::where('type', 'weekly_off')
            ->whereBetween('start_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get()
            ->keyBy(function ($item) {
                return $item->start_date->format('Y-m-d');
            });

        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            $dateStr = $current->format('Y-m-d');
            $dow = $current->dayOfWeek; // 0 = Sunday, 6 = Saturday

            if (in_array($dow, $daysOfWeek)) {
                if (!$existingWeeklyOffs->has($dateStr)) {
                    self::create([
                        'title'         => $dayNames[$dow] ?? 'Weekly Off',
                        'start_date'    => $dateStr,
                        'end_date'      => $dateStr,
                        'type'          => 'weekly_off',
                        'scope'         => 'all',
                        'description'   => 'Default Institute Weekly Off',
                        'status'        => true,
                        'academic_year' => (string) $year,
                    ]);
                    $added++;
                } else {
                    $rec = $existingWeeklyOffs->get($dateStr);
                    if (!$rec->status) {
                        $rec->update(['status' => true]);
                    }
                }
            } else {
                // If this day was previously recorded as weekly off but is no longer selected
                if ($existingWeeklyOffs->has($dateStr)) {
                    $existingWeeklyOffs->get($dateStr)->delete();
                    $removed++;
                }
            }

            $current->addDay();
        }

        return ['added' => $added, 'removed' => $removed];
    }

    /**
     * Generate all Sundays of a given year as weekly off records (backward compatibility)
     */
    public static function seedDefaultSundays(int $year): int
    {
        $res = self::syncWeeklyOffs($year, [Carbon::SUNDAY], 'all');
        return $res['added'];
    }

    /**
     * Standard Gazetted & Major Cultural Festivals list for a given year
     */
    public static function getStandardFestivalsList(int $year): array
    {
        return [
            ['title' => 'New Year Day', 'date' => "{$year}-01-01", 'type' => 'festival', 'desc' => 'New Year Celebration'],
            ['title' => 'Republic Day', 'date' => "{$year}-01-26", 'type' => 'festival', 'desc' => 'National Holiday'],
            ['title' => 'Maha Shivratri', 'date' => "{$year}-02-26", 'type' => 'festival', 'desc' => 'Gazetted Holiday'],
            ['title' => 'Holi Festival', 'date' => "{$year}-03-14", 'type' => 'festival', 'desc' => 'Festival of Colors'],
            ['title' => 'Eid-ul-Fitr', 'date' => "{$year}-03-31", 'type' => 'festival', 'desc' => 'Islamic Festival (Moon sighting dependent)'],
            ['title' => 'Ram Navami', 'date' => "{$year}-04-06", 'type' => 'festival', 'desc' => 'Gazetted Festival Holiday'],
            ['title' => 'Mahavir Jayanti', 'date' => "{$year}-04-10", 'type' => 'festival', 'desc' => 'Gazetted Holiday'],
            ['title' => 'Good Friday', 'date' => "{$year}-04-18", 'type' => 'festival', 'desc' => 'Christian Holiday'],
            ['title' => 'Eid-ul-Adha (Bakrid)', 'date' => "{$year}-06-07", 'type' => 'festival', 'desc' => 'Islamic Festival (Feast of Sacrifice)'],
            ['title' => 'Muharram', 'date' => "{$year}-07-06", 'type' => 'festival', 'desc' => 'Islamic New Year / Ashura'],
            ['title' => 'Independence Day', 'date' => "{$year}-08-15", 'type' => 'festival', 'desc' => 'National Holiday'],
            ['title' => 'Raksha Bandhan', 'date' => "{$year}-08-28", 'type' => 'festival', 'desc' => 'Festival Holiday'],
            ['title' => 'Janmashtami', 'date' => "{$year}-09-04", 'type' => 'festival', 'desc' => 'Krishna Janmashtami'],
            ['title' => 'Milad-un-Nabi', 'date' => "{$year}-09-15", 'type' => 'festival', 'desc' => 'Prophet Birthday (Eid Milad)'],
            ['title' => 'Mahatma Gandhi Jayanti', 'date' => "{$year}-10-02", 'type' => 'festival', 'desc' => 'National Holiday'],
            ['title' => 'Dussehra (Vijayadashami)', 'date' => "{$year}-10-20", 'type' => 'festival', 'desc' => 'Vijayadashami Celebration'],
            ['title' => 'Diwali (Deepavali)', 'date' => "{$year}-11-08", 'type' => 'festival', 'desc' => 'Festival of Lights'],
            ['title' => 'Govardhan Puja / Bhai Dooj', 'date' => "{$year}-11-10", 'type' => 'festival', 'desc' => 'Post Diwali Holiday'],
            ['title' => 'Guru Nanak Jayanti', 'date' => "{$year}-11-24", 'type' => 'festival', 'desc' => 'Gurpurab'],
            ['title' => 'Christmas Day', 'date' => "{$year}-12-25", 'type' => 'festival', 'desc' => 'Christmas Celebration'],
        ];
    }

    /**
     * Seed major standard festivals and national holidays for a given year
     */
    public static function seedStandardFestivals(int $year): int
    {
        $standardFestivals = self::getStandardFestivalsList($year);

        $createdCount = 0;
        foreach ($standardFestivals as $f) {
            $exists = self::where('start_date', $f['date'])
                ->where('title', $f['title'])
                ->exists();

            if (!$exists) {
                self::create([
                    'title'         => $f['title'],
                    'start_date'    => $f['date'],
                    'end_date'      => $f['date'],
                    'type'          => $f['type'],
                    'scope'         => 'all',
                    'description'   => $f['desc'],
                    'status'        => true,
                    'academic_year' => (string) $year,
                ]);
                $createdCount++;
            }
        }

        return $createdCount;
    }

    /**
     * Human-readable type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'weekly_off' => 'Weekly Off (Sunday)',
            'festival'   => 'Festival Holiday',
            'emergency'  => 'Emergency Closure',
            'event'      => 'Event / Function',
            'vacation'   => 'Academic Vacation',
            default      => ucfirst($this->type ?: 'Holiday'),
        };
    }

    /**
     * Human-readable scope label
     */
    public function getScopeLabelAttribute(): string
    {
        return match($this->scope) {
            'students_only' => 'Students Only',
            'staff_only'    => 'Staff Only',
            default         => 'Whole Institute (All)',
        };
    }

    /**
     * Duration in days
     */
    public function getDurationDaysAttribute(): int
    {
        if (!$this->start_date || !$this->end_date) return 1;
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Check if holiday has already passed relative to today
     */
    public function getIsPastAttribute(): bool
    {
        $todayStr = now()->format('Y-m-d');
        return $this->end_date->format('Y-m-d') < $todayStr;
    }

    /**
     * Countdown badge label (e.g. 'In Session Today', 'Tomorrow', 'In 3 Days', 'Passed')
     */
    public function getCountdownLabelAttribute(): string
    {
        $today = now()->startOfDay();
        $start = $this->start_date->copy()->startOfDay();
        $end = $this->end_date->copy()->startOfDay();

        if ($today->between($start, $end)) {
            return 'In Session Today';
        }

        if ($end->lt($today)) {
            return 'Passed';
        }

        $diffDays = (int) $today->diffInDays($start, false);
        if ($diffDays === 1) {
            return 'Tomorrow';
        }
        if ($diffDays > 1) {
            return "In {$diffDays} Days";
        }

        return 'Upcoming';
    }

    /**
     * Check if a specific date is a declared active holiday and automatically
     * mark attendance records for all active teachers and/or students.
     *
     * @param string|\Carbon\Carbon $date
     * @param string $targetScope 'all', 'student', or 'staff'
     * @return AcademicHoliday|null
     */
    public static function autoMarkDate($date, string $targetScope = 'all'): ?self
    {
        $holiday = self::forDate($date, $targetScope);
        if (!$holiday) {
            return null;
        }

        $dateStr = ($date instanceof Carbon) ? $date->format('Y-m-d') : date('Y-m-d', strtotime($date));
        $today = now()->format('Y-m-d');

        // Only mark attendance if the date is not in the future
        if ($dateStr <= $today) {
            $adminId = Auth::guard('tenant')->id() ?: ($holiday->created_by ?: 1);

            $affectsStaff = in_array($holiday->scope, ['all', 'staff_only']);
            $affectsStudents = in_array($holiday->scope, ['all', 'students_only']);

            if (($targetScope === 'all' || $targetScope === 'staff') && $affectsStaff) {
                $teachers = User::where('role', 'teacher')
                    ->where('status', 'active')
                    ->where(function($q) use ($dateStr) {
                        $q->where(function($sq) use ($dateStr) {
                            $sq->whereNotNull('joining_date')->whereDate('joining_date', '<=', $dateStr);
                        })
                        ->orWhere(function($sq) use ($dateStr) {
                            $sq->whereNull('joining_date')->whereDate('created_at', '<=', $dateStr);
                        });
                    })
                    ->get();

                foreach ($teachers as $teacher) {
                    StaffAttendance::updateOrCreate(
                        [
                            'user_id' => $teacher->id,
                            'date'    => $dateStr,
                        ],
                        [
                            'status'    => 'holiday',
                            'remarks'   => $holiday->title,
                            'marked_by' => $adminId,
                        ]
                    );
                }
            }

            if (($targetScope === 'all' || $targetScope === 'student') && $affectsStudents) {
                $students = User::where('role', 'student')
                    ->whereNotNull('class_id')
                    ->where(function($q) use ($dateStr) {
                        $q->where(function($sq) use ($dateStr) {
                            $sq->whereNotNull('enrolled_at')->whereDate('enrolled_at', '<=', $dateStr);
                        })
                        ->orWhere(function($sq) use ($dateStr) {
                            $sq->whereNull('enrolled_at')->whereDate('created_at', '<=', $dateStr);
                        });
                    })
                    ->get();

                foreach ($students as $student) {
                    StudentAttendance::updateOrCreate(
                        [
                            'student_id'      => $student->id,
                            'class_course_id' => $student->class_id,
                            'date'            => $dateStr,
                        ],
                        [
                            'status'    => 'holiday',
                            'remarks'   => $holiday->title,
                            'marked_by' => $adminId,
                        ]
                    );
                }
            }
        }

        return $holiday;
    }

    /**
     * Auto-mark attendance records for all eligible teachers and students
     * for any date in the holiday range that is <= today.
     */
    public static function autoMarkAttendanceForHoliday(self $holiday): void
    {
        if (!$holiday->status) {
            return;
        }

        $today = now()->format('Y-m-d');
        $start = Carbon::parse($holiday->start_date)->toDateString();
        $end = $holiday->end_date ? Carbon::parse($holiday->end_date)->toDateString() : $start;

        $effectiveEnd = ($end > $today) ? $today : $end;
        if ($start > $effectiveEnd) {
            return;
        }

        $period = CarbonPeriod::create($start, $effectiveEnd);
        foreach ($period as $dt) {
            self::autoMarkDate($dt->format('Y-m-d'), 'all');
        }
    }

    /**
     * Remove auto-marked holiday attendance records when a holiday is deleted or deactivated.
     */
    public static function removeHolidayAttendance(self $holiday): void
    {
        $start = Carbon::parse($holiday->start_date)->toDateString();
        $end = $holiday->end_date ? Carbon::parse($holiday->end_date)->toDateString() : $start;

        if (in_array($holiday->scope, ['all', 'staff_only'])) {
            StaffAttendance::whereBetween('date', [$start, $end])
                ->where('status', 'holiday')
                ->delete();
        }

        if (in_array($holiday->scope, ['all', 'students_only'])) {
            StudentAttendance::whereBetween('date', [$start, $end])
                ->where('status', 'holiday')
                ->delete();
        }
    }
}
