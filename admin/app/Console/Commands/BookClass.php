<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Admin\Controllers\BookController;
class BookClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:class';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '把分类进行统计';

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
        $book = new BookController;
        $book->syncClass();
    }
}
