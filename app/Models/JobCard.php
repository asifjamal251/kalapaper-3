<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCard extends Model
{
    protected $fillable = [
        'sold_to',
        'ship_to',
        'job_card_number',
        'so_number',
        'so_date',
        'reference_number',
        'reference_date',
        'purchase_by',
        'total_weight',
        'ready_weight',
        'delivered_weight',
        'excess',
        'type',
        'job_card_type',
        'status_id',
    ];

    public function items()
    {
        return $this->hasMany(JobCardItem::class);
    }

    public function purchaseJobCards()
    {
        return $this->hasMany(PurchaseJobCard::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $prefix = 'KPIPL';
            $monthYear = static::generateMonthYear(); // mm-yy
            $serialNumber = static::generateSerialNumber($monthYear, $prefix);

            $order->job_card_number = "{$prefix}/{$monthYear}/{$serialNumber}";
        });
    }

    protected static function generateMonthYear()
    {
        return date('m-y'); // Example: 08-25
    }

    protected static function generateSerialNumber($monthYear, $prefix)
    {
        $lastOrder = static::where('job_card_number', 'LIKE', "{$prefix}/{$monthYear}/%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastOrder) {
            $parts = explode('/', $lastOrder->job_card_number);
            $lastNumber = (int) end($parts);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return str_pad($newNumber, 4, '0', STR_PAD_LEFT); // 0001, 0002, ...
    }
}