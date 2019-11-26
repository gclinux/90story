<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BookGood extends Model
{
    public function book(){
        return $this->belongsTo(Book::class, 'book_id');
    }
}
