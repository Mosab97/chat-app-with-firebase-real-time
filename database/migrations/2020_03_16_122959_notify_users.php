<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotifyUsers extends Migration
{


    public function up()
    {

        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
    }



    public function down()
    {

                Schema::dropIfExists('notifications');

    }
}
