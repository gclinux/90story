<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BookContent extends Model
{
    public function cat(){
        return $this->belongsTo(BookCatalog::class, 'catalog_id');
    }
}
