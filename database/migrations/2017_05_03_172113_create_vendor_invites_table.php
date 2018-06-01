<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorInvitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_invites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uid', 10);
            $table->string('action', 30);
            $table->integer('vendor_id')->unsigned();
            $table->integer('event_id')->unsigned();
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('responsed_at')->nullable();            
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
        Schema::dropIfExists('vendor_invites');
    }
}
