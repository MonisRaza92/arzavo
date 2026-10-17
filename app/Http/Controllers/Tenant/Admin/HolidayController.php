<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant\AcademicHoliday;
use Carbon\Carbon;

class HolidayController extends Controller
{
    /**
     * Holiday & Academic Calendar Cockpit
     */
    public function index(Request $request)
    {
        $currentYear = (int) ($request->input('year', date('Y')));
        $currentMonth = (int) ($request->input('month', date('n')));

        // Auto-seed default Sundays & Festivals if system has none for this year
        $existingCount = AcademicHoliday::forYear($currentYear)->count();
        if ($existingCount === 0) {
            AcademicHoliday::seedDefaultSundays($currentYear);
            AcademicHoliday::seedStandardFestivals($currentYear);
        }

        // Base query for holiday records in natural chronological sequence
        $query = AcademicHoliday::forYear($currentYear)->orderBy('start_date', 'asc');

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        $holidays = $query->get();

        // Calculate KPI Metrics
        $totalHolidaysCount = $holidays->where('status', true)->count();
        $festivalCount = $holidays->where('type', 'festival')->where('status', true)->count();
        $weeklyOffCount = $holidays->where('type', 'weekly_off')->where('status', true)->count();
        $emergencyCount = $holidays->where('type', 'emergency')->where('status', true)->count();

        $todayStr = now()->format('Y-m-d');
        $nextUpcoming = AcademicHoliday::active()
            ->whereDate('end_date', '>=', $todayStr)
            ->orderBy('start_date', 'asc')
            ->first();

        $upcomingCount = $holidays->filter(function ($h) use ($todayStr) {
            return $h->end_date->format('Y-m-d') >= $todayStr;
        })->count();
        $pastCount = $holidays->count() - $upcomingCount;

        // ── Generate Mini-Calendar Grid for Selected Month ─────────────────
        $monthDate = Carbon::createFromDate($currentYear, $currentMonth, 1);
        $daysInMonth = $monthDate->daysInMonth;
        $startDayOfWeek = $monthDate->copy()->startOfMonth()->dayOfWeek; // 0 = Sunday, 6 = Saturday

        $monthHolidays = AcademicHoliday::query()
            ->where(function ($q) use ($currentYear, $currentMonth, $daysInMonth) {
                $from = sprintf('%04d-%02d-01', $currentYear, $currentMonth);
                $to = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $daysInMonth);

                $q->whereBetween('start_date', [$from, $to])
                  ->orWhereBetween('end_date', [$from, $to])
                  ->orWhere(function ($sub) use ($from, $to) {
                      $sub->where('start_date', '<=', $from)
                          ->where('end_date', '>=', $to);
                  });
            })
            ->get();

        $standardFestivals = AcademicHoliday::getStandardFestivalsList($currentYear);
        $standardFestivalsMap = collect($standardFestivals)->keyBy('date');

        // Detect currently configured recurring weekly off days (0 = Sun, 1 = Mon, ..., 6 = Sat)
        $activeWeeklyOffDays = AcademicHoliday::where('type', 'weekly_off')
            ->whereYear('start_date', $currentYear)
            ->where('status', true)
            ->get()
            ->map(function ($h) {
                return $h->start_date->dayOfWeek;
            })
            ->unique()
            ->values()
            ->all();

        if (empty($activeWeeklyOffDays)) {
            $activeWeeklyOffDays = [0]; // Default to Sunday
        }

        $calendarDays = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateObj = Carbon::createFromDate($currentYear, $currentMonth, $day);
            $dateStr = $dateObj->format('Y-m-d');
            $isSunday = ($dateObj->dayOfWeek === Carbon::SUNDAY);
            $isWeeklyOffDay = in_array($dateObj->dayOfWeek, $activeWeeklyOffDays);

            // Find holiday matching this date
            $matchedHoliday = $monthHolidays->first(function ($h) use ($dateStr) {
                return $dateStr >= $h->start_date->format('Y-m-d') && $dateStr <= $h->end_date->format('Y-m-d');
            });

            $stdFest = $standardFestivalsMap->get($dateStr);
            $hasFestival = ($stdFest !== null || ($matchedHoliday && $matchedHoliday->type === 'festival'));
            $festivalName = ($matchedHoliday && $matchedHoliday->type === 'festival') ? $matchedHoliday->title : ($stdFest['title'] ?? null);
            $festivalDesc = ($matchedHoliday && $matchedHoliday->type === 'festival') ? ($matchedHoliday->description ?? '') : ($stdFest['desc'] ?? '');

