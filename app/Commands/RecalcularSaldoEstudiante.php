<?php

namespace AccountHon\Commands;

use AccountHon\Commands\Command;
use AccountHon\Http\Controllers\TestController;
use Illuminate\Contracts\Bus\SelfHandling;

class RecalcularSaldoEstudiante extends Command implements SelfHandling
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

    }
}
