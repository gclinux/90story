<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->increments('id');
            $table->char('type',8)->comment('广告类型');
            $table->string('target_url',255)->comment('目标地址,点击地址');
            $table->string('img',255)->comment('展示图片地址')->default('');
            $table->string('code',1024)->comment('广告代码,如果需要')->default('');
            $table->tinyinteger('status')->comment('状态,0为不开放,1为开放')->default(1);
            $table->integer('click')->comment('点击数')->default(0);
            $table->integer('impress')->comment('展示数')->default(0);
            $table->timestamps();
            $table->index(['status','type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads');
    }
}
