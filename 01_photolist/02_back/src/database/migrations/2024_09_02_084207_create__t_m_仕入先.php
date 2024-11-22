<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('仕入先', function (Blueprint $table) {
            $table->string('仕入先')->unique();
            $table->string('属性')->nullable();
            $table->string('メーカー名')->nullable();
            $table->string('メーカー名モール商品名用')->nullable();
            $table->string('メーカー略称')->nullable();
            $table->string('発注先')->nullable();
            $table->integer('ソート順');
            $table->integer('モール表示順');
            $table->integer('引当不可発注書出力');
            $table->timestamp('登録日時');
            $table->timestamp('更新日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('仕入先');
    }
};
