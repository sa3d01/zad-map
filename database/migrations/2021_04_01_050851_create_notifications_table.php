<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receiver_id')->nullable();
            $table->foreignId('order_id')->nullable();
            $table->json('title')->nullable();
            $table->json('note')->nullable();
            $table->enum('read',['true','false'])->default('false');
            $table->enum('type',['admin','app'])->default('app');
            $table->enum('admin_notify_type',['single','user','provider','family','delivery','all'])->default('single');
            $table->json('receivers')->nullable();
            $table->json('more_details')->nullable();
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
        Schema::dropIfExists('notifications');
    }
}
