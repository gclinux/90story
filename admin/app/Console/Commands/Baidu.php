<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Admin\Controllers\LinkController;
class Baidu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'baidu:submit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '提交24小时内的更新给百度';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $submit = new LinkController;
        $submit->submitBaiduToday();
    }
}
