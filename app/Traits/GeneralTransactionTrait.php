<?php


namespace AccountHon\Traits;


use AccountHon\Entities\AccountingPeriod;
use AccountHon\Entities\BalancePeriod;
use AccountHon\Entities\Catalog;
use AccountHon\Entities\Seating;
use AccountHon\Entities\SeatingPart;
use AccountHon\Entities\TypeForm;
use AccountHon\Repositories\BalancePeriodRepository;

trait GeneralTransactionTrait
{
	public function revaluationBalanceCatalogo($oldPeriod,$newPeriod)
	{
		$catalogs=Catalog::where('school_id',userSchool()->id)->get();
		\Log::debug('Fecha enviada: '.json_encode($newPeriod));
		$periodo=AccountingPeriod::find($newPeriod->id);

		$periods=$this->accountingPeriodRepository->listsRange(array($periodo->year.$periodo->month,
			$periodo->year.$periodo->month),'period','id');
		\Log::debug('periodos: '.json_encode($periods));

		foreach ($catalogs AS $catalog) {
			$inicial=BalancePeriod::where('catalog_id',$catalog->id)->where('accounting_period_id',$oldPeriod)->sum('amount');
			
			$debito=$this->saldoPeriod($catalog->id,$periods,'debito');
			$credito=$this->saldoPeriod($catalog->id,$periods,'credito');
			$balance=($inicial + $debito) - $credito;

			$bal=BalancePeriod::where('catalog_id',$catalog->id)->where('accounting_period_id',$periodo->id);
			if ($bal->count() > 0) {
				BalancePeriod::where('catalog_id',$catalog->id)->where('accounting_period_id',$periodo->id)->update(['amount'=>$balance]);
			} else {
				BalancePeriod::create([
					'amount'   =>$balance,'catalog_id'=>$catalog->id,'accounting_period_id'=>$periodo->id,'year'=>$periodo->year,
					'school_id'=>userSchool()->id
				]);
			}
		}

	}

	private function saldoPeriod($catalog,$periods,$type)
	{
		$saldo = $this->saldoPeriodCatalog($catalog,$periods,$type);
		$saldoPart =$this->saldoPeriodCatalogPart($catalog,$periods,$type);
		$total = $saldo+$saldoPart;
		return $total;
	}

	private function saldoPeriodCatalog($catalog,$periods,$type){
		
		$type = TypeForm::where('name',$type)->first();
		$saldo = 0;
		foreach($periods AS $key => $period):
			$seat = Seating::where('type_id',$type->id)
				->where('accounting_period_id',$period)
				->where('status','Aplicado')
				->where('catalog_id',$catalog)->sum('amount');
			$saldo += $seat;
		endforeach;
		return $saldo;
	}

	private function saldoPeriodCatalogPart($catalog,$periods,$type){
	
		$type = TypeForm::where('name',$type)->first();
		$saldo = 0;
		foreach($periods AS $key => $period):
			$seatPart =  SeatingPart::where('type_id',$type->id)
				->where('accounting_period_id',$period)
				->where('status','Aplicado')
				->where('catalog_id',$catalog)->sum('amount');
			$saldo += $seatPart;
		endforeach;
		return $saldo;
	}
}
