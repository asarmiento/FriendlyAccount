<?php

namespace AccountHon\Repositories\Restaurant;

use AccountHon\Entities\Restaurant\OrderSalon;
use AccountHon\Repositories\BaseRepository;
use DB;

class OrderSalonRepository extends BaseRepository {
    public function getModel() {
        return new OrderSalon();
    }

    public function orders($table)
    {
    	return $this->getModel()
    				->select(DB::raw('SUM(qty) as total'),'menu_restaurant_id','user_id','created_at','token', 'date')
                    ->where('table_salon_id', $table->id)
                    ->where('status','no aplicado')
                    ->where('canceled', false)
                    ->groupBy('menu_restaurant_id')
                    ->with('menuRestaurant')
                    ->with('user')
                    ->orderBy('id', 'desc')
                    ->get();
    }

    public function orders_not_applied($table, $split = null)
    {
        if($split){
            return $this->getModel()
                        ->select(DB::raw('qty as total'),'menu_restaurant_id','user_id','created_at','token', 'date')
                        ->where('table_salon_id', $table->id)
                        ->where('status','no aplicado')
                        ->where('canceled', false)
                        ->get();
        }
        return $this->getModel()
                        ->where('table_salon_id', $table->id)
                        ->where('status','no aplicado')
                        ->where('canceled', false)
                        ->get();
    }

    public function orders_not_applied_group($menu,$table, $split = null)
    {
        if($split){
            return $this->getModel()
                ->select(DB::raw('qty as total'),'menu_restaurant_id','user_id','created_at','token', 'date')
                ->where('table_salon_id', $table->id)
                ->where('status','no aplicado')
                ->where('menu_restaurant_id',$menu)
                ->where('canceled', false)
                ->sum('qty');
        }
        return $this->getModel()
            ->where('table_salon_id', $table->id)
            ->where('status','no aplicado')
            ->where('menu_restaurant_id',$menu)
            ->where('canceled', false)
            ->sum('qty');
    }

    public function orders_applied_group($invoice,$menu,$table, $split = null)
    {
        if($split){
            return $this->getModel()
                ->select(DB::raw('qty as total'),'menu_restaurant_id','user_id','created_at','token', 'date')
                ->where('table_salon_id', $table->id)
                ->where('status','aplicado')
                ->where('menu_restaurant_id',$menu)
                ->where('invoice_id',$invoice->id)
                ->where('canceled', false)
                ->sum('qty');
        }
        return $this->getModel()
            ->where('table_salon_id', $table->id)
            ->where('status','aplicado')
            ->where('menu_restaurant_id',$menu)
            ->where('invoice_id',$invoice->id)
            ->where('canceled', false)
            ->sum('qty');
    }
    public function orders_not_applied_sum_qty($table)
    {
        return $this->getModel()
                    ->where('table_salon_id', $table->id)
                    ->where('status','no aplicado')
                    ->where('canceled', false)
                    ->sum('qty');
    }

}
