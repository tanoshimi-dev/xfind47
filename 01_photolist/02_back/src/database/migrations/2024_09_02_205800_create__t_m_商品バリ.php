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
        Schema::create('商品バリ', function (Blueprint $table) {
            $table->string('商品番号', 100);
            $table->string('サイズ', 100);
            $table->string('カラー', 100);
            $table->string('SKU', 100)->nullable();
            $table->integer('SKU属性');
            $table->integer('SKU属性詳細');
            $table->string('SKU仕入先', 100)->nullable();
            $table->string('SKU発注番号', 100)->nullable();
            $table->string('SKU物流コード', 100)->nullable();
            $table->string('SKUWMS商品名', 300)->nullable();
            $table->integer('SKU持量');
            $table->integer('SKU発注単位');
            $table->integer('SKUメーカー発注単位用数値');
            $table->integer('SKUSG定番在庫数');
            $table->integer('SKU最終リリース');
            $table->integer('SKU原価');
            $table->integer('SKU定価税抜');
            $table->integer('SKU価格税込');
            $table->integer('SKU価格税抜');
            $table->integer('SKU備考1');
            $table->integer('SKU備考2');
            $table->integer('SKU備考3');
            $table->integer('SKU備考4');
            $table->integer('SKU備考5');
            $table->string('JANコード現', 100)->nullable();
            $table->string('JANコード旧', 100)->nullable();
            $table->string('JANコード旧2', 100)->nullable();
            $table->string('ASIN', 100)->nullable();
            $table->string('SX2商品コード', 100)->nullable();
            $table->string('SX2商品名', 100)->nullable();
            $table->string('バイヤーメモ', 100)->nullable();
            $table->integer('縦');
            $table->integer('横');
            $table->integer('高さ');
            $table->integer('メール便単品上限個数');            
            $table->date('SKU最終CSV出力日');
            $table->integer('商品メンテフラグ');
            $table->timestamp('登録日時');
            $table->timestamp('更新日時');

            $table->primary(['商品番号', 'サイズ', 'カラー']);
        });

        // DB::statement("ALTER TABLE 商品バリ ALTER COLUMN 商品番号 varchar(100)");
        // DB::statement("ALTER TABLE 商品バリ ALTER COLUMN サイズ varchar(100)");
        // DB::statement("ALTER TABLE 商品バリ ALTER COLUMN カラー varchar(100)");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('商品バリ');
    }
};
