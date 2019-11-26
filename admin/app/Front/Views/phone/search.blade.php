@extends('phone.booklist')
<?php
$url = '"/search-more/"+keyword+"/"+last_id';
?>
@section('title','关于'.$keyword.'的搜索结果')
@section('tool')
@include('phone.layout.searchbox')
@endsection

