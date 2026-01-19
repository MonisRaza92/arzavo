@extends('layouts.admin')
@section('title', 'Admin Courses')
@section('content')
@include('tenant.admin.courses.partials.courses-stats')
@include('tenant.admin.courses.partials.header')
@include('tenant.admin.courses.partials.course-add')
@include('tenant.admin.courses.partials.courses')
@endsection
