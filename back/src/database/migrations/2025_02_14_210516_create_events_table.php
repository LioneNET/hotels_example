<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('Тип события');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('booking_id')->nullable()->constrained('bookings');
            $table->json('details')->nullable()->comment('Дополнительные данные о событии');
            $table->boolean('system_generated')->comment('Системное событие');
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
        Schema::dropIfExists('events');
    }
}
