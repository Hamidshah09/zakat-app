<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZakatCommitties extends Model
{
    public function beneficiaries(){
        return $this->hasMany(beneficiaries::class, 'zc_id', 'id');
    }
    public function mnas(){
        return $this->belongsTo(Mna::class, 'mna_id', 'id');
    }
    public function asstcommissioners(){
        return $this->belongsTo(AssistantCommissioners::class, 'ac_id', 'id');
    }
}
