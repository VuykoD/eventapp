<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name');
            $table->date('event_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('venue_id')->nullable()->unsigned();
            $table->integer('event_coordinator_id')->nullable()->unsigned();
            $table->integer('event_type_id')->nullable()->unsigned();
            $table->timestamps();
        });

        Schema::table('events', function (Blueprint $table) {
            $table->foreign('event_type_id')
                ->references('id')->on('event_types')
                ->onDelete('set null');
            $table->foreign('event_coordinator_id')
                ->references('id')->on('event_coordinators')
                ->onDelete('set null');
            $table->foreign('venue_id')
                ->references('id')->on('venues')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['event_type_id']);
            $table->dropForeign(['venue_id']);
            $table->dropForeign(['event_coordinator_id']);
        });
        Schema::dropIfExists('events');
    }
}
