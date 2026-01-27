@extends('layouts.editor')
@section('title', 'Editing: ' . $course->title)

@section('content')
@include('tenant.admin.courses.partials.navbar')
@include('tenant.admin.courses.partials.sidebar')
<div class="ml-75 p-4">
    @include('tenant.admin.courses.partials.overview')
    @include('tenant.admin.courses.partials.editor')
</div>
@endsection