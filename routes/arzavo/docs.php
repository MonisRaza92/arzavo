<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Arzavo\DocumentationController;

$docsDomain = 'docs.' . config('app.domain');

Route::domain($docsDomain)->group(function () {
    Route::get('/', [DocumentationController::class, 'index'])->name('docs.index');
    Route::get('/search', [DocumentationController::class, 'search'])->name('docs.search');
    Route::get('/{slug}', [DocumentationController::class, 'show'])->name('docs.show');
});
