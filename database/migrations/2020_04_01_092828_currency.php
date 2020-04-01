<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Currency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasTable('currency')) {
            Schema::create('currency', function (Blueprint $table) {
                $table->increments('id');
                $table->string('valuteID', 30);
                $table->integer('numCode');
                $table->string('сharCode', 30);
                $table->double('value', 15, 8);
                $table->date('date');
                $table->timestamps();
              });
          }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('currency');
    }
}
