<?php

namespace App\Http\Controllers;

use App\Models\AssistantCommissioners;
use App\Models\Beneficiaries;
use App\Models\ZakatCommitties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BeneficiaryController extends Controller
{
    public function index(Request $request){
        $query = Beneficiaries::with(['users'=>function($q){
            $q->select('id', 'name');
        }])
        ->with(['zakatcommittees'=>function($q){
            $q->select('id', 'lzc_name');
        }])
        ->with(['asstcommissioners'=>function($q){
            $q->select('id', 'sub_division_id','name')->with('subdivisions');
        }])
        ->orderBy('id', 'desc');
        
        if ($request->search){
            
            if($request->search_type=='cnic'){
                $query->where('cnic', 'like', "%".$request->search."%");
            }elseif($request->search_type=='name'){
                $query->where('name', 'like', "%".$request->search."%");
            }elseif($request->search_type=='id'){
                $query->where('id', $request->search);
            }elseif($request->search_type=='zc_id'){
                $query->whereHas('zakatcommittees', function ($q) use ($request) {
                    $q->where('lzc_name', $request->search);
                });            
            }elseif($request->search_type=='ac_id'){
                $query->whereHas('asstcommissioners', function ($q) use ($request) {
                    $q->where('name', 'like', "%".$request->search."%");
                });            
            }
            
        }

        $beneficiaries = $query->paginate(10);
        // return $beneficiaries;
        return view('beneficiry.index', compact('beneficiaries'));
    }
    public function create(){
        $zakatcommittees = ZakatCommitties::orderBy('lzc_name', 'asc')->get();
        $asstcommissioners = AssistantCommissioners::with('subdivisions')->get();
        // return $asstcommissioners;
        return view('beneficiry.create', compact('zakatcommittees', 'asstcommissioners'));
    }

    public function store(Request $request){
        $user_id= Auth::user()->id;
        $request->validate([
            'cnic' => 'required|string|size:13|unique:beneficiaries,cnic',
            'name' => 'required|string|max:30',
            'father_name' => 'nullable|string|max:30',
            'zc_id' => 'required|integer',
            'ac_id' => 'required|integer',
        ]);
        Beneficiaries::create([
            'cnic' => $request->cnic,
            'name' => $request->name,
            'father_name' => $request->father_name,
            'zc_id' => $request->zc_id,
            'ac_id' => $request->ac_id,
            'user_id'=>$user_id,
        ]);

        return redirect()->route('beneficiary.index')->with('status', 'Beneficiary Successfuly Added');
        

    }

    public function edit($id){
        $beneficiary = Beneficiaries::findorfail($id);
        $zakatcommittees = ZakatCommitties::orderBy('lzc_name', 'asc')->get();
        $asstcommissioners = AssistantCommissioners::with('subdivisions')->get();
        // return $asstcommissioners;
        return view('beneficiry.edit', compact('beneficiary','zakatcommittees', 'asstcommissioners'));
    }

    public function update(Request $request, $id){
        $user_id= Auth::user()->id;
        // |unique:beneficiaries,cnic'
        $request->validate([
            'cnic' => 'required|string|size:13',
            'name' => 'required|string|max:30',
            'father_name' => 'nullable|string|max:30',
            'zc_id' => 'required|integer',
            'ac_id' => 'required|integer',
        ]);
        $beneficiary = Beneficiaries::findorfail($id);
        
        if($beneficiary->cnic != $request->cnic){
            $request->validate(['cnic'=>'unique:beneficiaries,cnic']);
        }
        $beneficiary->cnic = $request->cnic;
        $beneficiary->name = $request->name;
        $beneficiary->father_name = $request->father_name;
        $beneficiary->zc_id = $request->zc_id;
        $beneficiary->ac_id = $request->ac_id;
        $beneficiary->user_id=$user_id;
        $beneficiary->save();

        return redirect()->route('beneficiary.index')->with('status', 'Beneficiary Successfuly Updated');
        

    }
    public function exportToCSV(Request $request)
    {
        // Build query with the same filters used for on-screen search
        $query = Beneficiaries::with(['users'=>function($q){
            $q->select('id', 'name');
        }])
        ->with(['zakatcommittees'=>function($q){
            $q->select('id', 'lzc_name');
        }])
        ->with(['asstcommissioners'=>function($q){
            $q->select('id', 'sub_division_id','name')->with('subdivisions');
        }])
        ->orderBy('id', 'asc');
        
        if ($request->search){
            
            if($request->search_type=='cnic'){
                $query->where('cnic', 'like', "%".$request->search."%");
            }elseif($request->search_type=='name'){
                $query->where('name', 'like', "%".$request->search."%");
            }elseif($request->search_type=='id'){
                $query->where('id', $request->search);
            }elseif($request->search_type=='zc_id'){
                $query->whereHas('zakatcommittees', function ($q) use ($request) {
                    $q->where('lzc_name', $request->search);
                });            
            }elseif($request->search_type=='ac_id'){
                $query->whereHas('asstcommissioners', function ($q) use ($request) {
                    $q->where('name', 'like', "%".$request->search."%");
                });            
            }
            
        }
    
        $beneficiaries = $query->get(); // Get filtered records
    
        // CSV file headers
        $filename = 'filtered_beneficiaries.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
    
        // Generate CSV data
        $callback = function () use ($beneficiaries) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'CNIC', 'Beneficiary Name', 'Father Name', 'Zakkat Committee', 'Assistant Commissioner', 'User']); // Add column headers
    
            foreach ($beneficiaries as $beneficiary) {
                fputcsv($file, [
                    $beneficiary->id,
                    $beneficiary->cnic,
                    $beneficiary->name,
                    $beneficiary->father_name,
                    $beneficiary->zakatcommittees->lzc_name,
                    $beneficiary->asstcommissioners->name . ", AC(". $beneficiary->asstcommissioners->subdivisions->name.")",
                    $beneficiary->users->name,
                ]);
                }
    
            fclose($file);
        };
    
        // Stream the CSV file to the browser
        return response()->stream($callback, 200, $headers);
    }
}
