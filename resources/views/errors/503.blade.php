@extends('layouts.register')
@section('title','503')
@section('content')
<div class="page_503">
  {{-- <div class="bg">
    <img src="{!! asset('img/demo/team2.jpg')!!}" data-src="{!! asset('img/demo/team2.jpg')!!}" data-src-retina="{!! asset('img/demo/team2.jpg')!!}" alt="" class="lazy">
  </div> --}}
  <div class="wrap">
    <img src="{!! asset('img/503.jpg') !!}">
    {{-- <h1>We will right back to you very soon. Back</h1> --}}
  </div>
</div>
<style type="text/css">
.page_503 .wrap{
    padding: 0;
    background-color: transparent;
    top: 100px;
    bottom: inherit;
}
.page_503 .wrap h1{
    padding: 10px;
    margin: 0;
    background-color: #fff;
    color: red;
    text-align: center;
    font-size: 14px;
}
.page_503 .wrap h1 a{
    color: #000;
}
.page_503 .wrap img{
    margin-bottom: 0px;
}
</style>
@endsection