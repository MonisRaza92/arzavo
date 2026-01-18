@extends('layouts.admin')
@section('title', 'Admin Themes')

@section('content')
@include('tenant.admin.themes.partials.search-and-filters')
@include('tenant.admin.themes.partials.current-theme')
@include('tenant.admin.themes.partials.themes-list')
@endsection
