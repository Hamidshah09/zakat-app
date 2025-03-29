<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZakatCommitties extends Model
{
    public function beneficiaries(){
        return $this->hasMany(beneficiaries::class, 'zc_id', 'id');
    }
}
