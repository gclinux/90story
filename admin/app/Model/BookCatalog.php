<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BookCatalog extends Model
{
    public function book(){
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function content(){
        return $this->hasOne(BookContent::class, 'catalog_id');
    }
    
    protected static function boot()
    {
        parent::boot();

        static::deleting(function($m) {
             $m->content()->delete();
        });
    }
    
}
