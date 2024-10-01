<?php

namespace AccountHon\Console\Commands;

use AccountHon\Entities\AccountingPeriod;
use AccountHon\Entities\BalancePeriod;
use AccountHon\Entities\Catalog;
use AccountHon\Http\Controllers\TestController;
use AccountHon\Repositories\AccountingPeriodRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class seanalert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:update-saldo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';
    /**
     * @var AccountingPeriodRepository
     */
    private $accountingPeriodRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(AccountingPeriodRepository $accountingPeriodRepository)
    {
        parent::__construct();
        $this->accountingPeriodRepository = $accountingPeriodRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $actual =   AccountingPeriod::where('school_id',1)->orderBy('id','DESC')->limit(2)->get();
        $other =$actual[0]->id;
        $id=$actual[1]->id;

        $catalogs = Catalog::where('school_id', 1)->get();
        $periodo = AccountingPeriod::find($other);

        $periods = $this->accountingPeriodRepository->listsRange(array($periodo->year . $periodo->month, $periodo->year . $periodo->month), 'period', 'id');


        foreach ($catalogs AS $catalog) {
            $inicial = BalancePeriod::where('catalog_id', $catalog->id)->where('accounting_period_id', $id)->sum('amount');
            $debito = $this->saldoPeriod($catalog->id, $periods, 'debito');
            $credito = $this->saldoPeriod($catalog->id, $periods, 'credito');
            $balance = ($inicial + $debito) - $credito;

            $bal = BalancePeriod::where('catalog_id', $catalog->id)->where('accounting_period_id', $periodo->id);
            if ($bal->count() > 0) {
                BalancePeriod::where('catalog_id', $catalog->id)->where('accounting_period_id', $periodo->id)->update(['amount' => $balance]);
            } else {
                BalancePeriod::create([
                    'amount' => $balance, 'catalog_id' => $catalog->id, 'accounting_period_id' => $periodo->id, 'year' => $periodo->year,
                    'school_id' => 1
                ]);
            }
        }
    }
}
