<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('note')->nullable();
            $table->char('brand')->nullable();
            $table->char('color')->nullable();
            $table->char('year')->nullable();
            $table->char('identity')->nullable();
            $table->char('insurance_image')->nullable();
            $table->char('end_insurance_date')->nullable();
            $table->char('identity_image')->nullable();
            $table->char('drive_image')->nullable();
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
        Schema::dropIfExists('cars');
    }
}
