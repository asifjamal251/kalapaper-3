<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wastage extends Model
{
    use HasFactory;

    protected $table = 'wastages';

    protected $fillable = [
        'width',
        'core_pipe',
        'edge_guard',
        'ldp',
        'strip',
        'core_plug',
        'side_disk',
        'paper_broke',
        'diff_due_to_gsm',
    ];

    protected $casts = [
        'width' => 'decimal:2',
        'core_pipe' => 'decimal:2',
        'edge_guard' => 'decimal:2',
        'ldp' => 'decimal:2',
        'strip' => 'decimal:2',
        'core_plug' => 'decimal:2',
        'side_disk' => 'decimal:2',
        'paper_broke' => 'decimal:2',
        'diff_due_to_gsm' => 'decimal:2',
    ];
}