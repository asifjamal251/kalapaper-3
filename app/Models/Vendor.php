<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'vendor_name',
        'email',
        'cc_email',
        'contact_no',
        'media_id',
        'gst',
        'state_id',
        'district_id',
        'city_id',
        'pincode',
        'address',
        'status_id'
    ];

    public function media(){
        return $this->hasOne(Media::class,'id','media_id');
    }

    public function paperQuality(){
        return $this->hasMany(PaperQuality::class);
    }

    public function state() {
        return $this->belongsTo(State::class);
    }

    public function district() {
        return $this->belongsTo(District::class);
    }

    public function city() {
        return $this->belongsTo(City::class);
    }

}