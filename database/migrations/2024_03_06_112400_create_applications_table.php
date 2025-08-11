<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('name_sender')->nullable();
            $table->string('second_name_sender')->nullable();
            $table->string('last_name_sender')->nullable();
            $table->string('email_sender')->nullable();
            $table->string('phone_sender')->nullable();
            $table->text('text')->nullable();
            $table->dateTime('date_send')->nullable();
            $table->tinyInteger('status')->nullable();
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
        Schema::dropIfExists('applications');
    }
}
