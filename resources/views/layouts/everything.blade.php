<!DOCTYPE html>
<html ng-app="mis">
 <head>
      <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
      <meta charset="utf-8" />
      <title>MIS-@yield('title')</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
      <link rel="apple-touch-icon" href="img/ico/60.png">
      <link rel="apple-touch-icon" sizes="76x76" href="img/ico/76.png">
      <link rel="apple-touch-icon" sizes="120x120" href="img/ico/120.png">
      <link rel="apple-touch-icon" sizes="152x152" href="img/ico/152.png">
      <link rel="icon" type="image/x-icon" href="{{asset('favicon.ico')}}" />
      <meta name="apple-mobile-web-app-capable" content="yes">
      <meta name="apple-touch-fullscreen" content="yes">
      <meta name="apple-mobile-web-app-status-bar-style" content="default">
      <meta content="" name="description" />
      <meta content="" name="author" />

        <link rel="stylesheet" href="{{ elixir('css/vendor.css') }}">
        <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
    <script type="text/javascript">
    window.onload = function()
    {
      // fix for windows 8
      if (navigator.appVersion.indexOf("Windows NT 6.2") != -1)
        document.head.innerHTML += '<link rel="stylesheet" type="text/css" href="pages/css/windows.chrome.fix.css" />'
    }
    </script>
    <style type="text/css">
      .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
      }

      .switch input { 
        opacity: 0;
        width: 0;
        height: 0;
      }

      .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
      }

      .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
      }

      input:checked + .slider {
        background-color: #2196F3;
      }

      input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
      }

      input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
      }

      /* Rounded sliders */
      .slider.round {
        border-radius: 34px;
      }

      .slider.round:before {
        border-radius: 50%;
      }
    </style>
</head>
<body ng-controller="BodyCtrl" class="" id="app-layout">
    <div class="page-container">
        @include('shared.header')
        <div class="page-content-wrapper">
            @yield('content')
        </div>
        @include('shared.footer')
    </div>
    <input type="hidden" id="duration" name="duration" value="{{ Auth::user()->timers->where('running',true)->count() > 0 ? Auth::user()->timers->where('running',true)->first()->duration : 0 }}">
    <input type="hidden" id="last_started_at" name="last_started_at" value="{{ Auth::user()->timers->where('running',true)->count() > 0 ? Auth::user()->timers->where('running',true)->first()->last_started_at : '' }}">
    <input type="hidden" id="timer_id" name="last_started_at" value="{{ Auth::user()->timers->where('running',true)->count() > 0 ? Auth::user()->timers->where('running',true)->first()->id : '' }}">
