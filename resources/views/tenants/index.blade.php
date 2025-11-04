@extends('layouts.website')

@section('title', $page->meta_title)
@section('content')
@if($sections->isEmpty())
<p class="text-center py-10 text-gray-500">No content available for this page.</p>
@else
@foreach($sections as $section)
@php
$viewPath = 'tenants.sections.' . $section->type;
@endphp
@if(View::exists($viewPath))
@include($viewPath, ['section' => $section])
@endif
@endforeach
@endif
@endsection