<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Front\Controllers\ToolsController;
class Luoxia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'luoxia:del';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '删除落霞的标签';

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
        $submit = new ToolsController;
        $submit->deleteLuoxiaLink();
    }
}
