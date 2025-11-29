@extends('layouts.admin')
@section('title', 'Admin Courses')
@section('content')
@include('admin.partials.courses_stats')
@include('admin.partials.upload_course')
@include('admin.partials.uploaded_courses')
@endsection