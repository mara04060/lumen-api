<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('warehouse', 250)->unique();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('unid');
            $table->integer('quantity')->default(0);
            $table->string('scu', 190)->default('');
            $table->unsignedBigInteger('barcode')->default(0);
            $table->unsignedBigInteger('warehouse_id');
            $table->string('size', 10)->default('');
            $table->timestamps();
            $table->unique(['unid', 'warehouse_id']);
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
        });


        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',190);
            $table->string('email',190)->unique();
            $table->string('password', 190);
            $table->string('key',190);
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
        Schema::dropIfExists('warhouses');
        Schema::dropIfExists('products');
        Schema::dropIfExists('users');

    }
}
