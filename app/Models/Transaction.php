<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_date',
        'opening_inprocess',
        'total_book',
        'total_delivered',
        'closing_inprocess',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    /**
     * Get or create today's transaction
     */
    public static function today()
    {
        return self::firstOrCreate(
            ['transaction_date' => today()],
            self::openingBalance()
        );
    }

    /**
     * Carry forward yesterday closing
     */
    protected static function openingBalance(): array
    {
        $yesterday = self::whereDate(
            'transaction_date',
            today()->subDay()
        )->first();

        $opening = $yesterday?->closing_inprocess ?? 0;

        return [
            'opening_inprocess' => $opening,
            'total_book'        => 0,
            'total_delivered'   => 0,
            'closing_inprocess' => $opening,
        ];
    }

    /**
     * Recalculate closing safely
     */
    public function recalcClosing(): void
    {
        $this->closing_inprocess =
            $this->opening_inprocess
            + $this->total_book
            - $this->total_delivered;

        $this->save();
    }
}


// $txn = Transaction::today();

// $txn->increment('total_book');
// $txn->recalcClosing();


// $txn = Transaction::today();

// $txn->increment('total_delivered');
// $txn->recalcClosing();







