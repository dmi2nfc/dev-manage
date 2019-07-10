<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('currencies');

        Schema::create('currencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('symbol')->nullable();
            $table->timestamps();
        });

        DB::table('currencies')->insert([
            ['name' => 'USD', 'symbol' => '$', 'created_at' => Carbon::now(),],
            ['name' => 'EUR', 'symbol' => '€', 'created_at' => Carbon::now(),],
            ['name' => 'UAH', 'symbol' => '₴', 'created_at' => Carbon::now(),],
         ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
