<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Party extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'username',
        'password',
        'password_plain',
        'company_name',
        'email',
        'contact_no',
        'media_id',
        'gst',
        'pincode',
        'city',
        'district',
        'state',
        'address',
        'status_id',
        'stock_on_email',
        'login_status',
    ];

    protected $casts = [
        'email' => 'array',
        'contact_no' => 'array',
    ];

    public function media(){
        return $this->hasOne(Media::class,'id','media_id');
    }
}