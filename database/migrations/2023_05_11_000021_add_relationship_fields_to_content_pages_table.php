<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToContentPagesTable extends Migration
{
    public function up()
    {
        Schema::table('content_pages', function (Blueprint $table) {
            $table->unsignedBigInteger('clinic_ids_id')->nullable();
            $table->foreign('clinic_ids_id', 'clinic_ids_fk_8465960')->references('id')->on('clinics');
        });
    }
}
