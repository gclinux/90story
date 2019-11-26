<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class Spider extends BatchAction
{
    protected $selector = '.spider-start';

    public function handle(Collection $collection, Request $request)
    {
        foreach ($collection as $model) {
            // 
        }

        return $this->response()->success('任务执行成功,请留意更新！')->refresh();
    }

    public function form()
    {
        //$this->checkbox('type', '类型')->options([]);
        //$this->textarea('reason', '原因')->rules('required');
    }

    public function html()
    {
        return "<a class='spider-start btn btn-sm btn-danger'><i class='fa fa-info-circle'></i>重新抓取</a>";
    }
}