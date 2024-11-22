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
        Schema::create('商品ネット', function (Blueprint $table) {
            $table->string('商品番号', 100);
            $table->integer('送料区分');
            $table->integer('倉庫指定区分');
            $table->integer('セット商品区分');
            $table->string('サイズ幅', 1000)->nullable();
            $table->string('カラーバリエーション', 1000)->nullable();
            $table->string('サイズバリエーション', 1000)->nullable();
            $table->string('素材', 1000)->nullable();
            $table->string('加工', 1000)->nullable();
            $table->integer('生産国');
            $table->string('キャッチコピー', 1000)->nullable();
            $table->string('キャッチコピー2', 1000)->nullable();
            $table->string('こだわりワード', 1000)->nullable();
            $table->string('関連キーワード1商品名', 2000)->nullable();
            $table->string('関連キーワード2商品説明文', 2000)->nullable();
            $table->string('商品説明パーツ1', 4000)->nullable();
            $table->string('商品説明文用タイトル', 1000)->nullable();
            $table->string('商品説明文用商品名', 1000)->nullable();
            $table->string('対象者', 1000)->nullable();
            $table->integer('Amazonカラー基準');
            $table->string('横軸名', 1000)->nullable();
            $table->string('縦軸名', 1000)->nullable();
            $table->string('楽天ディレクトリID', 100)->nullable();
            $table->string('YAHOOプロダクトカテゴリ', 100)->nullable();
            $table->string('YAHOOブランドコード', 100)->nullable();
            $table->string('ポンパレモールジャンルID', 100)->nullable();
            $table->string('WOWMAカテゴリ', 100)->nullable();
            $table->string('Qoo10カテゴリ', 100)->nullable();
            $table->string('Qoo10ブランドコード', 100)->nullable();
            $table->integer('Qoo10原産地');
            $table->string('メルカリカテゴリ', 100)->nullable();
            $table->string('メルカリブランドコード', 100)->nullable();
            $table->string('AMAZONブラウズノート', 100)->nullable();
            $table->string('AMAZONブラウズノート2', 100)->nullable();
            $table->integer('AMAZON商品タイプ');
            $table->integer('AMAZON表地素材');
            $table->integer('AMAZONライフスタイル1');
            $table->integer('AMAZONライフスタイル2');
            $table->string('楽天ジャンルID', 100)->nullable();
            $table->string('販売説明文用キャッチコピー', 1000)->nullable();
            $table->string('関連商品表示名', 1000)->nullable();
            $table->string('関連カテゴリ表示名', 1000)->nullable();
            $table->integer('関連情報出力位置');
            $table->string('ブランド', 100)->nullable();
            $table->string('ブランドカナ', 100)->nullable();
            $table->string('ベース商品名', 1000)->nullable();
            $table->integer('次回新規出力');
            $table->integer('次回項目出力');
            $table->integer('モール表示順');
            $table->integer('有効SKU数');
            $table->integer('出力対象');
            $table->timestamp('登録日時');
            $table->timestamp('更新日時');

            $table->primary(['商品番号']);
        });

        // Alter columns to varchar
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN カラーバリエーション varchar(1000)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN サイズバリエーション varchar(1000)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN 素材 varchar(1000)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN 加工 varchar(1000)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN キャッチコピー varchar(1000)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN キャッチコピー2 varchar(1000)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN こだわりワード varchar(1000)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN 関連キーワード1商品名 varchar(2000)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN 関連キーワード2商品説明文 varchar(2000)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN 商品説明パーツ1 varchar(8000)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN 商品説明文用タイトル varchar(1000)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN 商品説明文用商品名 varchar(1000)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN 対象者 varchar(1000)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN 横軸名 varchar(1000)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN 縦軸名 varchar(1000)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN 楽天ディレクトリID varchar(100)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN YAHOOプロダクトカテゴリ varchar(100)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN YAHOOブランドコード varchar(100)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN ポンパレモールジャンルID varchar(100)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN WOWMAカテゴリ varchar(100)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN Qoo10カテゴリ varchar(100)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN Qoo10ブランドコード varchar(100)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN メルカリカテゴリ varchar(100)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN メルカリブランドコード varchar(100)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN AMAZONブラウズノート varchar(100)");
        DB::statement("ALTER TABLE 商品ネット ALTER COLUMN AMAZONブラウズノート2 varchar(100)");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('商品ネット');
    }
};
