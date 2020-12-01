@extends('layouts.login')
@section('title','Reset Password')
@section('content')
<div ng-controller="PeopleCtrl">
    <div class="login_page container-fluid">
        <a href="#" class="login_togg" data-toggle="modal" data-target="#login_modal"><i class="fa fa-align-justify"></i></a>
        <div class="login_bg">
            <img src="{!! asset('img/demo/team2.jpg')!!}" data-src="{!! asset('img/demo/team2.jpg')!!}" data-src-retina="{!! asset('img/demo/team2.jpg')!!}" alt="" class="lazy">
        </div>
        <div class="modal fade" id="login_modal">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="login_form active">
                            <div ng-cloak class="loader" ng-if="loading"></div>
                            <form name="forgotPasswod" method="POST" class="form" role="form" novalidate>
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <div class="logo">
                                        <img src="{{asset('img/logo.png')}}" alt="logo" data-src="{{asset('img/logo.png')}}" data-src-retina="{{ asset('img/logo_2x.png')}}" width="200">
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <p>Forgot Password</p>
                                    <div ng-cloak class="alert-danger" ng-if='credential_error' >{% credential_error %}</div>
                                    <div ng-cloak class="alert-success" ng-if='success_msg' >{% success_msg %}</div>
                                </div>
                                <div class="form-group">
                                    <label class="label block">E-Mail Address<em>*</em></label>
                                    <input type="email" name='email' placeholder='We will send login details to you' ng-model='forgotPasswod_array.email'  class="form-control" ng-pattern="/^[_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/"  required>
                                    <span ng-cloak class='error' ng-show="forgotPasswordSubmitted && forgotPasswod.email.$error.required" >* Please enter email</span>
                                    <span ng-cloak class='error' ng-show="forgotPasswordSubmitted && forgotPasswod.email.$error.pattern" >* Please enter valid email</span>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-md btn-default" ng-click="submitForgotPassword(forgotPasswod)">
                                    Send Password Reset
                                    </button>
                                    <a href="{!!url('/login')!!}" class='btn btn-md btn-default'>Login</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection