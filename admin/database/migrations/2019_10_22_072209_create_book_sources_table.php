<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_sources', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_name',35)->comment('执行文件名');
            $table->string('remark',50)->comment('备注')->default('');
            $table->tinyInteger('status')->comment('状态')->defualt(1);
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
        Schema::dropIfExists('book_sources');
    }
}
