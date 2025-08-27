<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Pages\BibCheck;
use App\Filament\Pages\BibDisplay;
use App\Filament\Pages\BigScreenDisplay;
use App\Filament\Resources\ParticipantResource\Pages\Scanner;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/bib-check', BibCheck::class);
Route::get('/bib-display', BibDisplay::class);
// Route::get('/bib-scanner', Scanner::class);

