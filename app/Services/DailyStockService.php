<?php

namespace App\Services;

use App\Models\DailyStock;

class DailyStockService
{
    public static function getToday()
    {
        $today = now()->toDateString();

        return DailyStock::firstOrCreate(
            ['stock_date' => $today],
            self::defaultOpening($today)
        );
    }

    public static function updateJobCardBooking($weight){
        $daily = self::getToday();

        $daily->jobcard_booked += $weight;

        $daily->available_stock -= $weight;

        $daily->jobcard_closing =
            $daily->jobcard_opening
            + $daily->jobcard_booked
            - $daily->jobcard_consumed;

        $daily->total_stock_closing =
            $daily->total_stock_opening
            + $daily->inward_received
            - $daily->jobcard_booked
            - $daily->challan_dispatched;

        $daily->save();
    }

    public static function reduceInwardStock($weight)
    {
        $daily = self::getToday();

        $daily->inward_move_to_stock += $weight;

        $daily->inward_closing =
            $daily->inward_opening
            + $daily->inward_received
            - $daily->inward_move_to_stock;

        $daily->total_stock_closing =
            $daily->total_stock_opening
            + $daily->inward_received
            - $daily->jobcard_booked
            - $daily->challan_dispatched;

        $daily->save();
    }

    public static function addOnwayStock($weight)
    {
        $daily = self::getToday();

        $daily->onway_received += $weight;
        $daily->onway_closing =
            $daily->onway_opening
            + $daily->onway_received;

        $daily->available_onway_stock += $weight;

        $daily->save();
    }

    public static function addInwardReceived($weight)
    {
        $daily = self::getToday();

        $daily->inward_received += $weight;

        $daily->inward_closing =
            $daily->inward_opening
            + $daily->inward_received
            - $daily->inward_move_to_stock;

        $daily->available_stock += $weight;

        $daily->save();
    }

    protected static function defaultOpening($date)
    {
        $yesterday = DailyStock::whereDate('stock_date','<',$date)
            ->latest('stock_date')
            ->first();

        if (!$yesterday) {
            return [
                'jobcard_opening' => 0,
                'jobcard_closing' => 0,
                'inward_opening' => 0,
                'inward_closing' => 0,
                'onway_opening' => 0,
                'onway_closing' => 0,
                'total_stock_opening' => 0,
                'total_stock_closing' => 0,
            ];
        }

        return [
            'jobcard_opening' => $yesterday->jobcard_closing,
            'jobcard_closing' => $yesterday->jobcard_closing,
            'inward_opening' => $yesterday->inward_closing,
            'inward_closing' => $yesterday->inward_closing,
            'onway_opening' => $yesterday->onway_closing,
            'onway_closing' => $yesterday->onway_closing,
            'total_stock_opening' => $yesterday->total_stock_closing,
            'total_stock_closing' => $yesterday->total_stock_closing,
        ];
    }
}