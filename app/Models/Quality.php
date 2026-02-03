<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quality extends Model
{
    use HasFactory;
     protected $table = 'qualities';

    protected $fillable = [
        'name',
        'code',
    ];

    public function getNameWithCodeAttribute(){
        return trim($this->name . ' (' . $this->code . ')');
    }
}