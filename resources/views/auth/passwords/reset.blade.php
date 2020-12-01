@extends('layouts.login')
@section('title','Reset Password')
@section('content')
<div class="login_page container-fluid">
  <a href="#" class="login_togg" data-toggle="modal" data-target="#login_modal"><i class="fa fa-align-justify"></i></a>
  <div class="login_bg">
    <img src="{!! asset('img/demo/team2.jpg')!!}" data-src="{!! asset('img/demo/team2.jpg')!!}" data-src-retina="{!! asset('img/demo/team2.jpg')!!}" alt="" class="lazy">
  </div>
  <div class="modal fade" id="login_modal">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <div class="login_form">
            <div class="form-group">
              <div class="logo">
                <img src="{{asset('img/logo.png')}}" alt="logo" data-src="{{asset('img/logo.png')}}" data-src-retina="{{asset('img/img/logo_2x.png')}}" width="150" height="30">
              </div>
            </div>
            <div class="form-group text-center">
              <p>Reset Password</p>
            </div>
            {!!Former::framework('Nude') !!}
            @include('shared.session')
            {!! Former::open()->method('post')->action( url('/password/reset'))->class('form')->role('form')->token() !!}
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group">
              {!!  Former::label('E-Mail Address')->class('label')!!}
              {!!  Former::email('email','' )->class('form-control')->placeholder('Email Address')->id(false)!!}
              @if ($errors->has('email'))
              <span class="error">
                <strong>{{ $errors->first('email') }}</strong>
              </span>
              @endif
            </div>
            <div class="form-group">
              {!!  Former::label('Password')->class('label')!!}
              {!!  Former::password('password')->placeholder('Password')->id(false)->label(false)->class('form-control required') !!}
              @if ($errors->has('password'))
              <span class="error">
                <strong>{{ $errors->first('password') }}</strong>
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
            {!!Former::submit('Send Password Reset')->class('btn btn-md btn-default')!!}
            {!! Former::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection