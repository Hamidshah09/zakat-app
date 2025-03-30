<?php

namespace App\Http\Controllers;
use App\Models\ZakatCommitties;

use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ZakatCommitties::with(['asstcommissioners'=>function($q){
            $q->select('id', 'sub_division_id','name')->with('subdivisions');
        }])
        ->withCount('beneficiaries')
        ->with(['mnas'=>function($q){
            $q->select('id', 'name');
        }])
        ->orderBy('id', 'desc');
        
        if ($request->search){
            
            if($request->search_type=='name'){
                $query->where('lzc_name', 'like', "%".$request->search."%");
            }elseif($request->search_type=='id'){
                $query->where('id', $request->search);
            }elseif($request->search_type=='mna_id'){
                $query->whereHas('mnas', function ($q) use ($request) {
                    $q->where('name', 'like', "%".$request->search."%");
                });            
            }elseif($request->search_type=='ac_id'){
                $query->whereHas('asstcommissioners', function ($q) use ($request) {
                    $q->where('name', 'like', "%".$request->search."%");
                });            
            }
            
        }

        $zakatcommittees = $query->paginate(10);
        // return $zakatcommittees;
        return view('zakatcommittees.index', compact('zakatcommittees'));    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function exportToCSV(Request $request)
    {
        // Build query with the same filters used for on-screen search
        $query = ZakatCommitties::with(['asstcommissioners'=>function($q){
            $q->select('id', 'sub_division_id','name')->with('subdivisions');
        }])
        ->withCount('beneficiaries')
        ->with(['mnas'=>function($q){
            $q->select('id', 'name');
        }])
        ->orderBy('id', 'desc');
        
        if ($request->search){
            
            if($request->search_type=='name'){
                $query->where('lzc_name', 'like', "%".$request->search."%");
            }elseif($request->search_type=='id'){
                $query->where('id', $request->search);
            }elseif($request->search_type=='mna_id'){
                $query->whereHas('mnas', function ($q) use ($request) {
                    $q->where('name', 'like', "%".$request->search."%");
                });            
            }elseif($request->search_type=='ac_id'){
                $query->whereHas('asstcommissioners', function ($q) use ($request) {
                    $q->where('name', 'like', "%".$request->search."%");
                });            
            }
            
        }
    
        $zakatcommittees = $query->get(); // Get filtered records
    
        // CSV file headers
        $filename = 'filtered_zakatcommittees.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
    
        // Generate CSV data
        $callback = function () use ($zakatcommittees) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Zakkat Committee', 'No of Beneficiaries', 'Currently Updated', 'Bank and Branch Name', 'Account Number', 'Assistant Commissioner', 'MNA']); // Add column headers
    
            foreach ($zakatcommittees as $zakatcommittee) {
                fputcsv($file, [
                    $zakatcommittee->id,
                    $zakatcommittee->lzc_name,
                    $zakatcommittee->no_of_beneficiaries,
                    $zakatcommittee->beneficiaries_count,
                    $zakatcommittee->bank_name,
                    $zakatcommittee->acc_no,
                    $zakatcommittee->bank_name,
                    $zakatcommittee->asstcommissioners->name . ", AC(". $zakatcommittee->asstcommissioners->subdivisions->name.")",
                    $zakatcommittee->mnas->name,
                ]);
                }
    
            fclose($file);
        };
    
        // Stream the CSV file to the browser
        return response()->stream($callback, 200, $headers);
    }
}
