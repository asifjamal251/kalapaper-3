<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderJobCard extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'purchase_order_item_id',
        'job_card_id',
        'job_card_item_id',
        'reel_weight',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function jobCardItem()
    {
        return $this->belongsTo(JobCardItem::class);
    }
}