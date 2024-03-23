<?php

namespace App\Observers;

use App\Models\Transactions;

class TransactionsObserver
{
    public function creating(Transactions $transactions): void{
        if($transactions->spent){
            if($transactions->value > 0){
                $transactions->value = $transactions->value * -1;
            }
        }else{
            if($transactions->value < 0){
                $transactions->value = $transactions->value * -1;
            }
        }

        data_forget($transactions, 'spent');
    }
    /**
     * Handle the Transactions "created" event.
     */
    public function created(Transactions $transactions): void
    {
        //
    }

    /**
     * Handle the Transactions "updated" event.
     */
    public function updating(Transactions $transactions): void
    {
        if($transactions->spent){
            if($transactions->value > 0){
                $transactions->value = $transactions->value * -1;
            }
        }else{
            if($transactions->value < 0){
                $transactions->value = $transactions->value * -1;
            }
        }

        data_forget($transactions, 'spent');
    }

    /**
     * Handle the Transactions "deleted" event.
     */
    public function deleted(Transactions $transactions): void
    {
        //
    }

    /**
     * Handle the Transactions "restored" event.
     */
    public function restored(Transactions $transactions): void
    {
        //
    }

    /**
     * Handle the Transactions "force deleted" event.
     */
    public function forceDeleted(Transactions $transactions): void
    {
        //
    }
}
