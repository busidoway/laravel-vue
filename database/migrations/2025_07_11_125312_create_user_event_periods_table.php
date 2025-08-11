<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserEventPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_event_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_event_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->integer('price')->nullable();
            $table->boolean('active')->default(false);
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
        Schema::dropIfExists('user_event_periods');
    }
}
