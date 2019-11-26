<?php
namespace App\Front\Controllers;

use Illuminate\Http\Request,
    App\Model\Book,
    App\Model\BookCatalog;

class SitemapController{
    function index(){
        $books = Book::where('spider_status',1)->select('id','status','updated_at')->orderBy('updated_at','desc')->limit('800')->get();
        return response()->view('sitemap', [
            'books' => $books,
        ])->header('Content-Type', 'text/xml');
    }
    function book($book_id){
        $book = Book::where('id',$book_id)->select('id','status','updated_at')->first();
        $cats = BookCatalog::where('book_id',$book_id)->select('id','book_id','spider_status','updated_at')->orderBy('updated_at','desc')->limit(10)->get();
        return response()->view('booksitemap', [
            'book' => $book,
            'cats'=>$cats
        ])->header('Content-Type', 'text/xml');
    }
    

}