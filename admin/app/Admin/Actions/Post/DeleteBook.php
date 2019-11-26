<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Model\Book as Model,
    App\Model\BookCatalog;
class DeleteBook extends RowAction
{
   // protected $selector = '.add-good';
    public $name = '删除';

    public function handle(Model $model,Request $request)
    {
        
        $count = BookCatalog::where('book_id' , $model->id)->count();
        if($count>0){
            return $this->response()->error('该书下面还有'.$count.'个章节没有删除,您必须先清空章节才能删除');
        }
        $model->delete();
        return $this->response()->success('删除')->refresh();
        // 返回一个内容为`复制成功`的成功信息，并且刷新页面
        //return $this->response()->success('复制成功.')->refresh();
    }

    public function dialog()
    {
        $this->confirm('确定要删除?删除不可恢复');
    }
}