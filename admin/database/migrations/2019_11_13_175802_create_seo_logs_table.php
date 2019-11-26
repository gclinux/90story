<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeoLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('spider',16)->comment('爬虫类型');
            $table->string('url')->commemt('爬虫访问的地址');
            $table->integer('ip')->commemt('爬虫IP');
            $table->string('ua')->comment('爬虫完整UA');
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
        Schema::dropIfExists('seo_logs');
    }
}
