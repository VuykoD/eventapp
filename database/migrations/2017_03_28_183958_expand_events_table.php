<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpandEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->tinyInteger('status')->unsigned()->default(0);
            $table->tinyInteger('completed')->unsigned()->default(0);
            $table->integer('num_attendees')->nullable();
            $table->string('attendance')->nullable();
            $table->integer('notes_id')->unsigned()->nullable();
            $table->integer('complete_message_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
