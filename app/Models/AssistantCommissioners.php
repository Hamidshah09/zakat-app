<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssistantCommissioners extends Model
{
    public function subdivisions(){
        return $this->belongsTo(subdivisions::class, 'sub_division_id', 'id');
    }
}
