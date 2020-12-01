@extends('layouts.change_profile')
@section('title','Change Password')
@section('content')
<div class="login_page change_profile_page">
    <div class="container">
        <div class="form-group">
            <div class="logo">
                <img src="{{asset('img/logo.png')}}" alt="logo" data-src="{{asset('img/logo.png')}}" data-src-retina="{{asset('img/img/logo_2x.png')}}" width="300">
            </div>
        </div>
        <div class="form-group text-center">
            <p>Change Profile</p>
        </div>
        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
          @if(auth()->user()->people->photo)
            <div class="user_profile_pic">
                <div class="wraps">
                    <img src="{{auth()->user()->people->photo_url('medium')}}" alt="profile pic">
                  {{-- <img src="{!!asset('img/profile-pic-1.jpg')!!}" alt="profile"> --}}
                    <div id='select-pic-container'>
                        <div class="change-pic" id='select-pic'>
                             <a htef='javascript:;' >Change Photo<i class="fa fa-camera"></i></a>
                        </div>
                    </div>
                </div>
                <div class='notice'>*Upload Only JPG,PNG,JPEG</div>
                <div class="fileextensionerror" id='console'></div>
            </div>
          @else
            <div class="user_profile_pic">
                <div class="wraps">
                  <img src="{{asset('img/user-profile.png')}}" alt="profile pic">

                  <div id='select-pic-container'>
                        <div class="change-pic" id='select-pic'>
                             <a htef='javascript:;' >Change Photo<i class="fa fa-camera"></i></a>
                        </div>
                    </div>
                </div>
                <div class='notice'>*Upload only jpg,png </div>
                <div  id="console" class="fileextensionerror"> </div>
            </div>
          @endif
          </div>
          <div class="loader" style="display:none;"></div>
          <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <?php
            $marital_statuses = array('maried'=>'maried',
                'single'=>'single',
                'other'=>'other');
            ?>
            {!!Former::framework('Nude') !!}
            @include('shared.session')
            {!! Former::open()->method('post')->action( url('change-profile'))->class('form')->role('form')->token() !!}
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        {!!  Former::label('First Name')->class('label')!!}
                        {!!  Former::text('fname')->placeholder('first name')->id(false)->label(false)->class('form-control') !!}
                        @if ($errors->has('fname'))
                          <span class="error">
                            <strong>{{ $errors->first('fname') }}</strong>
                          </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        {!!  Former::label('Last Name')->class('label')!!}
                        {!!  Former::text('lname')->placeholder('last name')->id(false)->label(false)->class('form-control') !!}
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        {!!  Former::label('Phone')->class('label')!!}
                        {!!  Former::text('phone')->placeholder('phone')->id(false)->label(false)->class('form-control') !!}
                        @if ($errors->has('phone'))
                          <span class="error">
                            <strong>{{ $errors->first('phone') }}</strong>
                          </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        {!!  Former::label('Mobile')->class('label')!!}
                        {!!  Former::text('mobile')->placeholder('mobile')->id(false)->label(false)->class('form-control') !!}
                        @if ($errors->has('mobile'))
                          <span class="error">
                            <strong>{{ $errors->first('mobile') }}</strong>
                          </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        {!!  Former::label('Google')->class('label')!!}
                        {!!  Former::text('google')->placeholder('google')->id(false)->label(false)->class('form-control') !!}
                        @if ($errors->has('google'))
                          <span class="error">
                            <strong>{{ $errors->first('google') }}</strong>
                          </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        {!!  Former::label('Facebook')->class('label')!!}
                        {!!  Former::text('facebook')->placeholder('facebook')->id(false)->label(false)->class('form-control') !!}
                         @if ($errors->has('facebook'))
                          <span class="error">
                            <strong>{{ $errors->first('facebook') }}</strong>
                          </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        {!!  Former::label('Website')->class('label')!!}
                        {!!  Former::text('website')->placeholder('website')->id(false)->label(false)->class('form-control') !!}
                        @if ($errors->has('website'))
                            <span class="error">
                            <strong>{{ $errors->first('website') }}</strong>
                          </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        {!!  Former::label('LinkedIn')->class('label')!!}
                        {!!  Former::text('linkedin')->placeholder('linkedin')->id(false)->label(false)->class('form-control') !!}
                          @if ($errors->has('linkedin'))
                            <span class="error">
                            <strong>{{ $errors->first('linkedin') }}</strong>
                          </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        {!!  Former::label('Skype')->class('label')!!}
                        {!!  Former::text('skype')->placeholder('skype')->id(false)->label(false)->class('form-control') !!}
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        {!!  Former::label('Twitter')->class('label')!!}
                        {!!  Former::text('twitter')->placeholder('twitter')->id(false)->label(false)->class('form-control') !!}
                          @if ($errors->has('twitter'))
                            <span class="error">
                            <strong>{{ $errors->first('twitter') }}</strong>
                          </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        {!!Former::submit('Update')->class('btn btn-md btn-default')!!}
                       {{--  <a href="{{ url('/') }}" class="btn btn-md btn-default">Home</a> --}}
                        <a href="{{ url()->previous() }}" class="btn btn-md btn-default">Back</a>
                    </div>
                </div>
            </div>
            {!! Former::close() !!}
          </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

@section('scripts')

<script type="text/javascript">
    $(document).ready(function() {
//START CROP WITH PLUPLOAD
var uploader = new plupload.Uploader({
      runtimes : 'html5,flash,silverlight,html4',
      rename : true,
      browse_button : "select-pic",
      container: document.getElementById('select-pic-container'),
      url : "{!! asset('/plupload/upload.php') !!}",

      filters: {
          mime_types : [
            { title : "Image files", extensions : "jpg,png,jpeg" }

          ],
          max_file_size: "20mb"
    },
      multi_selection:false,
      // Flash settings
      flash_swf_url : "{!! asset('/plupload/Moxie.swf') !!}",
      // Silverlight settings
      silverlight_xap_url : "{!!asset('/plupload/Moxie.xap')!!}",

      init: {
            PostInit: function() {
            },

            FilesAdded: function(up, files) {
                $(".loader").show();
                files[0].name = "{{ uniqid() }}_"+files[0].name;
                uploader.start();
            },

            FileUploaded: function(up,file)
            {
                    var post={};
                    post.photo=file.name;
                    var url ='/update-user-profile-photo';

                   $.ajax({
                   type: "POST",
                   url: url,
                   data: post,
                   cache: false,
                   success: function(data){
                        $(".loader").hide();
                        window.location.reload();
                    }

            });

            },
            Error: function(up, err) {
                document.getElementById('console').innerHTML = "<span>\nError #" + err.message+"</span>";
                $(".loader").hide();
            }
            }
      });
      uploader.init();

  });
//END CROP WITH PLUPLOAD
</script>
@endsection

@endsection
