<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHostCommitmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('host_commitments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('action', 30);
            $table->integer('host_id')->unsigned();
            $table->integer('event_id')->unsigned();
            $table->string('ipadr', 30)->nullable();
            $table->tinyInteger('with_conditions')->unsigned()->default(0);
            $table->mediumText('conditions_message')->nullable();
            $table->timestamp('committed_at');
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
        Schema::dropIfExists('host_commitments');
    }
}
