<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;


class Proxy extends Model
{
    protected $rediskey = 'joffe_proxy_list';
    function redisKeyGet(){
        return $this->rediskey;
    }
    public function __call($method, $parameters)
    {
        
        if(strtolower($method) == 'findorfail'){
            return $this->$method(...$parameters);
        }else{
            return parent::__call($method, $parameters);
        }
    }

    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }
   

    public static function getInstance(){
        return new static;
    }
 
 	public function Lrange($start,$length){
 		return Redis::LRANGE($this->redisKeyGet(),$start,$start+$length-1);
 	}

    public function paginate(){
        $obj = self::getInstance();
        $perPage = Request::get('per_page', 20);
        $page = Request::get('page', 1);
        $start = ($page-1)*$perPage+1;
        $data = Redis::LRANGE($obj->redisKeyGet(),$start-1,$start+$perPage);
        if(!$data){
            $data = [];
        }else{
        	foreach($data as $i=>$value){
                $data[$i] = json_decode($value,1);
                $data[$i]['id'] = $start+$i;
        	}
        }
        $items = static::hydrate($data);
        $total=Redis::LLEN($obj->redisKeyGet());
        $paginator = new LengthAwarePaginator($items, $total, $perPage,$page,['path' => Paginator::resolveCurrentPath(),'pageName' => 'page']);
        return $paginator;

    }

    protected function findOrFail($id)
    {
        $obj = self::getInstance();
        $data = Redis::LINDEX($obj->redisKeyGet(), intval($id)-1);
        $data = json_decode($data,1);
        $data['id'] = $id;
        return static::newFromBuilder($data);
    }

    public static function findOrFailStatic($id){
        $obj = self::getInstance();
        return $obj->findOrFail($id);
    } 
    
    // 保存提交的form数据
    public function save(array $options = [])
    {
        $attributes = $this->getAttributes();
        $attributes['from']=isset($attributes['from'])?$attributes['from'] : '手动录入';
        $string = json_encode($attributes);
        if(isset($attributes['id'])){
            Redis::LSET($this->rediskey,$attributes['id']-1, $string);
            return $attributes['id']-1;
        }else{
            $id = Redis::RPUSH($this->rediskey,$string);
            return $id;
        }
    }
    public function redisInsert($data){
        if(is_array($data)){
            $string = \json_encode($data);
        }else{
            $string = $data;
        }
        $id = Redis::RPUSH($this->rediskey,$string);
        return $id;
    }


    public function delete(){
        $attributes = $this->getAttributes();

        $this->deleteOne($attributes['id']);
    }

    public function deleteOne($id){
        Redis::lset( $this->rediskey, $id-1,'del');
        Redis::lrem( $this->rediskey, 0, 'del');
        return true;
    }
    public function clean(){
    	$len = Redis::LLEN($this->rediskey);
    	//dump($len);die();
    	for($i=0;$i<$len;$i++){
    		$data = Redis::LINDEX($this->rediskey, $i);
    		$data = json_decode($data,1);
    		if(!isset($data['from']) or $data['from'] != '手动录入'){
    			//dump($i);
                Redis::lset( $this->rediskey, $i,'del');
            }
            
    	}
    	Redis::lrem( $this->rediskey, 0, 'del');
    }

    public static function with($relations)
    {
        return new static;
    }

    // 覆盖`orderBy`来收集排序的字段和方向
    public function orderBy($column, $direction = 'asc')
    {

    }

    // 覆盖`where`来收集筛选的字段和条件
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        //lset mylist index "del"
        //lrem mylist 0 "del"
    }


}
