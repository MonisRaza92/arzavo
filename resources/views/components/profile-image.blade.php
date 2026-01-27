@props([
    'user' => $user,
])
@if ($user->profile_picture) 
<img src="{{ media($user->profile_picture) }}" class="border-rounded logo aspect-square object-cover w-full" alt="{{ $user->fname }}"> 
@else 
<h2 class="font-bold border-rounded text-xl flex justify-center items-center logo aspect-square bg-invert text-invert">{{ strtoupper(substr($user->fname, 0, 1)) }}</h2> 
@endif