<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'sold_to',
        'quality_id',
        'created_by',
        'item_number',
        'gsm',
        'type',
        'grain',
        'length_cm',
        'length_inch',
        'width_cm',
        'width_inch',
        'ream_weight',
        'quantity',
        'quantity_kg',
        'discount',
        'remarks',
        'job_card_weight',
        'status_id',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function purchaseJobCards()
    {
        return $this->hasMany(PurchaseJobCard::class);
    }

    public function quality()
    {
        return $this->belongsTo(Quality::class, 'quality_id');
    }

    public function setLengthAttribute($value)
    {
        $this->attributes['length'] = $value;

        $this->attributes['length_inch'] = $value !== null
            ? round($value / 2.54, 2)
            : null;
    }

    public function setWidthAttribute($value)
    {
        $this->attributes['width'] = $value;

        $this->attributes['width_inch'] = $value !== null
            ? round($value / 2.54, 2)
            : null;
    }
}