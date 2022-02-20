<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcInfo extends Model
{
    protected $guarded = ['id'];
    use HasFactory;

    public function request_info()
    {
        return $this->belongsTo(RequestInfo::class);
    }
}
