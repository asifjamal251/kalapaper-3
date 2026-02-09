<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCard extends Model
{
    protected $fillable = [
        'created_by',
        'from',
        'purchase_order_id',
        'ship_to',
        'sold_to',

        'job_card_number',
        'so_number',
        'so_date',

        'total_weight',
        'ready_weight',
        'delivered_weight',
        'excess',

        'type',
        'job_card_type',

        'status_id',
    ];

    
    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function fromParty()
    {
        return $this->belongsTo(Party::class, 'from');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function shipTo()
    {
        return $this->belongsTo(Party::class, 'ship_to');
    }

    public function soldTo()
    {
        return $this->belongsTo(Party::class, 'sold_to');
    }

    public function items()
    {
        return $this->hasMany(JobCardItem::class);
    }


    public function wastage()
    {
        return $this->hasOne(JobCardWastage::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $prefix = 'KPIPL';
            $monthYear = static::generateMonthYear();
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

        return str_pad($newNumber, 4, '0', STR_PAD_LEFT); 
    }


    public function calculateAndSaveWastage(){
        $items = $this->items()->with('wastage')->get();

        $data = [
            'core_pipe' => 0,
            'ldp' => 0,
            'strip' => 0,
            'edge_guard' => 0,
            'core_plug' => 0,
            'side_disk' => 0,
            'paper_broke' => 0,
            'trim' => 0,
            'second_sheets' => 0,
            'diff_due_to_gsm' => 0,
        ];

        foreach ($items as $item) {

            if (!$item->wastage) {
                continue;
            }

            $data['core_pipe'] += (float) $item->wastage->core_pipe;
            $data['ldp'] += (float) $item->wastage->ldp;
            $data['strip'] += (float) $item->wastage->strip;
            $data['edge_guard'] += (float) $item->wastage->edge_guard;
            $data['core_plug'] += (float) $item->wastage->core_plug;
            $data['side_disk'] += (float) $item->wastage->side_disk;
            $data['paper_broke'] += (float) $item->wastage->paper_broke;
            $data['diff_due_to_gsm'] += (float) $item->wastage->diff_due_to_gsm;
        }

        $data['trim'] = $this->items()->sum('trim');

        $this->wastage()->updateOrCreate(
            ['job_card_id' => $this->id],
            $data
        );
    }

    //$jobCard->calculateAndSaveWastage();
}