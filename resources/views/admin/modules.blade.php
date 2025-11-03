@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')
@include('admin.partials.categories')
@include('admin.partials.classes')
@include('admin.partials.subjects')
@include('admin.partials.faqs')
@endsection