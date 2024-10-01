<?php

namespace AccountHon\Entities;


class BalancePeriod extends Entity
{
    protected $table='balance_periods';

    protected $fillable = [ 'amount', 'catalog_id', 'accounting_period_id', 'year','school_id'];



    public function getRules()
    {
        return [
            'amount'=> 'required',
            'catalog_id'=> 'required',
            'accounting_period_id'=> 'required',
            'amount'=> 'required',
            'year'=> 'required'
        ];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }

	public function saldoPeriod($catalog,$period,$type){

		$saldo = $this->saldoPeriodCatalog($catalog,$period,$type);
		$saldoPart =$this->saldoPeriodCatalogPart($catalog,$period,$type);
		$total = $saldo+$saldoPart;
		return $total;
	}
	private function saldoPeriodCatalog($catalog,$periods,$type){
		$typeForm = new TypeForm();
		$type = $typeForm->nameType($type);
		$saldo = 0;
		foreach($periods AS $key => $period):
			$seat = new Seating();
			$saldo += $seat->catalogPeriod($catalog,$period,$type);
		endforeach;
		return $saldo;
	}

	private function saldoPeriodCatalogPart($catalog,$periods,$type){
    	$typeForm = new TypeForm();
		$type = $typeForm->nameType($type);
		$saldo = 0;
		foreach($periods AS $key => $period):
			$seatPart = new SeatingPart();
			$saldo += $seatPart->catalogPeriod($catalog,$period,$type);
		endforeach;
		return $saldo;
	}


}
