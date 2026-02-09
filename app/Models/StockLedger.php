<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLedger extends Model
{
    protected $fillable = [
        'source_type',
        'source_id',
        'type',
        'old_stock',
        'new_stock',
        'current_stock',
    ];

    protected $casts = [
        'old_stock'     => 'float',
        'new_stock'     => 'float',
        'current_stock' => 'float',
    ];

    public function source()
    {
        return $this->morphTo(__FUNCTION__, 'source_type', 'source_id');
    }

    public function scopeIn($query)
    {
        return $query->where('type','in');
    }

    public function scopeOut($query)
    {
        return $query->where('type','out');
    }

    public function scopeLatestBalance($query)
    {
        return $query->latest('id');
    }
}