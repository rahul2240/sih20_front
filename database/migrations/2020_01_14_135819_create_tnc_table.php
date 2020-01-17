<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTncTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tnc', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('pad_id')->nullable()->default(NULL);
            $table->string('pad_read_id')->nullable()->default(NULL);
            $table->bigInteger('user_id');
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
        Schema::dropIfExists('tnc');
    }
}
