<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    const UPDATED_AT = null;
    public function good(){
        return $this->hasOne(BookGood::class, 'book_id');
    }
    public function cats(){
        return $this->hasMany(BookCatalog::class,'book_id');
    }
    protected static function boot()
    {
        parent::boot();
        static::deleting(function($m) {
             $m->good()->delete(); 
             $cats = $m->cats()->delete();
        });
    }
}
