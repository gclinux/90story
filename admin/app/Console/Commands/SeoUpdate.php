<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Admin\Controllers\LinkController;
class SeoUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '对最新5个未伪原创的描述进行伪原创';

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
        $submit->seoUpdate();
    }
}
