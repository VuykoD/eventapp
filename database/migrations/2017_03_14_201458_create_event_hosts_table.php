<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventHostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_hosts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('phone')->nullable();
            $table->string('alt_phone')->nullable();
            $table->string('title')->nullable();
            $table->integer('event_coordinator_id')->nullable()->unsigned();
            $table->timestamps();
        });

        Schema::table('event_hosts', function (Blueprint $table) {
            $table->foreign('event_coordinator_id')
                ->references('id')->on('event_coordinators')
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
        Schema::table('event_hosts', function (Blueprint $table) {
            $table->dropForeign(['event_coordinator_id']);
        });
        Schema::dropIfExists('event_hosts');
    }
}
