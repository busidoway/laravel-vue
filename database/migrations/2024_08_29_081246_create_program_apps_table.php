<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_apps', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('programs_education_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('programs_education_id');
            $table->foreign('programs_education_id')->references('id')->on('programs_education')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('application_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('program_apps');
    }
}
