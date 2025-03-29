<?php

use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\AssistantCommissioners;
use App\Models\Beneficiaries;
use App\Models\ZakatCommitties;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function(){
        // $acs = AssistantCommissioners::with(['zakatcommitties'=>function($query){
        //     $query->withCount('beneficiaries');
        // }])-> with('subdivisions')->get();
        $acs = AssistantCommissioners::with('subdivisions')->withCount('beneficiaries')->get();
    return view('dashboard', compact('acs'));
})->middleware(['auth', 'verified'])->name('dashboard');

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
