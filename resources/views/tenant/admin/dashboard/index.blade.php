@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')

<div class="space-y-6">
    {{-- WELCOME BANNER & BREADCRUMB (DASHBOARD ONLY) --}}
    @include('tenant.admin.includes.breadcrumb')

    {{-- SECTION 1: FINANCIAL & SALES OVERVIEW --}}
    @include('tenant.admin.dashboard.partials.finance_section')

    {{-- SECTION 2: USERS & COMMUNITY DIRECTORY (ROLE-BASED) --}}
    @include('tenant.admin.dashboard.partials.users_section')

    {{-- SECTION 3: GROWTH TRENDS & ANALYTICS CHARTS --}}
    @include('tenant.admin.dashboard.partials.charts_section')

    {{-- SECTION 4: ACADEMICS & CONTENT CATALOG --}}
    @include('tenant.admin.dashboard.partials.academics_section')

    {{-- SECTION 5: RECENT ACTIVITY & DIRECTORY FEEDS --}}
    @include('tenant.admin.dashboard.partials.feeds_section')
</div>

@endsection
