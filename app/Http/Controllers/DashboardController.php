<?php

namespace App\Http\Controllers;
use App\Models\AssistantCommissioners;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $acs = AssistantCommissioners::with('subdivisions')->withCount('beneficiaries')->get();
        $users = User::withCount('beneficiaries')->orderBy('beneficiaries_count', 'desc')->get();
        
    return view('dashboard', compact('acs', 'users'));
    }
}