            $calendarDays[] = [
                'day'               => $day,
                'date'              => $dateStr,
                'is_sunday'         => $isSunday,
                'is_weekly_off_day' => $isWeeklyOffDay,
                'is_today'          => ($dateStr === $todayStr),
                'is_holiday'        => ($matchedHoliday !== null && $matchedHoliday->status),
                'is_overridden'     => ($matchedHoliday !== null && !$matchedHoliday->status),
                'has_festival'      => $hasFestival,
                'festival_name'     => $festivalName,
                'festival_desc'     => $festivalDesc,
                'holiday'           => $matchedHoliday ? [
                    'id'             => $matchedHoliday->id,
                    'title'          => $matchedHoliday->title,
                    'type'           => $matchedHoliday->type,
                    'type_label'     => $matchedHoliday->type_label,
                    'scope'          => $matchedHoliday->scope,
                    'scope_label'    => $matchedHoliday->scope_label,
                    'status'         => (bool) $matchedHoliday->status,
                    'start_date'     => $matchedHoliday->start_date->format('Y-m-d'),
                    'end_date'       => $matchedHoliday->end_date->format('Y-m-d'),
                    'date_formatted' => $matchedHoliday->start_date->format('l, d M Y'),
                    'description'    => $matchedHoliday->description,
                ] : null,
            ];
        }

        return view('tenant.admin.academics.holidays.index', compact(
            'holidays',
            'upcomingCount',
            'pastCount',
            'currentYear',
            'currentMonth',
            'monthDate',
            'calendarDays',
            'startDayOfWeek',
            'totalHolidaysCount',
            'festivalCount',
            'weeklyOffCount',
            'emergencyCount',
            'nextUpcoming',
            'standardFestivalsMap',
            'activeWeeklyOffDays'
        ));
    }

    /**
     * Store a newly created Holiday / Event / Vacation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'type'        => 'required|string|in:festival,weekly_off,emergency,event,vacation,other',
            'scope'       => 'required|string|in:all,students_only,staff_only',
            'description' => 'nullable|string|max:1000',
        ]);

        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'] ?: $startDate;
        $academicYear = date('Y', strtotime($startDate));
        $adminId = Auth::guard('tenant')->id();

        $holiday = AcademicHoliday::create([
            'title'         => $validated['title'],
            'start_date'    => $startDate,
            'end_date'      => $endDate,
            'type'          => $validated['type'],
            'scope'         => $validated['scope'],
            'description'   => $validated['description'] ?? null,
            'status'        => true,
            'academic_year' => $academicYear,
            'created_by'    => $adminId,
        ]);

        AcademicHoliday::autoMarkAttendanceForHoliday($holiday);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Holiday '{$holiday->title}' added successfully.",
                'holiday' => $holiday,
            ]);
        }

        return redirect()->back()->with('success', "Holiday '{$holiday->title}' created successfully.");
    }

    /**
     * Update an existing Holiday
     */
    public function update(Request $request, $id)
    {
        $holiday = AcademicHoliday::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'type'        => 'required|string|in:festival,weekly_off,emergency,event,vacation,other',
            'scope'       => 'required|string|in:all,students_only,staff_only',
            'description' => 'nullable|string|max:1000',
            'status'      => 'nullable|boolean',
        ]);

        $newStatus = $request->has('status') ? (bool) $request->status : $holiday->status;
        $holiday->update([
            'title'       => $validated['title'],
            'start_date'  => $validated['start_date'],
            'end_date'    => $validated['end_date'] ?: $validated['start_date'],
            'type'        => $validated['type'],
            'scope'       => $validated['scope'],
            'description' => $validated['description'] ?? null,
            'status'      => $newStatus,
        ]);

        if ($holiday->status) {
            AcademicHoliday::autoMarkAttendanceForHoliday($holiday);
        } else {
            AcademicHoliday::removeHolidayAttendance($holiday);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Holiday updated successfully.",
                'holiday' => $holiday,
            ]);
        }

        return redirect()->back()->with('success', "Holiday '{$holiday->title}' updated successfully.");
    }

    /**
     * Toggle Holiday status (e.g. Active Holiday <-> Working Day Override)
     */
    public function toggleStatus($id)
    {
        $holiday = AcademicHoliday::findOrFail($id);
        $newStatus = !$holiday->status;
        $holiday->update(['status' => $newStatus]);

        if ($newStatus) {
            AcademicHoliday::autoMarkAttendanceForHoliday($holiday);
        } else {
            AcademicHoliday::removeHolidayAttendance($holiday);
        }

        $actionText = $newStatus ? 'activated as Holiday' : 'converted to Normal Working Day';

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Date {$holiday->start_date->format('d M')} {$actionText}.",
                'status'  => $newStatus,
            ]);
        }

        return redirect()->back()->with('success', "Date {$holiday->start_date->format('d M')} {$actionText}.");
    }

    /**
     * Delete a Holiday record
     */
    public function destroy($id)
    {
        $holiday = AcademicHoliday::findOrFail($id);
        $title = $holiday->title;
        AcademicHoliday::removeHolidayAttendance($holiday);
        $holiday->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Holiday '{$title}' removed successfully.",
            ]);
        }

        return redirect()->back()->with('success', "Holiday '{$title}' removed successfully.");
    }

    /**
     * 1-Click Re-sync Standard Festivals and Weekly Offs for given year
     */
    public function syncDefaults(Request $request)
    {
        $year = (int) ($request->input('year', date('Y')));

        $sundaysCount = AcademicHoliday::seedDefaultSundays($year);
        $festivalsCount = AcademicHoliday::seedStandardFestivals($year);

        return redirect()->back()->with('success', "Calendar synced! Added {$sundaysCount} Sundays and {$festivalsCount} standard festival holidays for {$year}.");
    }

    /**
     * Configure recurring weekly offs (any combination of 7 days: Sunday, Saturday, Friday, etc.)
     */
    public function manageWeeklyOffs(Request $request)
    {
        $request->validate([
            'year'           => 'required|integer',
            'days_of_week'   => 'nullable|array',
            'days_of_week.*' => 'integer|min:0|max:6',
            'scope'          => 'nullable|string|in:all,future',
            'bulk_action'    => 'nullable|string|in:apply,make_all_working,make_all_holiday',
        ]);

        $year = (int) $request->input('year', date('Y'));
        $bulkAction = $request->input('bulk_action', 'apply');

        if ($bulkAction === 'make_all_working') {
            AcademicHoliday::where('type', 'weekly_off')
                ->whereYear('start_date', $year)
                ->update(['status' => false]);
            return redirect()->back()->with('success', "All weekly offs for year {$year} marked as open working days.");
        }

        if ($bulkAction === 'make_all_holiday') {
            AcademicHoliday::where('type', 'weekly_off')
                ->whereYear('start_date', $year)
                ->update(['status' => true]);
            return redirect()->back()->with('success', "All weekly offs for year {$year} reactivated as official holidays.");
        }

        $selectedDays = $request->input('days_of_week', []);
        $scope = $request->input('scope', 'all');

        if (empty($selectedDays)) {
            // Unchecked all days: remove all weekly off records
            $deleted = AcademicHoliday::where('type', 'weekly_off')
                ->whereYear('start_date', $year)
                ->delete();
            return redirect()->back()->with('success', "All recurring weekly offs removed for year {$year} ({$deleted} days cleared). All 7 days are now regular working days.");
        }

        $selectedDays = array_map('intval', $selectedDays);
        $result = AcademicHoliday::syncWeeklyOffs($year, $selectedDays, $scope);

        $dayLabels = [
            0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday',
            4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday'
        ];
        $selectedNames = array_map(fn($d) => $dayLabels[$d] ?? "Day {$d}", $selectedDays);
        $daysStr = implode(', ', $selectedNames);

        return redirect()->back()->with('success', "Weekly offs configured for [{$daysStr}] ({$result['added']} added, {$result['removed']} removed) for year {$year}.");
    }

    /**
     * Bulk Action: Enable or Disable All Sundays for a Year (Legacy compatibility)
     */
    public function bulkToggleSundays(Request $request)
    {
        $year = (int) ($request->input('year', date('Y')));
        $action = $request->input('action', 'enable'); // enable, disable, delete

        if ($action === 'delete') {
            $deleted = AcademicHoliday::where('type', 'weekly_off')
                ->whereYear('start_date', $year)
                ->delete();
            return redirect()->back()->with('success', "Removed {$deleted} Sunday weekly off entries for {$year}.");
        }

        $newStatus = ($action === 'enable');
        $updated = AcademicHoliday::where('type', 'weekly_off')
            ->whereYear('start_date', $year)
            ->update(['status' => $newStatus]);

        $text = $newStatus ? 'enabled as Official Weekly Offs' : 'converted to Normal Working Days';
        return redirect()->back()->with('success', "All Sundays for {$year} have been {$text}.");
    }

    /**
     * AJAX 1-Click Fast Declare Holiday (used from Attendance Cockpit)
     */
    public function quickDeclare(Request $request)
    {
        $validated = $request->validate([
            'date'        => 'required|date',
            'title'       => 'required|string|max:255',
            'type'        => 'required|string|in:emergency,festival,event,weekly_off,other',
            'scope'       => 'required|string|in:all,students_only,staff_only',
            'description' => 'nullable|string|max:500',
        ]);

        $date = $validated['date'];
        $adminId = Auth::guard('tenant')->id();

        $holiday = AcademicHoliday::updateOrCreate(
            [
                'start_date' => $date,
                'end_date'   => $date,
            ],
            [
                'title'         => $validated['title'],
                'type'          => $validated['type'],
                'scope'         => $validated['scope'],
                'description'   => $validated['description'] ?? 'Instant Holiday Declaration from Attendance Cockpit',
                'status'        => true,
                'academic_year' => date('Y', strtotime($date)),
                'created_by'    => $adminId,
            ]
        );

        AcademicHoliday::autoMarkAttendanceForHoliday($holiday);

        return response()->json([
            'success' => true,
            'message' => "Holiday '{$holiday->title}' declared for " . date('d M Y', strtotime($date)) . " successfully.",
            'holiday' => $holiday,
        ]);
    }
}
