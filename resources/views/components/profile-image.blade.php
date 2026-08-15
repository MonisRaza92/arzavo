@props([
    'user' => null,
])
@php
    $user = $user ?? auth('web')->user() ?? auth('tenant')->user();
    $fname = $user->fname ?? 'U';
@endphp
@if ($user && !empty($user->profile_picture)) 
<img src="{{ media($user->profile_picture) }}" class="border-rounded logo aspect-square object-cover w-full" alt="{{ $fname }}"> 
@else 
<h2 class="font-bold border-rounded text-xl flex justify-center items-center logo aspect-square bg-invert text-invert">{{ strtoupper(substr($fname, 0, 1)) }}</h2> 
@endif