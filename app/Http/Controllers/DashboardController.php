<?php

namespace App\Http\Controllers;
use App\Models\AssistantCommissioners;
use App\Models\Beneficiaries;
use App\Models\User;
use Illuminate\Http\Request;
use DateTime;

class DashboardController extends Controller
{
    public function index(){
        $results = [];
        
        $date = new DateTime('2025-03-28'); // Replace with your desired date
        $today = new DateTime();
        $lenth = $date->diff($today);
        for($i=1; $i<$lenth->d+1; $i++){
            $date->modify('+1 day'); // Add one day
            if ($today>=$date){
                $startOfDay = $date->format('Y-m-d 00:00:00'); // Beginning of the day
                $endOfDay = $date->format('Y-m-d 23:59:59'); // End of the day
                $count = Beneficiaries::whereBetween('created_at', [$startOfDay, $endOfDay])->count(); // Count records for the day
                if ($count!=0){
                    $results[$date->format('Y-m-d')] = $count;
                }
                
            }
        }
        
        $acs = AssistantCommissioners::with('subdivisions')->withCount('beneficiaries')->get();
        // $users = User::whereHas(['beneficiaries'=>function($q)use($startOfDay, $endOfDay){
        //     $q->select('id', 'created_at')->whereBetween('created_at', [$startOfDay, $endOfDay])->count();
        // }])->orderBy('beneficiaries_count', 'desc')->get();
        #->withCount('beneficiaries')
        $users = User::withCount(['beneficiaries' => function($q) use ($startOfDay, $endOfDay) {
            $q->whereBetween('created_at', [$startOfDay, $endOfDay]);
        }])
        ->orderBy('beneficiaries_count', 'desc')
        ->get();
        
        
        // return ['difference'=>$lenth];
        return view('dashboard', compact('acs', 'users', 'results'));
    }
}
