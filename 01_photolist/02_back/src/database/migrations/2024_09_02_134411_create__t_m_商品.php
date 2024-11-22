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
        Schema::create('商品', function (Blueprint $table) {
            $table->string('商品番号', 100);
            $table->string('商品名', 100)->nullable();
            $table->integer('属性');
            $table->integer('属性詳細');
            $table->integer('属性２');
            $table->integer('販売単位');
            $table->string('仕入先', 100)->nullable();
            $table->integer('性別');
            $table->integer('セットフラグ');
            $table->string('バイヤー担当', 100)->nullable();
            $table->integer('制作担当');
            $table->integer('定価税込');
            $table->integer('BTOC販売価格');
            $table->integer('BTOC１次値下げ');
            $table->integer('BTOC２次値下げ');
            $table->integer('BTOC最終値下げ');
            $table->integer('BTOCバイヤー決済');
            $table->integer('BTOC最安値２１OFF');
            $table->integer('BTOC送料無料価格');
            $table->integer('BTOC販売価格税抜');
            $table->integer('BTOC１次値下げ税抜');
            $table->integer('BTOC２次値下げ税抜');
            $table->integer('BTOC最終値下げ税抜');
            $table->integer('BTOCバイヤー決済税抜');
            $table->integer('BTOC最安値２１OFF税抜');
            $table->integer('BTOC送料無料価格税抜');
            $table->integer('定番リセット値');
            $table->integer('素材');
            $table->string('AMAZON登録', 100)->nullable();
            $table->integer('セール対象');
            $table->integer('BTOC適用ステータス');
            $table->integer('BTOCマミーズ適用ステータス');
            $table->integer('BTOCAmazon適用ステータス');
            $table->integer('卸単価一律定価税抜');
            $table->string('ターゲット層', 100)->nullable();
            $table->integer('表示順');
            $table->string('ブランドモール表示順', 100)->nullable();
            $table->integer('代表送料区分');
            $table->integer('商品連番');
            $table->integer('新規登録方法区分');
            $table->timestamp('登録日時');
            $table->timestamp('更新日時');
            $table->timestamp('初回CSV出力日時')->nullable();
            $table->timestamp('最終CSV出力日時')->nullable();
            $table->integer('削除フラグ');

            $table->primary(['商品番号']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('商品');
    }
};
