@extends('phone.booklist')
<?php
$keyword = '';
$url = '"/class-more/"+keyword2+"/"+last_id';
?>
@section('title',$keyword2.'的分类')
@section('tool')
@include('phone.layout.searchbox')
@endsection



