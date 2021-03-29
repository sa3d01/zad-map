<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->enum('status',['new','pre_paid','in_progress','rejected','completed'])->default('new');
            $table->enum('deliver_by',['user','provider','delivery'])->default('user');
            $table->unsignedBigInteger('delivery_id')->nullable();
            $table->dateTime('deliver_at')->nullable();
            $table->char('promo_code')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
