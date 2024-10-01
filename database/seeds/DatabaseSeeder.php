<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Model::unguard();

        //$this->call('UsersTableSeeder');
        $this->call('TypeUsersTableSeeder');
        $this->call('SchoolsTableSeeder');
        $this->call('UsersTableSeeder');
        $this->call('CatalogsTableSeeder');
        $this->call('TasksTableSeeder');
        $this->call('MenuTableSeeder');
        $this->call('MenuTaskTableSeeder');
        $this->call('SchoolUserTableSeeder');
        $this->call('AccountingPeriodsTableSeeder');
        $this->call('TypeSeatsTableSeeder');
        $this->call('DegreesTableSeeder');
        $this->call('TypesTableSeeder');
        $this->call('TaskUserTableSeeder');
        $this->call('InvoicesTypesTableSeeder');
        $this->call('PaymentMethodsTableSeeder');
       // $this->call('BrandsTableSeeder');
       // $this->call('SupplierTableSeeder');
       // $this->call('RawProductsTableSeeder');
        $this->call('ConversionTableSeeder');
        $this->call('TableSalonTableSeeder');
        $this->call('CashDesksTableSeeder');
        $this->call('CurrenciesTableSeeder');
        $this->call('SchoolsMonthsFiscalTableSeeder');

        Model::reguard();
    }

}
