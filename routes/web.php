<?php

use App\Http\Controllers\CommitteeController;
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
    Route::get('/beneficiaries/export/csv', [BeneficiaryController::class, 'exportToCSV'])->name('beneficiaries.export.csv');
    Route::get('/test', function(){
        
        $date_array = [];
        $date_test = array();
        $date = new DateTime('2025-03-28'); // Replace with your desired date
        $today = new DateTime();
        for($i=1; $i<10; $i++){
            $date->modify('+1 day'); // Add one day
            array_push($date_test, $date->format('Y-m-d'));
            if ($today>=$date){
                $startOfDay = $date->format('Y-m-d 00:00:00'); // Beginning of the day
                $endOfDay = $date->format('Y-m-d 23:59:59'); // End of the day
                $count = Beneficiaries::whereBetween('created_at', [$startOfDay, $endOfDay])->count(); // Count records for the day
                if ($count!=0){
                    $date_array[$date->format('Y-m-d')] = $count;
                }
                
            }
        }
        
        return $date_array;

    });
    route::get('/import', function(){
        
            $filename =  public_path('uploads/upload1.csv'); // Replace with your file path

            // Read the file into an array
            $file_lines = file($filename);

            // Loop over each line
            $count = 0;
            foreach ($file_lines as $line) {
                $values = explode(',', $line);
                $data = Beneficiaries::where('cnic', $values[0])->get();
                if($data->isEmpty()){
                    $count++;
                    Beneficiaries::create([
                        'cnic' => $values[0],
                        'name' => $values[1],
                        'father_name' => $values[2],
                        'zc_id' => $values[3],
                        'ac_id' => $values[4],
                        'user_id'=>$values[5]
                    ]);
                }
            }
            return $count;
    });
    // Route::resource('/zakatcommittee', [ZakatCommitteeController::class])->only(['index', 'create', 'store', 'edit', 'update']);
    Route::resource('/zakatcommittee', CommitteeController::class)->only([
        'index', 'create', 'store', 'edit', 'update'
    ]);
    Route::get('/zakatcommittee/export/csv', [CommitteeController::class, 'exportToCSV'])->name('zakatcommittee.export.csv');

});

require __DIR__.'/auth.php';
