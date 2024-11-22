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
        Schema::create('コード', function (Blueprint $table) {
            $table->integer('コード区分');
            $table->integer('コード');
            $table->string('コード名称', 100)->nullable();
            $table->string('コード略称', 50)->nullable();
            $table->string('メモ', 80)->nullable();
            $table->integer('ソート順');
            $table->string('画像ファイルPATH', 1000)->nullable();
            $table->timestamp('登録日時');
            $table->timestamp('更新日時');
            $table->primary(['コード区分', 'コード']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('コード');
    }
};
