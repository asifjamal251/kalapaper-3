<?php
namespace App\Services;

use App\Models\InwardItem;

class HandlingUnitService
{
    public static function generateSplit(string $parentHU): string
    {
        $lastSplit = InwardItem::where('handling_unit', 'LIKE', $parentHU . '-%')
            ->orderBy('handling_unit', 'desc')
            ->first();

        if (!$lastSplit) {
            return $parentHU . '-01';
        }

        $lastNumber = (int) substr($lastSplit->handling_unit, -2);
        return $parentHU . '-' . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
    }
}