<!DOCTYPE html>
<html  ng-app="mis">
	 <head>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
		<meta name="csrf-token" content="{{ csrf_token() }}">
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
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">

        <style type="text/css">
        .datepicker .input-group{
            width: 100%;
        }
        .datepicker .input-group-addon{
            display: table-cell;
            cursor: pointer;
        }
        .input-group.bootstrap-timepicker .form-control{
            text-align: left;
        }
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
        <style>
		    .swal-button--confirm {
		      background-color: #DD6B55;
		    }
		  </style>
        </style>
        <link rel="stylesheet" type="text/css" href="{{ asset('/css/developer.css') }}">
                @yield('styles')
       
	</head>
	{{-- body part start --}}
	@if(Request::segment(1) == '')
	<body ng-controller="BodyCtrl" class="sidebar-visible {{ Auth::user()->theme ? Auth::user()->theme->class : 'default' }}" id="app-layout" oncontextmenu="return false" >
		@include('shared.company_list')
		@elseif(Request::segment(1) == 'companies')
	<body ng-controller="BodyCtrl" class="without_sidebar {{ Auth::user()->theme ? Auth::user()->theme->class : 'default' }}" id="app-layout" oncontextmenu="return false" >
	@elseif(Request::segment(1) == 'industries')
	<body ng-controller="BodyCtrl" class="without_sidebar {{ Auth::user()->theme ? Auth::user()->theme->class : 'default' }}" id="app-layout" oncontextmenu="return false" >
	@elseif(Request::segment(1) == 'everything')
	<body ng-controller="BodyCtrl" class="without_sidebar {{ Auth::user()->theme ? Auth::user()->theme->class : 'default' }}" id="app-layout" oncontextmenu="return false" >
	@elseif(Request::segment(1) == 'departments')
		<body ng-controller="BodyCtrl" class="without_sidebar {{ Auth::user()->theme ? Auth::user()->theme->class : 'default' }}" id="app-layout" oncontextmenu="return false" >
	@elseif(Request::segment(1) == 'project-categories')
		<body ng-controller="BodyCtrl" class="without_sidebar {{ Auth::user()->theme ? Auth::user()->theme->class : 'default' }}" id="app-layout" oncontextmenu="return false" >
	@elseif(Request::segment(1) == 'task-categories')
		<body ng-controller="BodyCtrl" class="without_sidebar {{ Auth::user()->theme ? Auth::user()->theme->class : 'default' }}" id="app-layout" oncontextmenu="return false" >
	@elseif(Request::segment(1) == 'designations')
		<body ng-controller="BodyCtrl" class="without_sidebar {{ Auth::user()->theme ? Auth::user()->theme->class : 'default' }}" id="app-layout" oncontextmenu="return false" >
	@elseif(Request::segment(1) == 'people')
		<body ng-controller="BodyCtrl" class="without_sidebar {{ Auth::user()->theme ? Auth::user()->theme->class : 'default' }}" id="app-layout" oncontextmenu="return false" >

    @elseif(Request::segment(1)=='projects' && Request::segment(3) == 'tasks' & Request::segment(4) == '')
	<body ng-controller="BodyCtrl" class="sidebar-visible {{ Auth::user()->theme ? Auth::user()->theme->class : 'default' }}" id="app-layout" oncontextmenu="return false">
		@include('shared.project_detail')

	@elseif(Request::segment(1) == 'projects' && Request::segment(2) == '')
	<body ng-controller="BodyCtrl" class="sidebar-visible {{ Auth::user()->theme ? Auth::user()->theme->class : 'default' }}" id="app-layout" oncontextmenu="return false" >
		@include('shared.project_list')
	@elseif(Route::currentRouteNamed('search-projects'))
	<body ng-controller="BodyCtrl" class="sidebar-visible {{ Auth::user()->theme ? Auth::user()->theme->class : 'default' }}" id="app-layout" oncontextmenu="return false" >
		@include('shared.project_list')

    @elseif(Request::segment(1) == 'category-wise-tasks' && Request::segment(2) != '')
	<body ng-controller="BodyCtrl" class="without_sidebar {{ Auth::user()->theme ? Auth::user()->theme->class : 'default' }}" id="app-layout" oncontextmenu="return false" >


	@elseif(Request::segment(1)=='projects' && Request::segment(3) == 'milestones')
		<body ng-controller="BodyCtrl" class="sidebar-visible {{ Auth::user()->theme ? Auth::user()->theme->class : 'default' }}" id="app-layout" oncontextmenu="return false" >
        @include('shared.project_detail')

	@elseif(Request::segment(1)=='projects-list')
		<body ng-controller="BodyCtrl" class="sidebar-visible no-page-title {{ Auth::user()->theme ? Auth::user()->theme->class : 'default' }}" id="app-layout" oncontextmenu="return false" >
        @include('shared.project_list')

    @elseif(Request::segment(1)=='projects' && Request::segment(3) == 'people')
	<body ng-controller="BodyCtrl" class="sidebar-visible {{ Auth::user()->theme ? Auth::user()->theme->class : 'default' }}" id="app-layout" oncontextmenu="return false" >
		@include('shared.project_detail')

    @elseif((Request::segment(1)=='projects') && (Request::segment(3) == 'tasks') && (Request::segment(4) != ''))
    <body ng-controller="BodyCtrl" class="sidebar-visible {{ Auth::user()->theme ? Auth::user()->theme->class : 'default' }}" id="app-layout" oncontextmenu="return false" >
        @include('shared.task_detail_sidebar')
    @else
      <body ng-controller="BodyCtrl" class="{{ Auth::user()->theme ? Auth::user()->theme->class : 'default' }}" id="app-layout" oncontextmenu="return false" >
    @endif
    	@include('shared.header')
    	
    	<div class="alert alert-warning alert-dismissible alert-user-availableornot" id="availableornot" role="alert">
		  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		  	<span aria-hidden="true">&times;</span>
		  </button>
		  <strong id="message"></strong>
		</div>
		
		<div class="page-container">
			<div class="page-content-wrapper">
				@yield('content')
			</div>
			@include('shared.footer')
		</div>
		
		<input type="hidden" id="duration" name="duration" value="{{ Auth::user()->timers->where('running',true)->count() > 0 ? Auth::user()->timers->where('running',true)->first()->duration : 0 }}">
		<input type="hidden" id="last_started_at" name="last_started_at" value="{{ Auth::user()->timers->where('running',true)->count() > 0 ? Auth::user()->timers->where('running',true)->first()->last_started_at : '' }}">
		<input type="hidden" id="timer_id" name="timer_id" value="{{ Auth::user()->timers->where('running',true)->count() > 0 ? Auth::user()->timers->where('running',true)->first()->id : '' }}">

		<script src="{{ elixir('js/vendor.js') }}"></script>
		<script src="{{ elixir('js/app.js') }}"></script>
		
		
		<script src="https://www.gstatic.com/firebasejs/7.9.3/firebase-app.js"></script>
		<script src="https://www.gstatic.com/firebasejs/7.9.3/firebase-analytics.js"></script>
		<script src="https://www.gstatic.com/firebasejs/7.9.3/firebase-messaging.js"></script>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		
        @yield('scripts')    
	   	{{-- <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script> --}}
	    <script>
	    	
	    	
	    	var start_time = null ;
	    	$(document).ready(function(){
	    		var draggable = $('#log-timer'); //element 

	    		draggable.on('mousedown', function(e){
	    			var dr = $(this).addClass("drag").css("cursor","move");
	    			height = dr.outerHeight();
	    			width = dr.outerWidth();
	    			max_left = dr.parent().offset().left + dr.parent().width() - dr.width();
	    			max_top = dr.parent().offset().top + dr.parent().height() - dr.height();
	    			min_left = dr.parent().offset().left;
	    			min_top = dr.parent().offset().top;

	    			// ypos = dr.offset().top + height - e.pageY,
	    			xpos = dr.offset().left + width - e.pageX;
	    			$(document.body).on('mousemove', function(e){
	    				// var itop = e.pageY + ypos - height;
	    				var ileft = e.pageX + xpos - width;
	    				
	    				if(dr.hasClass("drag")){
	    					// if(itop <= min_top ) { itop = min_top; }
	    					if(ileft <= min_left ) { ileft = min_left; }
	    					// if(itop >= max_top ) { itop = max_top; }
	    					if(ileft >= max_left ) { ileft = max_left; }
	    					dr.offset({ left: ileft});
	    				}
	    			}).on('mouseup', function(e){
	    					dr.removeClass("drag");
	    			});
	    		});

	    		// $( ".log-timer" ).draggable({ axis: "x",containment: ".page-container", scroll: false });

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
	    			var current_date = new Date(current_time.replace(/-/g, '/'));
	    			var last_date = new Date(last_started_at.replace(/-/g, '/'));
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
	    			start_time = window.setInterval(function() {
	    				seconds++;
	    				if (seconds == 60) {
	    					minutes++;
	    					seconds = 0;
	    				}
	    				if (minutes == 60) {
	    					hours++;
	    					seconds = 0;
	    					minutes = 0;
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
         //  		cluster: 'ap2',
         //  		forceTLS: true
         //  	});
	        // var channel = pusher.subscribe('user-availability');
	        // var user = {!! Auth::user() !!};
	        // channel.bind('App\\Events\\UserAvailability', function(data) {
	        //   	console.log(user.roles);
	        //   	if(user.roles == "admin" || (user.roles  == 'employee' && user.is_teamlead == true) || user.id == 35)
	        //   	{
	        //   		console.log(data);
	        //   		$('#availableornot').addClass('visible');
	        //   		if (data.status == "false") {
	        //   			$('#message').html(data.user.fname+' has work so now he/she is not available.');
	        //   		}
	        //   		else{
	        //   			$('#message').html(data.user.fname+' is now available.');
	        //   		}
	        //   		setTimeout(function(){
	        //   			$('#availableornot').removeClass('visible');
	        //   		},4000);
	        //   	}
	        // });
    	    // start push notification

	    	    // if('serviceWorker' in navigator) 
	    	    // {
	    	    //  	navigator.serviceWorker
	    	    //  	.register('/firebase-messaging-sw.js')
	    	    //  	.then(function() { console.log("Service Worker Registered"); });
	    	    // }
		    	// Your web app's Firebase configuration
		    	// var firebaseConfig = 
		    	// {
		    	// 	apiKey: "AIzaSyAEm9cjXTgLji1Bh0Z6BTBSldIrskreHcs",
		    	// 	authDomain: "teamwork-bff6a.firebaseapp.com",
		    	// 	databaseURL: "https://teamwork-bff6a.firebaseio.com",
		    	// 	projectId: "teamwork-bff6a",
		    	// 	storageBucket: "teamwork-bff6a.appspot.com",
		    	// 	messagingSenderId: "249944962835",
		    	// 	appId: "1:249944962835:web:92d07c58e36427226c4ce6",
		    	// 	measurementId: "G-VK3Q35YP24"
		    	// };
		    	// Initialize Firebase
		    	// firebase.initializeApp(firebaseConfig);
		    	// firebase.analytics();

		    	// const messaging = firebase.messaging();

		    	// messaging
		    	// .requestPermission()
		    	// .then(function () {	    		               
		    	// 	console.log("Notification permission granted.");
		    	// 	return messaging.getToken();
		    	// })
		    	// .then(function(token) {
		    	// 	$.get('{!! route("user-fcm-token") !!}',{token:token},function(response){
		    	// 		console.log(response);
		    	// 		$('#fcm_token').val(token);
		    	// 	});
		    	// 	console.log(token);

		    	// })
		    	// .catch(function (err) {
		    	// 	console.log("Unable to get permission to notify.", err);
		    	// });

		    	// messaging.onMessage(function(payload) {
		    	// 	const title = payload.notification.title;
		    	// 	const options = {
		    	// 		body: payload.notification.body,
		    	// 		icon: payload.notification.icon,
		    	// 	};
		    	// 	const notification = new Notification(title, options);
		    	// 	console.log("Message received. ", payload);
		    	// });
	    	// end push notification
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
							
							if (response.length > 1) {
								$('.log-timer').html(response);
							}
							else{
								$('.log-timer').css('display','none');	
							}
							$(document).find('.start-timer').data('task-id',timer_id).show();
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
							if (response.length > 1) {
								$('.log-timer').html(response);
							}
							else{
								$('.log-timer').css('display','none');	
							}
							$(document).find('.start-timer').data('task-id',timer_id).show();
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
	</body>
	{{-- body part end --}}
</html>