@extends('layouts.admin')
@section('title', 'Academic Calendar & Holidays')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

@php
    $monthsList = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];
    $todayStr = now()->format('Y-m-d');
@endphp

<div x-data="holidayManager()" x-cloak class="space-y-6">

    <!-- PAGE HEADER BANNER (FLAT, ZERO BORDERS) -->
    <div class="rounded-lg bg-primary p-5 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 mt-0.5">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div>
                <h1 class="text-base font-semibold text-theme-text leading-tight">
                    Academic Calendar &amp; Holidays Engine
                </h1>
                <p class="text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl">
                    Configure official holidays, cultural festivals, and institute recurring weekly offs. Holidays auto-mark zero-click attendance for students and faculty while supporting working day overrides.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap self-stretch lg:self-auto shrink-0">
            <button type="button" @click="openCreateModal()"
                    class="px-4 py-2 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer shadow-xs">
                <i class="fa-solid fa-plus text-[11px]"></i>
                <span>Declare Holiday</span>
            </button>

            <button type="button" @click="isWeeklyOffsModalOpen = true"
                    class="px-4 py-2 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-calendar-week text-[11px] text-brand"></i>
                <span>Manage Weekly Offs</span>
            </button>
        </div>
    </div>

    <!-- TOP KPI METRICS BAR -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <!-- Total Holidays -->
        <div class="rounded-lg bg-primary p-4 space-y-1">
            <span class="text-[11px] text-theme-text-muted font-normal block">Total Active Holidays</span>
            <div class="flex items-center justify-between">
                <span class="text-xl font-semibold text-theme-text">{{ $totalHolidaysCount }}</span>
                <span class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                    <i class="fa-solid fa-umbrella-beach"></i>
                </span>
            </div>
            <span class="text-[10px] text-theme-text-muted">In Year {{ $currentYear }}</span>
        </div>

        <!-- Festival Holidays -->
        <div class="rounded-lg bg-primary p-4 space-y-1">
            <span class="text-[11px] text-theme-text-muted font-normal block">Festival Holidays</span>
            <div class="flex items-center justify-between">
                <span class="text-xl font-semibold text-emerald-500">{{ $festivalCount }}</span>
                <span class="w-7 h-7 rounded-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-star-and-crescent"></i>
                </span>
            </div>
            <span class="text-[10px] text-theme-text-muted">Cultural &amp; National</span>
        </div>

        <!-- Recurring Weekly Offs -->
        <div class="rounded-lg bg-primary p-4 space-y-1">
            <span class="text-[11px] text-theme-text-muted font-normal block">Recurring Weekly Offs</span>
            <div class="flex items-center justify-between">
                <span class="text-xl font-semibold text-blue-500">{{ $weeklyOffCount }}</span>
                <span class="w-7 h-7 rounded-full bg-blue-500/10 text-blue-500 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-calendar-week"></i>
                </span>
            </div>
            <span class="text-[10px] text-theme-text-muted">Scheduled Weekly Offs</span>
        </div>

        <!-- Emergency / Ad-Hoc -->
        <div class="rounded-lg bg-primary p-4 space-y-1">
            <span class="text-[11px] text-theme-text-muted font-normal block">Emergency Closures</span>
            <div class="flex items-center justify-between">
                <span class="text-xl font-semibold text-amber-500">{{ $emergencyCount }}</span>
                <span class="w-7 h-7 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-cloud-showers-heavy"></i>
                </span>
            </div>
            <span class="text-[10px] text-theme-text-muted">Weather / Urgent Orders</span>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- TOP SECTION: LEFT (DATE & HOLIDAY INSPECTOR) | RIGHT (CALENDAR) -->
    <!-- ============================================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">

        <!-- ── LEFT BLOCK: DATE & HOLIDAY INSPECTOR (5 COLS) ────────────── -->
        <div class="lg:col-span-5 rounded-lg bg-primary p-5 flex flex-col justify-between space-y-4">
            
            <div class="space-y-4">
                <!-- Inspector Header -->
                <div class="flex items-center justify-between border-b-0 pb-1">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs font-semibold">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <h2 class="text-sm font-semibold text-theme-text">Date &amp; Holiday Details</h2>
                    </div>

                    <!-- Jump to Today or Live Badge -->
                    <div>
                        <template x-if="selectedDate !== todayStr">
                            <button type="button" @click="selectDate(todayStr)"
                                    class="text-[11px] text-brand hover:underline font-normal inline-flex items-center gap-1 cursor-pointer">
                                <i class="fa-solid fa-bolt text-[10px]"></i> Jump to Today
                            </button>
                        </template>
                        <template x-if="selectedDate === todayStr">
                            <span class="text-[10px] text-success font-medium inline-flex items-center gap-1">
                                <i class="fa-solid fa-circle-dot text-[8px] animate-pulse"></i> Today's Live Date
                            </span>
                        </template>
                    </div>
                </div>

                <!-- 1. DATE OVERVIEW CARD -->
                <div class="rounded-lg bg-secondary p-4 flex items-center justify-between gap-3 flex-wrap">
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-lg bg-primary flex flex-col items-center justify-center shrink-0 shadow-xs leading-tight">
                            <span class="text-[10px] text-brand uppercase font-semibold tracking-wider" x-text="selectedDateMonthName"></span>
                            <span class="text-xl font-semibold text-theme-text mt-0.5" x-text="selectedDateDayNum"></span>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-theme-text" x-text="formattedSelectedDate"></h3>
                            <p class="text-[11px] text-theme-text-muted mt-0.5" x-text="selectedDateDayOfWeek"></p>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div>
                        <template x-if="selectedDateHoliday && selectedDateHoliday.status">
                            <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-500 text-xs font-semibold inline-flex items-center gap-1.5 shadow-xs">
                                <i class="fa-solid fa-umbrella-beach"></i> Official Holiday
                            </span>
                        </template>
                        <template x-if="selectedDateHoliday && !selectedDateHoliday.status">
                            <span class="px-3 py-1 rounded-full bg-amber-500/10 text-amber-500 text-xs font-semibold inline-flex items-center gap-1.5 shadow-xs">
                                <i class="fa-solid fa-briefcase"></i> Working Day Override
                            </span>
                        </template>
                        <template x-if="!selectedDateHoliday && selectedDateFestival">
                            <span class="px-3 py-1 rounded-full bg-amber-500/10 text-amber-500 text-xs font-semibold inline-flex items-center gap-1.5 shadow-xs">
                                <i class="fa-solid fa-briefcase"></i> Working Day (Festival Day)
                            </span>
                        </template>
                        <template x-if="!selectedDateHoliday && !selectedDateFestival">
                            <span class="px-3 py-1 rounded-full bg-primary text-theme-text-muted text-xs font-medium inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-building-columns text-[11px]"></i> Normal Working Day
                            </span>
                        </template>
                    </div>
                </div>

                <!-- 2. FESTIVAL BLOCK (DEDICATED FESTIVAL CARD - BG-SECONDARY & BRAND THEME - NO DELETE BUTTON) -->
                <template x-if="selectedDateFestival">
                    <div class="rounded-lg bg-secondary p-4 space-y-3">
                        <!-- Header with Cultural Tag -->
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs shrink-0">
                                    <i class="fa-solid fa-star-and-crescent text-sm"></i>
                                </span>
                                <div>
                                    <span class="text-[10px] text-theme-text-muted uppercase font-medium block">Cultural / Calendar Occasion</span>
                                    <h4 class="text-sm font-semibold text-theme-text" x-text="selectedDateFestival.title"></h4>
                                </div>
                            </div>
                            <span class="text-[10px] px-2.5 py-0.5 rounded-full bg-brand/10 text-brand font-medium">
                                Cultural Festival
                            </span>
                        </div>

                        <!-- Description -->
                        <p class="text-[11px] text-theme-text-muted leading-relaxed"
                           x-text="selectedDateFestival.desc || 'Celebrated cultural and national festival occasion.'"></p>

                        <!-- Festival Status & Actions (Mark as Holiday / Working Day only, NO DELETE BUTTON) -->
                        <div class="pt-1">
                            <!-- Case A: Festival is already declared as an ACTIVE Holiday -->
                            <template x-if="selectedDateHoliday && selectedDateHoliday.status">
                                <div class="p-3.5 rounded-lg bg-primary space-y-2.5 shadow-xs">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-xs text-brand font-medium inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-circle-check"></i> Declared Official Holiday
                                        </span>
                                        <span class="text-[10px] text-theme-text-muted" x-text="selectedDateHoliday.scope_label"></span>
                                    </div>
                                    <p class="text-[11px] text-theme-text-muted leading-relaxed">
                                        All classroom sessions and lectures are suspended. Auto-attendance is set to holiday for students and faculty.
                                    </p>
                                    <div class="pt-1 flex items-center gap-2">
                                        <button type="button" @click="promptToggleWorkingDay(selectedDateHoliday)"
                                                class="px-4 py-2 rounded-full bg-secondary hover:bg-subtle text-theme-text text-xs font-normal transition inline-flex items-center gap-1.5 cursor-pointer">
                                            <i class="fa-solid fa-briefcase text-amber-500"></i>
                                            <span>Mark as Working Day</span>
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <!-- Case B: Festival is NOT an active holiday (Currently open Working Day) -->
                            <template x-if="!selectedDateHoliday || !selectedDateHoliday.status">
                                <div class="p-3.5 rounded-lg bg-primary space-y-2.5 shadow-xs">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-xs text-amber-500 font-medium inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-briefcase"></i> Currently Open Working Day
                                        </span>
                                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-secondary text-theme-text-muted font-medium">Holiday Overridden</span>
                                    </div>

                                    <!-- Recommendation Alert -->
                                    <div class="p-2.5 rounded-lg bg-secondary text-[11px] text-theme-text space-y-1">
                                        <div class="flex items-center gap-1.5 font-medium text-amber-500">
                                            <i class="fa-solid fa-lightbulb"></i>
                                            <span>Festival Suggestion</span>
                                        </div>
                                        <p class="text-theme-text-muted leading-relaxed">
                                            Today is <strong class="text-theme-text" x-text="selectedDateFestival.title"></strong>. Classes and regular attendance are currently scheduled. It is recommended to observe this festival as an official holiday.
                                        </p>
                                    </div>

                                    <!-- Actions: Mark as Holiday (with confirmation modal) -->
                                    <div class="pt-1 flex items-center gap-2">
                                        <template x-if="selectedDateHoliday && !selectedDateHoliday.status">
                                            <button type="button" @click="promptToggleWorkingDay(selectedDateHoliday)"
                                                    class="px-4 py-2 bg-brand text-primary hover:opacity-90 rounded-full text-xs font-normal transition inline-flex items-center gap-1.5 cursor-pointer shadow-xs">
                                                <i class="fa-solid fa-umbrella-beach"></i>
                                                <span>Mark as Holiday</span>
                                            </button>
                                        </template>

                                        <template x-if="!selectedDateHoliday">
                                            <button type="button" @click="openCreateModal(selectedDate, selectedDateFestival.title, selectedDateFestival.desc)"
                                                    class="px-4 py-2 bg-brand text-primary hover:opacity-90 rounded-full text-xs font-normal transition inline-flex items-center gap-1.5 cursor-pointer shadow-xs">
                                                <i class="fa-solid fa-umbrella-beach"></i>
                                                <span>Declare Holiday for This Festival</span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- 3. HOLIDAY BLOCK (DEDICATED HOLIDAY CARD) -->
                <!-- Shown when a holiday record exists and is either not a festival holiday or if no festival block was rendered -->
                <template x-if="selectedDateHoliday && (selectedDateHoliday.type !== 'festival' || !selectedDateFestival)">
                    <div class="rounded-lg bg-secondary p-4 space-y-3">
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-full flex items-center justify-center text-xs shrink-0"
                                      :class="{
                                          'bg-rose-500/15 text-rose-500': selectedDateHoliday.type === 'weekly_off',
                                          'bg-amber-500/15 text-amber-500': selectedDateHoliday.type === 'emergency',
                                          'bg-brand/15 text-brand': selectedDateHoliday.type !== 'weekly_off' && selectedDateHoliday.type !== 'emergency'
                                      }">
                                    <i class="fa-solid"
                                       :class="{
                                           'fa-calendar-week': selectedDateHoliday.type === 'weekly_off',
                                           'fa-cloud-showers-heavy': selectedDateHoliday.type === 'emergency',
                                           'fa-umbrella-beach': selectedDateHoliday.type !== 'weekly_off' && selectedDateHoliday.type !== 'emergency'
                                       }"></i>
                                </span>
                                <div>
                                    <span class="text-[10px] text-theme-text-muted uppercase font-medium block">Academic Holiday Record</span>
                                    <h4 class="text-sm font-semibold text-theme-text" x-text="selectedDateHoliday.title"></h4>
                                </div>
                            </div>

                            <div class="flex items-center gap-1.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium"
                                      :class="{
                                          'bg-rose-500/15 text-rose-500': selectedDateHoliday.type === 'weekly_off',
                                          'bg-amber-500/15 text-amber-500': selectedDateHoliday.type === 'emergency',
                                          'bg-brand/15 text-brand': selectedDateHoliday.type !== 'weekly_off' && selectedDateHoliday.type !== 'emergency'
                                      }"
                                      x-text="selectedDateHoliday.type_label">
                                </span>
                                <span class="px-2 py-0.5 rounded-full bg-primary text-theme-text-muted text-[10px]"
                                      x-text="selectedDateHoliday.scope_label">
                                </span>
                            </div>
                        </div>

                        <p class="text-[11px] text-theme-text-muted leading-relaxed"
                           x-text="selectedDateHoliday.description || 'Declared institute holiday closure.'"></p>

                        <!-- Actions for this Holiday Record -->
                        <div class="pt-2 flex items-center gap-2 flex-wrap">
                            <!-- Working Day Toggle Button (Triggers Confirmation Modal!) -->
                            <button type="button" @click="promptToggleWorkingDay(selectedDateHoliday)"
                                    class="px-4 py-2 rounded-full text-xs font-normal transition inline-flex items-center gap-1.5 cursor-pointer"
                                    :class="selectedDateHoliday.status ? 'bg-amber-500/15 text-amber-600 hover:bg-amber-500/25' : 'bg-emerald-500/15 text-emerald-600 hover:bg-emerald-500/25'">
                                <i class="fa-solid" :class="selectedDateHoliday.status ? 'fa-briefcase' : 'fa-umbrella-beach'"></i>
                                <span x-text="selectedDateHoliday.status ? 'Mark as Working Day' : 'Reactivate as Holiday'"></span>
                            </button>

                            <!-- Edit Details -->
                            <button type="button" @click="editHoliday(selectedDateHoliday)"
                                    class="px-3.5 py-2 rounded-full bg-primary text-theme-text hover:bg-subtle text-xs font-normal transition inline-flex items-center gap-1.5 cursor-pointer">
                                <i class="fa-solid fa-pen text-[10px]"></i>
                                <span>Edit</span>
                            </button>
                        </div>
                    </div>
                </template>

                <!-- 4. NORMAL WORKING DAY STATE (NO FESTIVAL & NO HOLIDAY) -->
                <template x-if="!selectedDateHoliday && !selectedDateFestival">
                    <div class="rounded-lg bg-secondary p-4 space-y-3 text-xs">
                        <div class="flex items-center gap-2 text-theme-text font-medium">
                            <i class="fa-solid fa-building-columns text-brand"></i>
                            <span>Normal Academic Working Day</span>
                        </div>
                        <p class="text-[11px] text-theme-text-muted leading-relaxed">
                            No holiday, festival, or official closure is scheduled for this date. All lectures, classroom attendance, and faculty duties proceed normally.
                        </p>
                        <div>
                            <button type="button" @click="openCreateModal(selectedDate)"
                                    class="w-full py-2.5 bg-brand text-primary rounded-full text-xs font-normal hover:opacity-90 transition flex items-center justify-center gap-2 shadow-xs cursor-pointer">
                                <i class="fa-solid fa-umbrella-beach text-xs"></i>
                                <span>Declare Holiday for This Date</span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Next Upcoming Holiday Quick Glance Card -->
            @if($nextUpcoming)
                <div class="rounded-lg bg-secondary/70 p-3.5 flex items-center justify-between text-xs gap-3">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <span class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center shrink-0 text-xs">
                            <i class="fa-solid fa-bell"></i>
                        </span>
                        <div class="min-w-0">
                            <span class="text-[10px] text-theme-text-muted block">Next Upcoming Holiday:</span>
                            <strong class="text-xs font-semibold text-theme-text truncate block">{{ $nextUpcoming->title }}</strong>
                        </div>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full bg-brand text-primary text-[10px] font-semibold shrink-0">
                        {{ $nextUpcoming->countdown_label }} ({{ $nextUpcoming->start_date->format('d M') }})
                    </span>
                </div>
            @endif

        </div>

        <!-- ── RIGHT BLOCK: INTERACTIVE MONTHLY CALENDAR (7 COLS) ───────── -->
        <div class="lg:col-span-7 rounded-lg bg-primary p-5 space-y-4">
            
            <!-- Calendar Navigation Header -->
            <div class="flex items-center justify-between flex-wrap gap-2">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-brand"></span>
                    <h3 class="text-sm font-semibold text-theme-text">
                        {{ $monthsList[$currentMonth] }} {{ $currentYear }}
                    </h3>
                </div>

                <!-- Month Prev / Next Switcher -->
                <div class="flex items-center gap-1.5">
                    @php
                        $prevMonth = $currentMonth - 1;
                        $prevYear = $currentYear;
                        if ($prevMonth < 1) { $prevMonth = 12; $prevYear--; }

                        $nextMonth = $currentMonth + 1;
                        $nextYear = $currentYear;
                        if ($nextMonth > 12) { $nextMonth = 1; $nextYear++; }
                    @endphp

                    <a href="{{ route('admin.holidays.index', ['year' => $prevYear, 'month' => $prevMonth]) }}"
                       class="w-7 h-7 rounded-full bg-secondary text-theme-text hover:bg-subtle flex items-center justify-center text-xs transition">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </a>

                    <a href="{{ route('admin.holidays.index', ['year' => date('Y'), 'month' => date('n')]) }}"
                       class="px-2.5 py-1 rounded-full bg-secondary text-theme-text hover:bg-subtle text-[11px] font-normal transition">
                        Today
                    </a>

                    <a href="{{ route('admin.holidays.index', ['year' => $nextYear, 'month' => $nextMonth]) }}"
                       class="w-7 h-7 rounded-full bg-secondary text-theme-text hover:bg-subtle flex items-center justify-center text-xs transition">
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Calendar Days Header (SUN to SAT) -->
            <div class="grid grid-cols-7 gap-1.5 text-center text-[11px] font-semibold tracking-wider">
                <div class="py-1 text-rose-500">SUN</div>
                <div class="py-1 text-theme-text-muted">MON</div>
                <div class="py-1 text-theme-text-muted">TUE</div>
                <div class="py-1 text-theme-text-muted">WED</div>
                <div class="py-1 text-theme-text-muted">THU</div>
                <div class="py-1 text-theme-text-muted">FRI</div>
                <div class="py-1 text-theme-text-muted">SAT</div>
            </div>

            <!-- Monthly Calendar Grid -->
            <div class="grid grid-cols-7 gap-1.5">
                <!-- Empty day slots for alignment before 1st of month -->
                @for($blank = 0; $blank < $startDayOfWeek; $blank++)
                    <div class="aspect-square rounded-lg bg-secondary/30"></div>
                @endfor

                <!-- Month Day Cells -->
                @foreach($calendarDays as $cell)
                    <button type="button"
                            @click="selectDate('{{ $cell['date'] }}')"
                            class="aspect-square rounded-lg flex flex-col items-center justify-center p-1 relative text-xs transition cursor-pointer select-none"
                            :class="{
                                'ring-2 ring-brand': selectedDate === '{{ $cell['date'] }}',
                                'bg-secondary': !selectedDate || selectedDate !== '{{ $cell['date'] }}',
                                'bg-brand/15 text-brand': '{{ $cell['is_today'] }}' && selectedDate !== '{{ $cell['date'] }}',
                            }">
                        
                        <!-- Day Number -->
                        <span class="text-xs leading-none"
                              :class="{
                                  'font-semibold text-brand': '{{ $cell['is_today'] }}',
                                  'text-rose-500 font-medium': ('{{ $cell['is_weekly_off_day'] }}' || '{{ $cell['is_sunday'] }}') && !'{{ $cell['is_today'] }}',
                                  'text-theme-text font-normal': !('{{ $cell['is_weekly_off_day'] }}' || '{{ $cell['is_sunday'] }}') && !'{{ $cell['is_today'] }}'
                              }">
                            {{ $cell['day'] }}
                        </span>

                        <!-- Indicator Dots / Badges -->
                        @if($cell['is_holiday'])
                            <span class="w-1.5 h-1.5 rounded-full mt-1 shrink-0"
                                  title="{{ $cell['holiday']['title'] }}"
                                  :class="{
                                      'bg-rose-500': '{{ $cell['holiday']['type'] }}' === 'weekly_off',
                                      'bg-emerald-500': '{{ $cell['holiday']['type'] }}' === 'festival',
                                      'bg-amber-500': '{{ $cell['holiday']['type'] }}' === 'emergency',
                                      'bg-brand': '{{ $cell['holiday']['type'] }}' !== 'weekly_off' && '{{ $cell['holiday']['type'] }}' !== 'festival' && '{{ $cell['holiday']['type'] }}' !== 'emergency'
                                  }">
                            </span>
                        @elseif($cell['is_overridden'])
                            <span class="w-1.5 h-1.5 rounded-full mt-1 shrink-0 border border-amber-500 bg-amber-500/20"
                                  title="{{ $cell['holiday']['title'] ?? 'Holiday' }} (Working Day Override)">
                            </span>
                        @elseif($cell['has_festival'])
                            <span class="w-1.5 h-1.5 rounded-full mt-1 shrink-0 border border-emerald-500 bg-emerald-500/20"
                                  title="{{ $cell['festival_name'] }} (Cultural Festival)">
                            </span>
                        @endif
                    </button>
                @endforeach
            </div>

            <!-- Mini Calendar Legend -->
            <div class="flex items-center justify-between text-[10px] text-theme-text-muted pt-2 border-t border-subtle/40 flex-wrap gap-2">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Festival
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span> Weekly Off
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span> Emergency
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full border border-amber-500 bg-amber-500/20"></span> Working Day Override
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-brand"></span> Today
                </span>
            </div>

        </div>

    </div>

    <!-- ============================================================== -->
    <!-- BOTTOM SECTION: FULL SCREEN HOLIDAY DIRECTORY & LEDGER         -->
    <!-- ============================================================== -->
    <div class="rounded-lg bg-primary p-5 space-y-4">
        
        <!-- Header & Action Buttons -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 border-b-0 pb-1">
            <div>
                <h3 class="text-sm font-semibold text-theme-text">Academic Holidays &amp; Calendar Directory</h3>
                <p class="text-xs text-theme-text-muted mt-0.5">Chronological schedule for academic year {{ $currentYear }} &bull; Past holidays above, current/next highlighted, upcoming holidays below</p>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <!-- <button type="button" @click="scrollToCurrentOrNext()"
                        class="px-3.5 py-1.5 rounded-full bg-secondary text-brand text-xs hover:bg-subtle transition inline-flex items-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-crosshairs text-[10px]"></i>
                    <span>Jump to Current / Next</span>
                </button> -->

                <button type="button" @click="isWeeklyOffsModalOpen = true"
                        class="px-3.5 py-1.5 rounded-full bg-secondary text-theme-text text-xs hover:bg-subtle transition inline-flex items-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-calendar-week text-brand text-[10px]"></i>
                    <span>Manage Weekly Offs</span>
                </button>

                <button type="button" @click="openCreateModal()"
                        class="px-4 py-1.5 rounded-full bg-brand text-primary text-xs font-medium hover:opacity-90 transition inline-flex items-center gap-1.5 cursor-pointer shadow-xs">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>+ Declare Holiday</span>
                </button>
            </div>
        </div>

        <!-- Filters & Search Toolbar (Search Bar + Category Filters Ahead) -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-start gap-3 text-xs flex-wrap">
            <!-- Search Input -->
            <div class="relative w-full sm:w-64 shrink-0">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-theme-text-muted text-xs"></i>
                <input type="text" x-model="searchQuery" placeholder="Search holidays by name or notes..."
                       class="w-full bg-secondary text-theme-text text-xs pl-9 pr-3.5 py-2 rounded-full focus:outline-none">
            </div>

            <!-- Category Filter Pills (Ahead of Search Bar) -->
            <div class="flex items-center gap-1 bg-secondary p-1 rounded-full overflow-x-auto">
                <button type="button" @click="activeFilter = 'all'"
                        class="px-3 py-1 rounded-full text-xs transition cursor-pointer whitespace-nowrap"
                        :class="activeFilter === 'all' ? 'bg-primary text-theme-text font-medium shadow-xs' : 'text-theme-text-muted hover:text-theme-text'">
                    All ({{ $holidays->count() }})
                </button>
                <button type="button" @click="activeFilter = 'festival'"
                        class="px-3 py-1 rounded-full text-xs transition cursor-pointer whitespace-nowrap"
                        :class="activeFilter === 'festival' ? 'bg-primary text-theme-text font-medium shadow-xs' : 'text-theme-text-muted hover:text-theme-text'">
                    Festivals ({{ $festivalCount }})
                </button>
                <button type="button" @click="activeFilter = 'weekly_off'"
                        class="px-3 py-1 rounded-full text-xs transition cursor-pointer whitespace-nowrap"
                        :class="activeFilter === 'weekly_off' ? 'bg-primary text-theme-text font-medium shadow-xs' : 'text-theme-text-muted hover:text-theme-text'">
                    Weekly Offs ({{ $weeklyOffCount }})
                </button>
                <button type="button" @click="activeFilter = 'emergency'"
                        class="px-3 py-1 rounded-full text-xs transition cursor-pointer whitespace-nowrap"
                        :class="activeFilter === 'emergency' ? 'bg-primary text-theme-text font-medium shadow-xs' : 'text-theme-text-muted hover:text-theme-text'">
                    Emergency ({{ $emergencyCount }})
                </button>
            </div>
        </div>

        <!-- Full-Width Seamless List of Holidays (Past holidays above, Current/Next in middle, Future below) -->
        <div id="holidaysScrollContainer" class="space-y-2 max-h-[650px] overflow-y-auto pr-1 scroll-smooth">
            @forelse($holidays as $h)
                @php
                    $isSingleDay = $h->start_date->equalTo($h->end_date);
                    $isNextOne = ($nextUpcoming && $nextUpcoming->id === $h->id);
                @endphp

                <div @if($isNextOne) id="currentOrNextHolidayRow" @endif
                     class="rounded-lg p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs transition hover:bg-subtle/60 {{ $isNextOne ? 'bg-secondary border-2 border-brand shadow-sm' : ($h->is_past ? 'bg-secondary/60 opacity-80' : 'bg-secondary') }}"
                     x-show="
                        (activeFilter === 'all' || activeFilter === '{{ $h->type }}') &&
                        (!searchQuery || '{{ strtolower(addslashes($h->title)) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower(addslashes($h->description ?? '')) }}'.includes(searchQuery.toLowerCase()))
                     ">
                    
                    <!-- Left Info -->
                    <div class="flex items-start gap-3.5 min-w-0">
                        <!-- Date Badge -->
                        <div class="w-12 h-12 rounded-lg flex flex-col items-center justify-center shrink-0 text-center leading-tight shadow-xs {{ $isNextOne ? 'bg-brand text-primary font-semibold' : ($h->is_past ? 'bg-primary/60 text-theme-text-muted' : 'bg-primary') }}">
                            <span class="text-[10px] {{ $isNextOne ? 'text-primary' : 'text-brand' }} uppercase font-semibold">{{ $h->start_date->format('M') }}</span>
                            <span class="text-sm font-semibold {{ $isNextOne ? 'text-primary' : 'text-theme-text' }}">{{ $h->start_date->format('d') }}</span>
                        </div>

                        <div class="min-w-0 space-y-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-xs font-semibold text-theme-text truncate">{{ $h->title }}</span>

                                <!-- Current / Next Indicator Badge -->
                                @if($isNextOne)
                                    <span class="px-2.5 py-0.5 rounded-full bg-brand text-primary text-[10px] font-semibold inline-flex items-center gap-1 shadow-xs">
                                        <i class="fa-solid fa-bolt text-[9px]"></i> {{ $h->countdown_label }}
                                    </span>
                                @elseif(!$h->is_past && $h->countdown_label !== 'Upcoming')
                                    <span class="px-2 py-0.5 rounded-full bg-secondary text-brand text-[10px] font-medium">
                                        {{ $h->countdown_label }}
                                    </span>
                                @elseif($h->is_past)
                                    <span class="px-2 py-0.5 rounded-full bg-primary text-theme-text-muted text-[10px]">
                                        Past Holiday
                                    </span>
                                @endif
                                
                                <!-- Type Pill -->
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-normal"
                                      :class="{
                                          'bg-emerald-500/10 text-emerald-500': '{{ $h->type }}' === 'festival',
                                          'bg-rose-500/10 text-rose-500': '{{ $h->type }}' === 'weekly_off',
                                          'bg-amber-500/10 text-amber-500': '{{ $h->type }}' === 'emergency',
                                          'bg-brand/10 text-brand': '{{ $h->type }}' !== 'festival' && '{{ $h->type }}' !== 'weekly_off' && '{{ $h->type }}' !== 'emergency'
                                      }">
                                    {{ $h->type_label }}
                                </span>

                                <!-- Scope Pill -->
                                <span class="px-2 py-0.5 rounded-full bg-primary text-theme-text-muted text-[10px] font-normal">
                                    {{ $h->scope_label }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2 text-[11px] text-theme-text-muted flex-wrap">
                                <span>
                                    @if($isSingleDay)
                                        {{ $h->start_date->format('l, d M Y') }}
                                    @else
                                        {{ $h->start_date->format('d M') }} &ndash; {{ $h->end_date->format('d M Y') }} ({{ $h->duration_days }} Days)
                                    @endif
                                </span>

                                @if($h->description)
                                    <span>&bull;</span>
                                    <span class="truncate max-w-md">{{ $h->description }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right Actions & Toggle (WITH CONFIRMATION DIALOG!) -->
                    <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                        <!-- Toggle Button (Calls promptToggleWorkingDay for confirmation!) -->
                        <button type="button"
                                @click="promptToggleWorkingDay({{ json_encode([
                                    'id'             => $h->id,
                                    'title'          => $h->title,
                                    'status'         => (bool) $h->status,
                                    'date_formatted' => $h->start_date->format('l, d M Y')
                                ]) }})"
                                title="{{ $h->status ? 'Click to make this a normal working day' : 'Click to reactivate as holiday' }}"
                                class="px-2.5 py-1 rounded-full text-[11px] font-normal transition inline-flex items-center gap-1 cursor-pointer"
                                :class="{{ $h->status ? 'true' : 'false' }} ? 'bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500/20' : 'bg-primary text-theme-text-muted hover:text-theme-text'">
                            <i class="fa-solid" :class="{{ $h->status ? 'true' : 'false' }} ? 'fa-check' : 'fa-briefcase'"></i>
                            <span>{{ $h->status ? 'Holiday Active' : 'Working Day' }}</span>
                        </button>

                        <!-- Edit button -->
                        <button type="button"
                                @click="editHolidayById({{ $h->id }})"
                                title="Edit details"
                                class="w-7 h-7 rounded-full bg-primary text-theme-text hover:bg-subtle flex items-center justify-center text-xs transition cursor-pointer">
                            <i class="fa-solid fa-pen text-[11px]"></i>
                        </button>

                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-xs text-theme-text-muted bg-secondary rounded-lg">
                    No holidays found for {{ $currentYear }}. Standard festivals and weekly offs are automatically configured for this academic year.
                </div>
            @endforelse
        </div>

    </div>

    <!-- ============================================================== -->
    <!-- MODAL 0: CONFIRMATION MODAL FOR WORKING DAY / HOLIDAY TOGGLE   -->
    <!-- ============================================================== -->
    <div x-show="isConfirmModalOpen"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         @keydown.escape.window="isConfirmModalOpen = false">

        <div class="bg-primary rounded-lg shadow-2xl w-full max-w-md overflow-hidden flex flex-col"
             @click.away="isConfirmModalOpen = false">

            <!-- Modal Header -->
            <div class="p-5 flex items-center justify-between bg-secondary/50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold"
                         :class="confirmHoliday?.status ? 'bg-amber-500/10 text-amber-500' : 'bg-emerald-500/10 text-emerald-500'">
                        <i class="fa-solid" :class="confirmHoliday?.status ? 'fa-briefcase' : 'fa-umbrella-beach'"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-theme-text" x-text="confirmModalTitle"></h2>
                        <p class="text-[11px] text-theme-text-muted">Working status confirmation</p>
                    </div>
                </div>
                <button type="button" @click="isConfirmModalOpen = false" class="w-8 h-8 rounded-full bg-secondary text-theme-text hover:bg-subtle flex items-center justify-center text-xs transition cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-5 space-y-3.5 text-xs text-theme-text">
                <p class="leading-relaxed" x-text="confirmModalMessage"></p>
                
                <div class="p-3.5 rounded-lg bg-secondary space-y-1">
                    <div class="font-semibold text-theme-text text-sm" x-text="confirmHoliday?.title"></div>
                    <div class="text-[11px] text-theme-text-muted flex items-center gap-1.5">
                        <i class="fa-regular fa-calendar"></i>
                        <span x-text="confirmHoliday?.date_formatted"></span>
                    </div>
                </div>

                <p class="text-[11px] text-theme-text-muted italic"
                   x-text="confirmHoliday?.status ? 'Note: Converting to a working day will allow regular attendance and class lectures on this date.' : 'Note: Reactivating as holiday will automatically mark attendance as Holiday for all students and faculty.'">
                </p>
            </div>

            <!-- Modal Footer -->
            <div class="p-4 bg-secondary/40 flex items-center justify-end gap-2.5">
                <button type="button" @click="isConfirmModalOpen = false"
                        class="px-5 py-2 bg-secondary text-theme-text rounded-full text-xs font-normal hover:bg-subtle transition cursor-pointer">
                    Cancel
                </button>

                <form :action="'{{ url('admin/holidays') }}/' + confirmHoliday?.id + '/toggle-status'" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                            class="px-6 py-2 rounded-full text-xs font-medium transition cursor-pointer text-white shadow-xs"
                            :class="confirmHoliday?.status ? 'bg-amber-600 hover:bg-amber-700' : 'bg-emerald-600 hover:bg-emerald-700'"
                            x-text="confirmHoliday?.status ? 'Yes, Make Working Day' : 'Yes, Activate Holiday'">
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 1: ADD / DECLARE HOLIDAY MODAL                           -->
    <!-- ============================================================== -->
    <div x-show="isCreateModalOpen"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         @keydown.escape.window="isCreateModalOpen = false">

        <div class="bg-primary rounded-lg shadow-2xl w-full max-w-md overflow-hidden flex flex-col"
             @click.away="isCreateModalOpen = false">

            <!-- Modal Header -->
            <div class="p-5 flex items-center justify-between bg-secondary/50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs font-semibold">
                        <i class="fa-solid fa-umbrella-beach"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-theme-text">Declare Academic Holiday</h2>
                        <p class="text-[11px] text-theme-text-muted">Set festival, emergency closure, or custom holiday</p>
                    </div>
                </div>
                <button type="button" @click="isCreateModalOpen = false" class="w-8 h-8 rounded-full bg-secondary text-theme-text hover:bg-subtle flex items-center justify-center text-xs transition cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Modal Form (Container bg-primary -> Input bg-secondary) -->
            <form method="POST" action="{{ route('admin.holidays.store') }}" class="p-5 space-y-4">
                @csrf
                <!-- Title -->
                <div>
                    <label class="block text-xs font-medium text-theme-text mb-1">Holiday Title *</label>
                    <input type="text" name="title" x-model="formTitle" required placeholder="e.g. Eid-ul-Fitr, Annual Sports Day"
                           class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none">
                </div>

                <!-- Date Range (Start & End) -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-theme-text mb-1">Start Date *</label>
                        <input type="date" name="start_date" x-model="formStartDate" required
                               class="w-full bg-secondary text-theme-text text-xs px-3.5 py-2 rounded-full focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-theme-text mb-1">End Date</label>
                        <input type="date" name="end_date" x-model="formEndDate"
                               class="w-full bg-secondary text-theme-text text-xs px-3.5 py-2 rounded-full focus:outline-none">
                    </div>
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-xs font-medium text-theme-text mb-1">Holiday Classification *</label>
                    <select name="type" x-model="formType" required
                            class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none">
                        <option value="festival">Festival / Cultural Celebration</option>
                        <option value="emergency">Emergency Closure (Weather, Alert, Orders)</option>
                        <option value="event">Institute Annual Event / Sports Day</option>
                        <option value="vacation">Academic Vacation / Semester Break</option>
                        <option value="weekly_off">Weekly Off / Institute Recess</option>
                        <option value="other">Other Official Closure</option>
                    </select>
                </div>

                <!-- Scope -->
                <div>
                    <label class="block text-xs font-medium text-theme-text mb-1">Applicable Target *</label>
                    <select name="scope" x-model="formScope" required
                            class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none">
                        <option value="all">Whole Institute (All Students &amp; Faculty)</option>
                        <option value="students_only">Students Only (Faculty on Duty)</option>
                        <option value="staff_only">Staff / Faculty Only</option>
                    </select>
                </div>

                <!-- Notes / Description -->
                <div>
                    <label class="block text-xs font-medium text-theme-text mb-1">Notes / Description (Optional)</label>
                    <textarea name="description" x-model="formDesc" rows="2" placeholder="Provide any additional notice or guidelines..."
                              class="w-full bg-secondary text-theme-text text-xs p-3 rounded-lg focus:outline-none"></textarea>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="isCreateModalOpen = false"
                            class="px-5 py-2 bg-secondary text-theme-text rounded-full text-xs font-normal hover:bg-subtle transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-brand text-primary rounded-full text-xs font-semibold hover:opacity-90 transition cursor-pointer shadow-xs">
                        Save Holiday
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 2: EDIT HOLIDAY MODAL                                    -->
    <!-- ============================================================== -->
    <div x-show="isEditModalOpen"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         @keydown.escape.window="isEditModalOpen = false">

        <div class="bg-primary rounded-lg shadow-2xl w-full max-w-md overflow-hidden flex flex-col"
             @click.away="isEditModalOpen = false">

            <div class="p-5 flex items-center justify-between bg-secondary/50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs font-semibold">
                        <i class="fa-solid fa-pen"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-theme-text">Edit Holiday Details</h2>
                        <p class="text-[11px] text-theme-text-muted">Update title, date range, or holiday classification</p>
                    </div>
                </div>
                <button type="button" @click="isEditModalOpen = false" class="w-8 h-8 rounded-full bg-secondary text-theme-text hover:bg-subtle flex items-center justify-center text-xs transition cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" :action="'{{ url('admin/holidays') }}/' + editForm.id" class="p-5 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-medium text-theme-text mb-1">Holiday Title *</label>
                    <input type="text" name="title" x-model="editForm.title" required
                           class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-theme-text mb-1">Start Date *</label>
                        <input type="date" name="start_date" x-model="editForm.start_date" required
                               class="w-full bg-secondary text-theme-text text-xs px-3.5 py-2 rounded-full focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-theme-text mb-1">End Date</label>
                        <input type="date" name="end_date" x-model="editForm.end_date"
                               class="w-full bg-secondary text-theme-text text-xs px-3.5 py-2 rounded-full focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-theme-text mb-1">Holiday Classification *</label>
                    <select name="type" x-model="editForm.type" required
                            class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none">
                        <option value="festival">Festival / Cultural Celebration</option>
                        <option value="emergency">Emergency Closure (Weather, Alert, Orders)</option>
                        <option value="event">Institute Annual Event / Sports Day</option>
                        <option value="vacation">Academic Vacation / Semester Break</option>
                        <option value="weekly_off">Weekly Off / Institute Recess</option>
                        <option value="other">Other Official Closure</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-theme-text mb-1">Applicable Target *</label>
                    <select name="scope" x-model="editForm.scope" required
                            class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none">
                        <option value="all">Whole Institute (All Students &amp; Faculty)</option>
                        <option value="students_only">Students Only (Faculty on Duty)</option>
                        <option value="staff_only">Staff / Faculty Only</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-theme-text mb-1">Notes / Description (Optional)</label>
                    <textarea name="description" x-model="editForm.description" rows="2"
                              class="w-full bg-secondary text-theme-text text-xs p-3 rounded-lg focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="isEditModalOpen = false"
                            class="px-5 py-2 bg-secondary text-theme-text rounded-full text-xs font-normal hover:bg-subtle transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-brand text-primary rounded-full text-xs font-semibold hover:opacity-90 transition cursor-pointer shadow-xs">
                        Update Holiday
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 3: MANAGE INSTITUTE RECURRING WEEKLY OFFS (ALL 7 DAYS)     -->
    <!-- ============================================================== -->
    <div x-show="isWeeklyOffsModalOpen"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         @keydown.escape.window="isWeeklyOffsModalOpen = false">

        <div class="bg-primary rounded-lg shadow-2xl w-full max-w-lg overflow-hidden flex flex-col"
             @click.away="isWeeklyOffsModalOpen = false">

            <div class="p-5 flex items-center justify-between bg-secondary/50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs font-semibold">
                        <i class="fa-solid fa-calendar-week"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-theme-text">Manage Institute Weekly Offs</h2>
                        <p class="text-[11px] text-theme-text-muted">Configure recurring non-working days for academic year {{ $currentYear }}</p>
                    </div>
                </div>
                <button type="button" @click="isWeeklyOffsModalOpen = false" class="w-8 h-8 rounded-full bg-secondary text-theme-text hover:bg-subtle flex items-center justify-center text-xs transition cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.holidays.manage-weekly-offs') }}" class="p-5 space-y-4 text-xs">
                @csrf
                <input type="hidden" name="year" value="{{ $currentYear }}">

                <p class="text-theme-text-muted leading-relaxed">
                    Select which days of the week are recurring institute holidays. By default, <strong>Sunday</strong> is selected. You can select multiple days (e.g. Saturday &amp; Sunday, or Friday), or uncheck Sunday to make it a normal working day.
                </p>

                <!-- Quick Presets -->
                <div class="space-y-1.5">
                    <label class="text-[11px] font-medium text-theme-text">Quick Presets</label>
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <button type="button" @click="weeklyOffDays = [0]"
                                class="px-2.5 py-1 rounded-full text-[11px] transition cursor-pointer"
                                :class="weeklyOffDays.length === 1 && weeklyOffDays.includes(0) ? 'bg-brand text-primary font-medium' : 'bg-secondary text-theme-text hover:bg-subtle'">
                            Sunday Only
                        </button>
                        <button type="button" @click="weeklyOffDays = [0, 6]"
                                class="px-2.5 py-1 rounded-full text-[11px] transition cursor-pointer"
                                :class="weeklyOffDays.length === 2 && weeklyOffDays.includes(0) && weeklyOffDays.includes(6) ? 'bg-brand text-primary font-medium' : 'bg-secondary text-theme-text hover:bg-subtle'">
                            Sat &amp; Sun (Weekend)
                        </button>
                        <button type="button" @click="weeklyOffDays = [5]"
                                class="px-2.5 py-1 rounded-full text-[11px] transition cursor-pointer"
                                :class="weeklyOffDays.length === 1 && weeklyOffDays.includes(5) ? 'bg-brand text-primary font-medium' : 'bg-secondary text-theme-text hover:bg-subtle'">
                            Friday Only
                        </button>
                        <button type="button" @click="weeklyOffDays = [5, 6]"
                                class="px-2.5 py-1 rounded-full text-[11px] transition cursor-pointer"
                                :class="weeklyOffDays.length === 2 && weeklyOffDays.includes(5) && weeklyOffDays.includes(6) ? 'bg-brand text-primary font-medium' : 'bg-secondary text-theme-text hover:bg-subtle'">
                            Fri &amp; Sat
                        </button>
                        <button type="button" @click="weeklyOffDays = []"
                                class="px-2.5 py-1 rounded-full text-[11px] transition cursor-pointer"
                                :class="weeklyOffDays.length === 0 ? 'bg-rose-500 text-white font-medium' : 'bg-secondary text-theme-text hover:bg-subtle'">
                            No Weekly Offs (7 Days Working)
                        </button>
                    </div>
                </div>

                <!-- 7 Days Selection Grid -->
                <div class="space-y-1.5 pt-1">
                    <label class="text-[11px] font-medium text-theme-text">Recurring Off Days</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @php
                            $daysOfWeekList = [
                                0 => ['name' => 'Sunday', 'sub' => 'Default Off'],
                                1 => ['name' => 'Monday', 'sub' => 'Start of Week'],
                                2 => ['name' => 'Tuesday', 'sub' => 'Midweek'],
                                3 => ['name' => 'Wednesday', 'sub' => 'Midweek'],
                                4 => ['name' => 'Thursday', 'sub' => 'Midweek'],
                                5 => ['name' => 'Friday', 'sub' => 'Jumma / Weekend'],
                                6 => ['name' => 'Saturday', 'sub' => 'Weekend Half/Full'],
                            ];
                        @endphp

                        @foreach($daysOfWeekList as $dow => $info)
                            <label class="p-2.5 rounded-lg bg-secondary flex items-start gap-2.5 cursor-pointer hover:bg-subtle transition border-0">
                                <input type="checkbox"
                                       name="days_of_week[]"
                                       value="{{ $dow }}"
                                       :checked="weeklyOffDays.includes({{ $dow }})"
                                       @change="
                                           if ($el.checked) {
                                               if (!weeklyOffDays.includes({{ $dow }})) weeklyOffDays.push({{ $dow }});
                                           } else {
                                               weeklyOffDays = weeklyOffDays.filter(d => d !== {{ $dow }});
                                           }
                                       "
                                       class="mt-0.5 rounded text-brand focus:ring-brand">
                                <div class="min-w-0 leading-tight">
                                    <span class="text-xs font-semibold text-theme-text block">{{ $info['name'] }}</span>
                                    <span class="text-[10px] text-theme-text-muted">{{ $info['sub'] }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Scope Choice -->
                <div class="space-y-1.5 pt-1">
                    <label class="text-[11px] font-medium text-theme-text">Application Scope</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <label class="p-2.5 rounded-lg bg-secondary flex items-center gap-2 cursor-pointer hover:bg-subtle transition">
                            <input type="radio" name="scope" value="all" checked class="text-brand focus:ring-brand">
                            <span class="text-xs text-theme-text">Full Academic Year {{ $currentYear }}</span>
                        </label>
                        <label class="p-2.5 rounded-lg bg-secondary flex items-center gap-2 cursor-pointer hover:bg-subtle transition">
                            <input type="radio" name="scope" value="future" class="text-brand focus:ring-brand">
                            <span class="text-xs text-theme-text">Remaining Days of {{ $currentYear }} (Today Onwards)</span>
                        </label>
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-between border-t border-subtle/40">
                    <button type="button" @click="isWeeklyOffsModalOpen = false"
                            class="px-5 py-2 bg-secondary text-theme-text rounded-full text-xs font-normal hover:bg-subtle transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-brand text-primary rounded-full text-xs font-semibold hover:opacity-90 transition cursor-pointer shadow-xs">
                        Save &amp; Sync Weekly Offs
                    </button>
                </div>
            </form>

            <!-- Quick Bulk Actions Footer -->
            <div class="p-4 bg-secondary/30 border-t border-subtle/30 space-y-2">
                <span class="text-[11px] text-theme-text-muted font-medium block">Quick Bulk Overrides:</span>
                <div class="flex items-center gap-2 flex-wrap text-xs">
                    <form method="POST" action="{{ route('admin.holidays.manage-weekly-offs') }}" class="inline">
                        @csrf
                        <input type="hidden" name="year" value="{{ $currentYear }}">
                        <input type="hidden" name="bulk_action" value="make_all_working">
                        <button type="submit" onclick="return confirm('Override all weekly offs for {{ $currentYear }} to normal working days?');"
                                class="px-3 py-1 rounded-full bg-secondary hover:bg-subtle text-theme-text text-[11px] transition cursor-pointer inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-briefcase text-amber-500 text-[10px]"></i>
                            <span>Mark All Weekly Offs as Working Days</span>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.holidays.manage-weekly-offs') }}" class="inline">
                        @csrf
                        <input type="hidden" name="year" value="{{ $currentYear }}">
                        <input type="hidden" name="bulk_action" value="make_all_holiday">
                        <button type="submit"
                                class="px-3 py-1 rounded-full bg-secondary hover:bg-subtle text-theme-text text-[11px] transition cursor-pointer inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-[10px]"></i>
                            <span>Reactivate All Weekly Offs</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@php
    $holidaysJsonList = $holidays->map(function($h) {
        return [
            'id'             => $h->id,
            'title'          => $h->title,
            'start_date'     => $h->start_date->format('Y-m-d'),
            'end_date'       => $h->end_date ? $h->end_date->format('Y-m-d') : $h->start_date->format('Y-m-d'),
            'type'           => $h->type,
            'type_label'     => $h->type_label,
            'scope'          => $h->scope,
            'scope_label'    => $h->scope_label,
            'status'         => (bool) $h->status,
            'date_formatted' => $h->start_date->format('l, d M Y'),
            'description'    => $h->description ?? ''
        ];
    });
@endphp

<script>
    window.academicHolidayConfig = {
        calendarDays: @json($calendarDays),
        allHolidays: @json($holidaysJsonList),
        standardFestivals: @json($standardFestivalsMap),
        activeWeeklyOffDays: @json($activeWeeklyOffDays),
        todayStr: '{{ $todayStr }}'
    };

    function holidayManager() {
        return {
            calendarDays: window.academicHolidayConfig.calendarDays || [],
            allHolidays: window.academicHolidayConfig.allHolidays || [],
            standardFestivals: window.academicHolidayConfig.standardFestivals || {},
            weeklyOffDays: window.academicHolidayConfig.activeWeeklyOffDays || [0],
            todayStr: window.academicHolidayConfig.todayStr,
            selectedDate: window.academicHolidayConfig.todayStr,
            selectedDateHoliday: null,
            selectedDateFestival: null,
            selectedDateIsSunday: false,
            selectedDateIsWeeklyOff: false,
            activeFilter: 'all',
            searchQuery: '',

            // Modal states
            isCreateModalOpen: false,
            isEditModalOpen: false,
            isWeeklyOffsModalOpen: false,
            isConfirmModalOpen: false,

            // Confirmation modal data
            confirmHoliday: null,
            confirmModalTitle: '',
            confirmModalMessage: '',

            // Create form
            formTitle: '',
            formStartDate: '',
            formEndDate: '',
            formType: 'festival',
            formScope: 'all',
            formDesc: '',

            // Edit form
            editForm: {
                id: null,
                title: '',
                start_date: '',
                end_date: '',
                type: 'festival',
                scope: 'all',
                description: ''
            },

            init() {
                this.selectDate(this.selectedDate);
                setTimeout(() => {
                    this.scrollToCurrentOrNext();
                }, 150);
            },

            scrollToCurrentOrNext() {
                const target = document.getElementById('currentOrNextHolidayRow');
                const container = document.getElementById('holidaysScrollContainer');
                if (target && container) {
                    const targetTop = target.offsetTop - container.offsetTop;
                    const scrollPosition = targetTop - 70;
                    container.scrollTo({
                        top: Math.max(0, scrollPosition),
                        behavior: 'smooth'
                    });
                }
            },

            get formattedSelectedDate() {
                if (!this.selectedDate) return 'Select a date';
                const parts = this.selectedDate.split('-');
                if (parts.length === 3) {
                    const d = new Date(parts[0], parts[1] - 1, parts[2]);
                    return d.toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
                }
                return this.selectedDate;
            },

            get selectedDateDayNum() {
                if (!this.selectedDate) return '--';
                return this.selectedDate.split('-')[2] || '--';
            },

            get selectedDateMonthName() {
                if (!this.selectedDate) return '---';
                const parts = this.selectedDate.split('-');
                if (parts.length === 3) {
                    const d = new Date(parts[0], parts[1] - 1, parts[2]);
                    return d.toLocaleDateString('en-US', { month: 'short' });
                }
                return '---';
            },

            get selectedDateDayOfWeek() {
                if (!this.selectedDate) return '';
                const parts = this.selectedDate.split('-');
                if (parts.length === 3) {
                    const d = new Date(parts[0], parts[1] - 1, parts[2]);
                    return d.toLocaleDateString('en-US', { weekday: 'long' });
                }
                return '';
            },

            selectDate(dateStr) {
                this.selectedDate = dateStr;
                const cell = this.calendarDays.find(c => c.date === dateStr);
                
                // Find holiday in calendarDays or allHolidays
                let h = null;
                if (cell && cell.holiday) {
                    h = cell.holiday;
                } else {
                    h = this.allHolidays.find(item => dateStr >= item.start_date && dateStr <= item.end_date) || null;
                }
                this.selectedDateHoliday = h;

                // Check if there is a festival on this date
                let festName = null;
                let festDesc = null;

                if (cell && cell.festival_name) {
                    festName = cell.festival_name;
                    festDesc = cell.festival_desc;
                } else if (h && h.type === 'festival') {
                    festName = h.title;
                    festDesc = h.description;
                } else if (this.standardFestivals && this.standardFestivals[dateStr]) {
                    festName = this.standardFestivals[dateStr].title;
                    festDesc = this.standardFestivals[dateStr].desc;
                }

                this.selectedDateFestival = festName ? { title: festName, desc: festDesc } : null;

                const parts = dateStr.split('-');
                const d = new Date(parts[0], parts[1] - 1, parts[2]);
                const dow = d.getDay();
                this.selectedDateIsSunday = (dow === 0);
                this.selectedDateIsWeeklyOff = this.weeklyOffDays.includes(dow);
            },

            promptToggleWorkingDay(holiday) {
                if (!holiday) return;
                this.confirmHoliday = holiday;
                if (holiday.status) {
                    this.confirmModalTitle = 'Mark as Normal Working Day?';
                    this.confirmModalMessage = 'Are you sure you want to mark "' + holiday.title + '" as an open Working Day? This will override the holiday, and students and teachers will be expected for classes.';
                } else {
                    this.confirmModalTitle = 'Reactivate Official Holiday?';
                    this.confirmModalMessage = 'Are you sure you want to reactivate "' + holiday.title + '" as an official Holiday? This will automatically mark holiday attendance for students and staff.';
                }
                this.isConfirmModalOpen = true;
            },

            openCreateModal(prefillDate = null, prefillTitle = '', prefillDesc = '') {
                this.formTitle = prefillTitle || '';
                this.formStartDate = prefillDate || this.selectedDate || this.todayStr;
                this.formEndDate = this.formStartDate;
                this.formType = 'festival';
                this.formScope = 'all';
                this.formDesc = prefillDesc || '';
                this.isCreateModalOpen = true;
            },

            editHolidayById(id) {
                const h = this.allHolidays.find(item => item.id === id);
                if (h) {
                    this.editHoliday(h);
                }
            },

            editHoliday(h) {
                this.editForm = {
                    id: h.id,
                    title: h.title,
                    start_date: h.start_date,
                    end_date: h.end_date || h.start_date,
                    type: h.type,
                    scope: h.scope || 'all',
                    description: h.description || ''
                };
                this.isEditModalOpen = true;
            }
        };
    }
</script>
@endsection
