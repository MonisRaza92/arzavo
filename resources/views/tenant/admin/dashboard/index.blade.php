@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')

@include('tenant.admin.dashboard.partials.statics')
@include('tenant.admin.dashboard.partials.recent_users')

@endsection
