@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')
@include('admin.dashboard.partials.statics')
@include('admin.dashboard.partials.recent_users')

@endsection