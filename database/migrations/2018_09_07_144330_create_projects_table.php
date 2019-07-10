<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->integer('customer_id')->index();
            $table->integer('rate')->nullable();
            $table->enum('rate_type', ['hourly', 'fixed'])->nullable();
            $table->integer('currency_id');
            $table->text('description')->nullable();
            $table->dateTime('started_at')->nullable()->index();
            $table->boolean('archive')->default(false);
            $table->dateTime('archived_at')->nullable()->index();
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
        Schema::dropIfExists('projects');
    }
}
