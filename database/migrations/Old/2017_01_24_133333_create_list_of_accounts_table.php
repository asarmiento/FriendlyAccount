<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListOfAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_of_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->enum('style',['activo','pasivo','capital','ingresos','compras','gastos']);
            $table->enum('type',['grupo','detalle']);
            $table->string('token');
            $table->integer('list_of_account_id')->nullable()->unsigned()->index();
            $table->foreign('list_of_account_id')->references('id')->on('list_of_accounts')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('typesOfCompany_id')->unsigned()->index();
            $table->foreign('typesOfCompany_id')->references('id')->on('types_of_companies')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('list_of_accounts');//
    }
}
