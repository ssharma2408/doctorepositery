<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClosedTimingsTable extends Migration
{
    public function up()
    {
        Schema::create('closed_timings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('full_partial');
            $table->date('closed_on')->nullable();
            $table->time('from')->nullable();
            $table->time('to')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
