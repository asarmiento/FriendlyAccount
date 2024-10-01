<?php

namespace AccountHon\Commands;

use AccountHon\Commands\Command;
use AccountHon\Entities\AuxiliaryReceipt;
use Illuminate\Contracts\Bus\SelfHandling;

class send_receipt_email extends Command implements SelfHandling
{
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $receipts = AuxiliaryReceipt::where('status_email', false)->get();

        foreach ($receipts AS $receipt){

        }
    }
}
