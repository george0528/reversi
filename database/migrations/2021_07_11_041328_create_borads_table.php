<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoradsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borads', function (Blueprint $table) {
            // オセロ最初の盤面
            $reversi[3][3] = 1;
            $reversi[3][4] = 2;
            $reversi[4][3] = 2;
            $reversi[4][4] = 1;
            $reversi = json_encode($reversi);
            // テーブル　カラム
            $table->bigIncrements('id');
            $table->string('content')->default($reversi);
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
        Schema::dropIfExists('borads');
    }
}
