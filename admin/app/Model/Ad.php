<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    function getImgAttribute(){
        return 'upload/'.$this->attributes['img'];
    }
}
