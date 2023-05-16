<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimingsTable extends Migration
{
    public function up()
    {
        Schema::create('timings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('day')->nullable();
            $table->string('shift')->nullable();
            $table->time('form')->nullable();
            $table->time('to')->nullable();
            $table->time('before_from')->nullable();
            $table->time('before_to')->nullable();
            $table->time('after_from')->nullable();
            $table->time('after_to')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
