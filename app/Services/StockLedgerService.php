<?php

namespace App\Services;

use App\Models\StockLedger;
use Illuminate\Support\Facades\DB;

class StockLedgerService
{
    public static function add(array $data){
        return DB::transaction(function () use ($data) {

            $lastStock = StockLedger::lockForUpdate()
                ->latest('id')
                ->value('current_stock') ?? 0;

            $change = abs((float) ($data['new_stock'] ?? 0));

            if ($data['type'] === 'out') {
                $currentStock = $lastStock - $change;
            } else {
                $currentStock = $lastStock + $change;
            }

            return StockLedger::create([
                'source_type'   => $data['source_type'],
                'source_id'     => $data['source_id'] ?? null,
                'type'          => $data['type'],
                'old_stock'     => $lastStock,
                'new_stock'     => $change, // always positive
                'current_stock' => $currentStock,
            ]);
        });
    }

    public static function currentStock()
    {
        return StockLedger::latest('id')->value('current_stock') ?? 0;
    }

    public static function stockAtDate($date)
    {
        return StockLedger::whereDate('created_at','<=',$date)
            ->latest('id')
            ->value('current_stock') ?? 0;
    }
}