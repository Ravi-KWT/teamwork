@extends('layouts.app')
@section('title','Change Password')
@section('content')
<div class="login_page change_pass container-fluid">
  {{-- <a href="#" class="login_togg" data-toggle="modal" data-target="#login_modal"><i class="fa fa-align-justify"></i></a>
  <div class="login_bg">
    <img src="{!! asset('img/demo/team2.jpg')!!}" data-src="{!! asset('img/demo/team2.jpg')!!}" data-src-retina="{!! asset('img/demo/team2.jpg')!!}" alt="" class="lazy">
  </div> --}}
        <div class="col-lg-4 col-center">
          <div class="login_form">
            {{-- <div class="form-group">
              <div class="logo">
                <img src="{{asset('img/logo.png')}}" alt="logo" data-src="{{asset('img/logo.png')}}" data-src-retina="{{asset('img/img/logo_2x.png')}}" width="150" height="30">
              </div>
            </div>
            <div class="form-group text-center">
              <p>Change Password</p>
            </div> --}}
            {{-- <div class="avtar block">
              <div class="img avatar-sm">
                <img src="{{auth()->user()->people->photo_url('thumb')}}" alt="user">
              </div>
            </div> --}}
            {!!Former::framework('Nude') !!}
            @include('shared.session')
            {!! Former::open()->method('post')->action( url('change-password'))->class('form')->role('form')->token() !!}
            <div class="form-group">
              {!!  Former::label('Current Password')->class('label')!!}
              {!!  Former::password('oldpassword')->placeholder('Current Password')->id(false)->label(false)->class('form-control required') !!}
              @if ($errors->has('oldpassword'))
              <span class="error">
                <strong>{{ $errors->first('oldpassword') }}</strong>
              </span>
              @endif
            </div>
            <div class="form-group">
              {!!  Former::label('New Password')->class('label')!!}
              {!!  Former::password('newpassword')->placeholder('New Password')->id(false)->label(false)->class('form-control required') !!}
              @if ($errors->has('newpassword'))
              <span class="error">
                <strong>{{ $errors->first('newpassword') }}</strong>
              </span>
              @endif
            </div>
            <div class="form-group">
              {!!  Former::label('Confirm Password')->class('label')!!}
              {!!  Former::password('password_confirmation')->id(false)->placeholder('Confirm Password')->label(false)->class('form-control required') !!}
              @if ($errors->has('password_confirmation'))
              <span class="error">
                <strong>{{ $errors->first('password_confirmation') }}</strong>
              </span>
              @endif
            </div>
           
            {!!Former::submit('Change Password')->class('btn btn-md btn-default')!!}
             {{-- <a href="{{ url('/') }}" class="btn btn-md btn-default">Home</a> --}}
             <a href="{{ url()->previous() }}" class="btn btn-md btn-default">Back</a>
            {!! Former::close() !!}
          </div>
        </div>
      </div>
@endsection