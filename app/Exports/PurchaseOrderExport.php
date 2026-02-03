<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PurchaseOrderExport implements FromView
{
    protected $purchase_order;

    public function __construct($purchase_order)
    {
        $this->purchase_order = $purchase_order;
    }

    public function view(): View
    {
        return view('admin.purchase-order.export', [
            'purchase_order' => $this->purchase_order
        ]);
    }
}