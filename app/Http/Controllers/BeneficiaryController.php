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
        if ($request->search){
            $beneficiaries = Beneficiaries::where('cnic', $request->search)->with(['users'=>function($q){
                $q->select('id', 'name');
            }])
            ->with(['zakatcommittees'=>function($q){
                $q->select('id', 'lzc_name');
            }])
            ->with(['asstcommissioners'=>function($q){
                $q->select('id', 'sub_division_id','name')->with('subdivisions');
            }])
            ->orderBy('id', 'desc')
            ->paginate(10);
        }else{
            $beneficiaries = Beneficiaries::with(['users'=>function($q){
                $q->select('id', 'name');
            }])
            ->with(['zakatcommittees'=>function($q){
                $q->select('id', 'lzc_name');
            }])
            ->with(['asstcommissioners'=>function($q){
                $q->select('id', 'sub_division_id','name')->with('subdivisions');
            }])
            ->orderBy('id', 'desc')
            ->paginate(10);
        }
        
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
        Beneficiaries::findorfail($id)->update([
            'cnic' => $request->cnic,
            'name' => $request->name,
            'father_name' => $request->father_name,
            'zc_id' => $request->zc_id,
            'ac_id' => $request->ac_id,
            'user_id'=>$user_id,
        ]);

        return redirect()->route('beneficiary.index')->with('status', 'Beneficiary Successfuly Updated');
        

    }
}
