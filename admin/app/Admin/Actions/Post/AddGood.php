<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Model\Book as Model,
    App\Model\BookGood;
class AddGood extends RowAction
{
   // protected $selector = '.add-good';
    public $name = '添加到推荐';

    public function handle(Model $model,Request $request)
    {
        // 这里调用模型的`replicate`方法复制数据，再调用`save`方法保存
        //$model->replicate()->save();
    
        $good = BookGood::where('book_id' , $model->id)->first();
        if(!$good){
        	$good = new BookGood;
        }
        $good->book_id = $model->id;
        $good->tag = $request->input('tag');
        $good->reason =   $request->input('reason');
		$good->hot = $request->input('hot');
        $good->save();
        return $this->response()->success('推荐成功');
        // 返回一个内容为`复制成功`的成功信息，并且刷新页面
        //return $this->response()->success('复制成功.')->refresh();
    }

    public function form()
    {
        $this->textarea('reason', '原因')->rules('required');
        $this->text('tag', '标签')->rules('required')->help('多个标签使用英文逗号隔开');
        $this->integer('hot', '评分')->rules('required|min:0|max:100')->help('0到100整数')->default(65);
    
    }

}