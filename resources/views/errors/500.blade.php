@extends('layouts.register')
@section('title','500')
@section('content')
<div class="page_404">
  <div class="wrap">
    <h1>500</h1>
    <h2>Internal Server error</h2>

    <p>Opps, Something went wrong <a href="{!! url('/') !!}">Back</a></p>
  </div>
  <div class="bg">
    <img src="{!! asset('img/demo/team2.jpg')!!}" data-src="{!! asset('img/demo/team2.jpg')!!}" data-src-retina="{!! asset('img/demo/team2.jpg')!!}" alt="" class="lazy">
  </div>
</div>
@endsection