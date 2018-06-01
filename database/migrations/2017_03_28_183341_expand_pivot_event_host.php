<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpandPivotEventHost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pivot_event_host', function (Blueprint $table) {
            $table->tinyInteger('confirmed')->unsigned()->default(0);
            $table->integer('custom_message_id')->unsigned()->nullable();
            $table->timestamp('accept_timestamp')->nullable();
            $table->string('accept_ipadr')->nullable();

            $table->foreign('event_host_id')
                ->references('id')->on('event_hosts')
                ->onDelete('set null');

            $table->foreign('custom_message_id')
                ->references('id')->on('user_messages')
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
    }
}
