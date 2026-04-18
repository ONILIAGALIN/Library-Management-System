<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    protected $fillable = [
        'borrow_record_id',
        'amount',
        'payment_date',
    ];

    public function borrowRecord()
    {
        return $this->belongsTo(Borrow_record::class);
    }
}
