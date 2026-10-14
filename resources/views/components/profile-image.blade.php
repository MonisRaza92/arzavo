@props([
    'user' => null,
])
@php
    $resolvedUser = $user ?? auth('web')->user() ?? auth('tenant')->user();     
    $fname = $resolvedUser->fname ?? $resolvedUser->name ?? 'U';
@endphp
@if ($resolvedUser && !empty($resolvedUser->profile_picture ?? $resolvedUser->avatar)) 
<img src="{{ media($resolvedUser->profile_picture ?? $resolvedUser->avatar) }}" {{ $attributes->merge(['class' => 'rounded-full aspect-square object-cover']) }} alt="{{ $fname }}"> 
@else 
<span {{ $attributes->merge(['class' => 'rounded-full aspect-square flex items-center justify-center font-semibold bg-brand text-primary']) }}>{{ strtoupper(substr($fname, 0, 1)) }}</span> 
@endif