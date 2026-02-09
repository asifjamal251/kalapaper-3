<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobCardWastage extends Model
{
    use HasFactory;


    protected $fillable = [
        'job_card_id',
        'core_pipe',
        'ldp',
        'strip',
        'edge_guard',
        'core_plug',
        'side_disk',
        'paper_broke',
        'trim',
        'second_sheets',
        'diff_due_to_gsm',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }
}