<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->enum('type',['PROVIDER','FAMILY'])->default('PROVIDER');
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('city_id')->nullable();
            $table->foreignId('district_id')->nullable();
            $table->json('location')->nullable();
            $table->boolean('online')->nullable()->default(true);
            $table->boolean('has_delivery')->nullable()->default(false);
            $table->double('delivery_price')->default(0);
            $table->string('marketer_id')->nullable();

            $table->json('devices')->nullable();

            $table->json('data_for_update')->nullable();
            $table->boolean('request_update')->nullable()->default(false);

            $table->string('last_ip')->nullable();
            $table->timestamp('last_login_at')->nullable();

            $table->boolean('approved')->nullable()->default(false);
            $table->string('reject_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->boolean('banned')->nullable()->default(false);

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
        Schema::dropIfExists('providers');
    }
}
