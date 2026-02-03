<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCardItem extends Model
{
    protected $fillable = [
        'job_card_id',
        'inward_item_id',
        'item_no',
        'with_cm',
        'with_inch',
        'length_cm',
        'length_inch',
        'trim',
        'bundle_pack',
        'sheet_per_reem',
        'run_number',
    ];

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function inwardItem()
    {
        return $this->belongsTo(InwardItem::class);
    }
}