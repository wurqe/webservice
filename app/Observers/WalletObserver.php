<?php

namespace App\Observers;

use App\Wallet;

class WalletObserver
{
    /**
     * Handle the wallet "created" event.
     *
     * @param  \App\Wallet  $wallet
     * @return void
     */
    public function created(Wallet $wallet)
    {
        //
    }

    /**
     * Handle the wallet "updated" event.
     *
     * @param  \App\Wallet  $wallet
     * @return void
     */
    public function updated(Wallet $wallet)
    {
        //
    }

    /**
     * Handle the wallet "deleted" event.
     *
     * @param  \App\Wallet  $wallet
     * @return void
     */
    public function deleted(Wallet $wallet)
    {
        //
    }

    /**
     * Handle the wallet "restored" event.
     *
     * @param  \App\Wallet  $wallet
     * @return void
     */
    public function restored(Wallet $wallet)
    {
        //
    }

    /**
     * Handle the wallet "force deleted" event.
     *
     * @param  \App\Wallet  $wallet
     * @return void
     */
    public function forceDeleted(Wallet $wallet)
    {
        //
    }
}