<script src="{{ elixir('js/vendor.js') }}"></script>
<script src="{{ elixir('js/app.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script type="text/javascript">
    var start_time = null ;
    $(document).ready(function(){
      @if(\Carbon\Carbon::parse(Auth::user()->people->dob)->format('d-m')==date('d-m'))
        $( ".welcomepopup" ).fancybox( {
          maxWidth: 1140,
              maxHeight: 720,
          fitToView: true,
          width: '90%',
          height: '90%',
          autoSize: false,
          closeClick: false,
          openEffect: 'none',
          closeEffect: 'none',
          padding: 0,
          nextEffect: 'none',
          prevEffect: 'none',
              wrapCSS:'welcomepopup-wrap',
          helpers: {
            title: {
              type: 'over'
            },
            overlay: {
              locked: false,
              css: {
                'background-color': 'rgba(0,0,0,0.75)',
                'background-image': 'none',
                'box-shadow': 'none'
              }
            },
          },
        });
        if(!$.cookie('new_user'))
        {
          $('.welcomepopup').trigger("click");
          $.cookie('new_user', true,{ expires: 7} );
        }
      @endif
      $( ".log-timer" ).draggable({ axis: "x",containment: ".page-container", scroll: false });

          if(!!window.performance && window.performance.navigation.type === 2)
          {
            $('.log-timer').html('');
            $.ajax({
            url:'{{ url('/delete-log-timer') }}',
            type:'post',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  },
            data:{},
            success:function(response){
              $('.log-timer').html(response);
            }
          });
          }
          $('.timer-counter-'+ $('#timer_id').val()).html('');
          start_timer();
    });
      function secondsToTime(secs)
      {
          var hours = Math.floor(secs / (60 * 60));
          var divisor_for_minutes = secs % (60 * 60);
          var minutes = Math.floor(divisor_for_minutes / 60);
          var divisor_for_seconds = divisor_for_minutes % 60;
          var seconds = Math.ceil(divisor_for_seconds);
          var ans = hours+ " : "+ minutes +" : "+ seconds;
          var obj = {
              "h": hours,
              "m": minutes,
              "s": seconds
          };
          return obj;
      }
      function start_timer(timer_id = null,last_started_at = null, duration = null)
      {

        if (start_time != null) {
          clearInterval(start_time);
        }
        if (timer_id == null && last_started_at == null && duration == null) 
        {
          var duration = $('#duration').val();
          var last_started_at = $('#last_started_at').val();
          var timer_id = $('#timer_id').val();
        }
        if (last_started_at != '' && timer_id != '') 
        {
          $('.timer-counter-'+timer_id).html('');
          var current_time = new Date();
          current_time = current_time.getFullYear() + "-" + (current_time.getMonth()+1) + "-" + current_time.getDate() + " " + current_time.getHours() + ":" + current_time.getMinutes() + ":" + current_time.getSeconds();
          var current_date = new Date(current_time);
          var last_date = new Date(last_started_at);
          var total_seconds = Math.abs(current_date - last_date) / 1000;
          var minutes = Math.floor(total_seconds / 60) % 60;
          // var total_seconds = (current_date.getTime() - last_date.getTime()) / 1000;
          total_seconds = total_seconds % 60;
          total_seconds = parseInt(total_seconds) + parseInt(minutes * 60)
          var total_duration = parseInt(total_seconds) + parseInt(duration) ;
          var spent_time = secondsToTime(total_duration);

          
          var seconds = spent_time.s;
          var minutes = spent_time.m;
          var hours = spent_time.h;
          start_time = setInterval(function() {
            seconds++;
            if (seconds == 60) {
              minutes++;
              seconds = 0;
            }
            if (minutes == 60) {
              hours++;
              seconds = 0;
            }
            var formattedHour = ("0" + hours).slice(-2);
            var formattedMinutes = ("0" + minutes).slice(-2);
            var formattedSeconds = ("0" + seconds).slice(-2);
            var currentTimeString = formattedHour + ":" + formattedMinutes + ":" + formattedSeconds;
            $('.timer-counter-'+timer_id).html(currentTimeString);
            
          }, 1000);
        }
      }
          
    //instantiate a Pusher object with our Credential's key
    // var pusher = new Pusher('7e33696f1827248f3d59', {
    //     cluster: 'ap2',
    //     forceTLS: true
    //   });
    // var channel = pusher.subscribe('user-availability');
    // var user = {!! Auth::user() !!};
    // channel.bind('App\\Events\\UserAvailability', function(data) {
    //     console.log(user.roles);
    //     if(user.roles == "admin" || (user.roles  == 'employee' && user.is_teamlead == true) || user.id == 35)
    //     {
    //       console.log(data);
    //       $('#availableornot').addClass('visible');
    //       if (data.status == "false") {
    //         $('#message').html(data.user.fname+' has work so now he/she is not available.');
    //       }
    //       else{
    //         $('#message').html(data.user.fname+' is now available.');
    //       }
    //       setTimeout(function(){
    //         $('#availableornot').removeClass('visible');
    //       },4000);
    //     }
    // });

          $(document).on('click','.pause-timer',function(e){
            e.preventDefault();
            clearInterval(start_time);
            var _this = $(this);
            var timer_id = $(this).data('timer-id');
            $.ajax({
              url:'{{ route('pause-log-timer') }}',
              type:'post',
              headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  },
              data:{timer_id:timer_id},
              success:function(response){
                $(_this).parent().find('.timer-counter-'+timer_id).html(response.duration);
                $(_this).hide();
                $(_this).parent().find('.resume-timer').show();
              }
            });
          });
          $(document).on('click','.resume-timer',function(e){
            e.preventDefault();
            var _this = $(this);
            var timer_id = $(this).data('timer-id');
            if (start_time != null) {
              clearInterval(start_time);
              $(document).find('.pause-timer').parent().find('.resume-timer').show();
              $(document).find('.pause-timer').hide();
            }
            $.ajax({
              url:'{{ url('/start-log-timer') }}',
              type:'post',
              headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  },
              data:{timer_id:timer_id},
              success:function(response){             
                start_timer(timer_id,response.last_started_at,response.duration);
                $(_this).hide();
                $(_this).parent().find('.pause-timer').show();
              }
            });
          });

        $(document).on('click','.delete-log',function(e){
          e.preventDefault();
          var _this = $(this);
          var timer_id = $(this).data('timer-id');
          swal({
            title: "Are you sure?",
            text: "Your log will be deleted!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
            $.ajax({
              url:'{{ url('/delete-log-timer') }}',
              type:'post',
              headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  },
              data:{timer_id:timer_id},
              success:function(response){
                $('.log-timer').html(response);
                  swal("Your log has been deleted!", {
                    icon: "success",
                  });
              }
            });
            } else {
              swal("Your log is safe!");
            }
          });
        });
        $(document).on('click','.submit-log-timer',function(e){
          e.preventDefault();
          var timer_id = $(this).data('timer-id');
          var description = $(this).parent().parent().parent().parent().find('.description').val();
          var billable = $(this).parent().parent().parent().parent().find('.billable').is(':checked');
          var pause = $(this).parent().find('.pause-timer').attr('style');
          if (pause != "display: none;") 
          {
            $(this).parent().find('.pause-timer').click();
          }
          
          swal({
            title: "Are you sure want to save log?",
            // text: "Your log will be deleted!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
            $.ajax({
              url:'{{ url('/submit-log-timer') }}',
              type:'post',
              headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  },
              data:{timer_id:timer_id, description:description, billable:billable},
              success:function(response){
                $('.log-timer').html(response);
                  swal("Logtime Added successfully!", {
                    icon: "success",
                  });
              }
            });
            } else {
              swal("Your log is safe!");
            }
          });
        });
        $(document).on('click','.addition-timer-data-show',function(e){
          e.preventDefault();
          $(document).find('.addition-timer-data-hide').removeClass('addition-timer-data-hide').addClass('addition-timer-data-show');
          $(this).removeClass('addition-timer-data-show');
          $(this).addClass('addition-timer-data-hide');
          var timer_id = $(this).data('timer-id');
          $('.addition-timer-data').css('display','none');
          $('#collapseExample'+timer_id).css('display','block');
        });
        $(document).on('click','.addition-timer-data-hide',function(e){
          e.preventDefault();
          var timer_id = $(this).data('timer-id');
          $(this).removeClass('addition-timer-data-hide');
          $(this).addClass('addition-timer-data-show');
          $('#collapseExample'+timer_id).css('display','none');
        });
        $(document).on('click','#current-timer-show',function(e){
          e.preventDefault();
          var type = $(this).data('type');
          if (type == 'up') 
          {
            $(document).find('.time-log-window').children('.log-item').css('display','block');  
            $(this).data('type','down').html('<i class="fa fa-angle-down"></i>');
            
          }
          else
          {
            $(document).find('.log-item').css('display','none');
            $(document).find('.time-log-window').children('.log-item').first().css('display','block');
            $(this).data('type','up').html('<i class="fa fa-angle-up"></i>');
          }
        });
  </script>

@yield('scripts')
</body>
</html>