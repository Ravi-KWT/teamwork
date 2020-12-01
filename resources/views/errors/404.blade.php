@extends('layouts.register')
@section('title','404')
@section('content')
<div class="page_404">
  <div class="wrap">
    <h1>404</h1>
    <h2>Sorry but we couldnt find this page</h2>
    <p>This page you are looking for does not exsist <a href="{!! url('/') !!}">Back</a></p>
  </div>
  <div class="bg">
    <img src="{!! asset('img/demo/team2.jpg')!!}" data-src="{!! asset('img/demo/team2.jpg')!!}" data-src-retina="{!! asset('img/demo/team2.jpg')!!}" alt="" class="lazy">
  </div>
</div>
@endsection