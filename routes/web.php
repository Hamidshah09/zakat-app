<?php

use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Models\AssistantCommissioners;
use App\Models\Beneficiaries;
use App\Models\ZakatCommitties;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/beneficiaries', [BeneficiaryController::class, 'index'])->name('beneficiary.index');
    Route::get('/beneficiary/create', [BeneficiaryController::class, 'create'])->name('beneficiary.create');
    Route::post('/beneficiary/store', [BeneficiaryController::class, 'store'])->name('beneficiary.store');
    Route::get('/beneficiary/edit/{id}', [BeneficiaryController::class, 'edit'])->name('beneficiary.edit');
    Route::post('/beneficiary/update/{id}', [BeneficiaryController::class, 'update'])->name('beneficiary.update');
});

require __DIR__.'/auth.php';
