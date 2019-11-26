<?php

namespace App\Api\v1\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class TestController extends Controller
{
   public function index(Request $req){
       return ['status'=>200,'p'=>$req->p];
   }

   public function show(Request $req){
       return b64encode('uid=3&token=LOQe48mVF5fDTO0RreBuzlCjsMczLe4cz4VWsEYTuByDAtTQEH6ckn49TNZ1&id=1');
   }
}