<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MekanController;

Route::get('/', [MekanController::class, 'index'])->name('home');
Route::post('/ai-suggest', [MekanController::class, 'aiSuggest'])->name('ai.suggest');
