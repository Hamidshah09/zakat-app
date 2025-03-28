<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficiaries extends Model
{
    protected $guarded = [];
    public function zakatcommittees(){
        return $this->belongsTo(ZakatCommitties::class, 'zc_id', 'id');
    }
    public function asstcommissioners(){
        return $this->belongsTo(AssistantCommissioners::class, 'ac_id', 'id');
    }
    public function users(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
