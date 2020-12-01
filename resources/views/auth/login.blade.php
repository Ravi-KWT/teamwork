@extends("layouts.login")
@section("title","Login")
@section("content")
<!-- START PAGE-CONTAINER -->
<div ng-controller="PeopleCtrl">
  <div class="login_page container-fluid" >
    <a href="#" class="login_togg" data-toggle="modal" data-target="#login_modal"><i class="fa fa-align-justify"></i></a>
    <div class="login_bg">
      <img src="{!! asset("img/demo/team2.jpg")!!}" data-src="{!! asset("img/demo/team2.jpg")!!}" data-src-retina="{!! asset("img/demo/team2.jpg")!!}" alt="" class="lazy">
    </div>
    <div class="modal fade" id="login_modal">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-body">
            <div class="login_form active">
              <div class="form-group">
                <div class="logo">
                  <img src="{{asset("img/logo.png")}}" alt="logo" data-src="{{asset("img/logo.png")}}" data-src-retina="{{ asset("img/logo_2x.png")}}" width="200">
                </div>
              </div>
              <div ng-cloak class="form-group text-center">
                <p>Sign into your account</p>
                <div class="alert alert-danger" role="alert"  ng-if='credential_error' >

                  {% credential_error %}
                </div>
                  @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                      {{ session('error') }}
                    </div>
                  @endif
                {{-- @include("shared.session") --}}
              </div>
             
              <div ng-cloak class="loader" ng-if="loading"></div>
              <form name="login" method="post" class="form" role="form" novalidate>
                {{ csrf_field() }}

                <div class="form-group">
                  <label class="label block"><span>Email<em>*</em></span></label>
                  <input type="email" ng-enter='submitLogin(login)' name='email' placeholder='Email Address' ng-model='login_array.email'  class="form-control" ng-pattern="/^[_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/"  required>
                  <span ng-cloak class='error' ng-show="loginSubmitted && login.email.$error.required" >* Please enter email</span>
                  <span ng-cloak class='error' ng-show="loginSubmitted && login.email.$error.pattern" >* Please enter valid email</span>
                </div>
                <div class="form-group">
                  <label class="label block"><span>Password<em>*</em></span></label>
                  <input type="password"  class="form-control" placeholder="Password" name="password" ng-model="
                  login_array.password" required>
                  <span ng-cloak class='error' ng-show="loginSubmitted && login.password.$error.required">* Please enter password</span>
                </div>
                <div class="form-group">
                  <div class="checkbox">
                    <input type="checkbox" name="remember" ng-model="login_array.remember_me" id="checkbox1">
                    <label for="checkbox1" class="label">Keep Me Signed in</label>
                  </div>
                </div>
                <div class="form-group">
                  <a href="password/reset" class="forgot"><span class="label">Forgot your password?</span></a>
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-md btn-default" ng-click="submitLogin(login)">
                  Login
                  </button>
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