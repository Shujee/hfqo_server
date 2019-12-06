<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploadrows', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('upload_id')->unsigned();

            $table->smallInteger('q');
            $table->smallInteger('a1');
            $table->smallInteger('a2')->nullable()->default(null);
            $table->smallInteger('a3')->nullable()->default(null);    
            
            $table->foreign('upload_id')->references('id')->on('uploads');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uploadrows');
    }
}
