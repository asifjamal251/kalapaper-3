<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCardItem extends Model
{
    protected $fillable = [

        'job_card_id',
        'inward_item_id',
        'wastage_id',
        'quality_id',

        'item_number',

        // size
        'width_cm',
        'width_inch',
        'length_cm',
        'length_inch',

        'gsm',
        'weight',
        'handling_unit',

        'trim',
        'bundle_pack',
        'sheet_per_ream',
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

    public function wastage()
    {
        return $this->belongsTo(Wastage::class);
    }

    public function quality()
    {
        return $this->belongsTo(Quality::class);
    }

    protected static function booted(){
        static::saved(function ($item) {
            $item->jobCard?->calculateAndSaveWastage();
        });

        static::deleted(function ($item) {
            $item->jobCard?->calculateAndSaveWastage();
        });
    }
}