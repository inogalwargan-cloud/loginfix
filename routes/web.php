<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\DisposisiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('surat-masuk', SuratMasukController::class);
    Route::resource('surat-keluar', SuratKeluarController::class);
    Route::get('/surat-masuk/{suratMasuk}/lampiran',[SuratMasukController::class, 'lampiran'])->name('surat-masuk.lampiran');
    Route::get('/surat-keluar/{suratKeluar}/lampiran',[SuratKeluarController::class, 'lampiran'])->name('surat-keluar.lampiran');
    
Route::get('/disposisi', [DisposisiController::class, 'index'])
    ->name('disposisi.index');

Route::get(
    '/surat-masuk/{suratMasuk}/disposisi/create',
    [DisposisiController::class, 'create']
)->name('disposisi.create');

Route::post(
    '/surat-masuk/{suratMasuk}/disposisi',
    [DisposisiController::class, 'store']
)->name('disposisi.store');

Route::patch(
    '/disposisi/{disposisi}',
    [DisposisiController::class, 'update']
)->name('disposisi.update');

});

require __DIR__.'/auth.php';
