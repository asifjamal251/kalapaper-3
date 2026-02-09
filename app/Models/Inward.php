<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inward extends Model
{
    use HasFactory;

    protected $fillable = [
        'inward_number',
        'challan_from',
        'challan_date',
        'challan_no',
        'e_way_bill_no',
        'vehicle_no',
        'transport',
        'status_id',
        'created_by',
    ];

    protected $casts = [
        'challan_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(InwardItem::class, 'inward_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $prefix = 'INW';
            $monthYear = static::generateMonthYear();
            $serialNumber = static::generateSerialNumber($monthYear, $prefix);

            $order->inward_number = "{$prefix}/{$monthYear}/{$serialNumber}";
        });
    }

    protected static function generateMonthYear()
    {
        return date('m-y'); // Example: 08-25
    }

    protected static function generateSerialNumber($monthYear, $prefix)
    {
        $lastOrder = static::where('inward_number', 'LIKE', "{$prefix}/{$monthYear}/%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastOrder) {
            $parts = explode('/', $lastOrder->inward_number);
            $lastNumber = (int) end($parts);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return str_pad($newNumber, 4, '0', STR_PAD_LEFT); 
    }

}