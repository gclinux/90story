<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    public function getConnection()
    {
        return config('admin.database.connection') ?: config('database.default');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',125)->comment('书名');
            $table->string('author',16)->comment('作者')->default('佚名');
            $table->string('des',1024)->comment('描述')->default('');
            $table->string('url',191)->comment('资料更新地址')->default('');
            $table->string('cat_url',191)->comment('章节更新地址,如果没则表示跟资料更新地址一致')->default('');
            $table->string('file',36)->comment('处理的脚本文件名')->default('');
            $table->string('img',256)->comment('图片')->default('nopic.jpg');
            $table->tinyInteger('img_down')->comment('图片是否已经下载')->default('0')->index();
            $table->tinyInteger('spider_status')->default(0)
                ->comment('类型0：只爬了书名和来源  默认 ;1：已经填充了作者,描述等信息;');
            $table->tinyInteger('status')->comment('小说状态 默认0, 0为连载,1为完本')->default(0)->index();
            $table->string('class',16)->comment('小说分类,例如搞笑 穿越')->index();
            $table->integer('hot')->comment('热门程度')->default(0);
            $table->tinyInteger('seo_update')->comment('描述是否进行了伪原创')->default(0);
            //$table->char('book_sign',32)->default('')->comment('用于防止书本重复,md5(书名+|+作者),可能会有小许碰撞,如遇到可以自己在后台手工添加')->unique();
            $table->integer('retry')->comment('重试次数,防止死循环')->default(0);//防止死循环
            $table->timestamps();
            $table->unique('url');
            $table->unique(['name','author']);
            $table->index(['spider_status','hot']);
        });

        Schema::create('book_catalogs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('book_id')->comment('对应books表的ID');
            $table->string('name',191)->comment('章节名');
            $table->integer('inx')->comment('自然章节序号 抓取顺序');
            $table->integer('num')->comment('插入章节序号');
            $table->tinyInteger('type')->default(1)->comment('类型 1： 普通，章节名前有序号 ;2： 特殊，章节名前没序号;默认1;');
            $table->tinyInteger('open_type')->defalut(1)->comment('是否开放阅读,0为不开放,1为免费开放,2为收费开放');
            $table->tinyInteger('spider_status')->default(0);
            $table->string('url',256)->comment('内容的地址,地址里若没有http的话，那则是要跟book表的OriginUrl字段搭配');
            $table->integer('retry')->comment('重试次数,防止死循环')->default(0);
            $table->timestamps();
            $table->unique(['book_id','inx','num']);
            $table->index('created_at');
        });

        Schema::create('book_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('catalog_id')->comment('book_catalog的外键')->unique();
            $table->text('content')->comment('文章内容');
            $table->timestamps();
        });

        Schema::create('book_goods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('book_id')->unique();
            $table->string('reason','256')->comment('推荐的理由');
            $table->tinyInteger('hot')->comment('推荐度,0-100,越高越前');
            $table->string('tag','128')->comment('推荐标签,多个逗号隔开');
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
        Schema::dropIfExists('books');
        Schema::dropIfExists('book_catalogs');
        Schema::dropIfExists('book_contents');
        Schema::dropIfExists('book_goods');
    }
}
