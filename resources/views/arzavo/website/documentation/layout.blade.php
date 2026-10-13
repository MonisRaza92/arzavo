@extends('layouts.arzavo.docs')

@section('title', 'Institutional Documentation & User Guides • Arzavo')
@section('description', 'Comprehensive multi-tenant setup guides, custom domain mapping instructions, LMS configuration, and role-based permissions references for Arzavo.')

@section('breadcrumb_category')
    <span class="hover:text-theme-text transition cursor-pointer">Platform</span>
    <i class="fa-solid fa-chevron-right text-[9px] opacity-40"></i>
@endsection

@section('breadcrumb_title', ucwords(str_replace('-', ' ', $slug ?? 'Overview & Introduction')))

@section('content')
    @yield('doc_content')
@endsection
