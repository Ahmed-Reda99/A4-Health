<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->enum('title', ['professor', 'lecturer', 'consultant', 'specialist']);
            $table->text('description');
            $table->string('img_name');
            $table->string('street');
            $table->string('city');
            $table->unsignedBigInteger('specialization_id');
            $table->integer('fees');
            $table->foreign('id')->references('id')->on('users');
            $table->foreign('specialization_id')->references('id')->on('specializations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctors');
    }
}
