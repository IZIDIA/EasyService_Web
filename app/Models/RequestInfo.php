<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestInfo extends Model
{
    protected $guarded = ['id'];
    use HasFactory;

    public function pc_info()
    {
        return $this->belongsTo(PcInfo::class);
    }
}
