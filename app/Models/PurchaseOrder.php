<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'from',
        'bill_to',
        'ship_to',
        'consignee',
        'po_number',
        'po_date',
        'so_number',
        'so_date',
        'status_id',
    ];

    /* ================= RELATIONS ================= */

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function purchaseJobCards()
    {
        return $this->hasMany(PurchaseJobCard::class);
    }

    public function fromParty()
    {
        return $this->belongsTo(Party::class, 'from');
    }

    public function billTo()
    {
        return $this->belongsTo(Party::class, 'bill_to');
    }

    public function shipTo()
    {
        return $this->belongsTo(Party::class, 'ship_to');
    }

    public function consigneeParty()
    {
        return $this->belongsTo(Party::class, 'consignee');
    }

    /* ================= BUSINESS LOGIC ================= */

    // Total PO weight (required)
    public function requiredWeight(): float
    {
        return (float) $this->items()->sum('ream_weight');
    }

    // Weight already used in job cards
    public function usedWeight(): float
    {
        return (float) $this->purchaseJobCards()->sum('reel_weight');
    }

    // Remaining weight
    public function remainingWeight(): float
    {
        return $this->requiredWeight() - $this->usedWeight();
    }

    // Update PO status based on weight
    public function refreshStatus(): void
    {
        if ($this->usedWeight() > $this->requiredWeight()) {
            throw new \Exception('PO weight exceeded');
        }

        $this->update([
            'status_id' => $this->usedWeight() == $this->requiredWeight() ? 4 : 3
        ]);
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($po) {
            $prefix = 'KPIPL';
            $monthYear = date('m-y');

            $serialNumber = static::generateSerialNumber($prefix);

            $po->po_number = "{$prefix}/{$monthYear}/{$serialNumber}";
        });
    }

    protected static function generateSerialNumber($prefix)
    {
        // 🔥 Get last PO of ANY month
        $lastOrder = static::where('po_number', 'LIKE', "{$prefix}/%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastOrder) {
            $parts = explode('/', $lastOrder->po_number);
            $lastNumber = (int) end($parts);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}