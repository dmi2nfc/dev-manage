<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSprintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sprints', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->integer('project_id')->index();
            $table->integer('worked_time')->nullable();
            $table->integer('rate')->nullable();
            // $table->integer('rate_type')->nullable();
            $table->enum('rate_type', ['hourly', 'fixed'])->nullable();
            $table->integer('currency_id')->nullable();
            $table->integer('payment_status')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('started_at')->nullable()->index();
            $table->dateTime('closed_at')->nullable()->index();
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
        Schema::dropIfExists('sprints');
    }
}
