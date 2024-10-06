<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_code',
        'user_id',
        'status',
        'total_amount'
    ];

    public function items()
    {
        return $this->hasMany(ProcurementItem::class);
    }
}

