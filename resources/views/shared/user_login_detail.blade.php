<style type="text/css">
	.birthday{
		width: 200px; height: 80px; margin-left: 100px; display: inline-block;
	}
</style>
<div class="user-pic">
	<div class="avtar inline">
		@if(Auth::user()->people)
			@if(Auth::user()->people->photo)
				<a href="{{url('/people',Auth::user()->people->id)}}" >
					<div class='img avatar-md'>
						<img src="{!!Auth::user()->people->photo_url('thumb')!!}">
					</div>
				</a>
			@else
				<div class='img avatar-md'>
					<img src="/img/user.png">
				</div>
			@endif
		@else
			<div class='img avatar-md'>
				<img src="/img/user.png">
			</div>
		@endif
	</div>

</div>

<a href="{{url('/people',Auth::user()->people->id)}}" >
	<div class="user-detail" >
			<span class="name">{!!Auth::user()->people ? Auth::user()->people->name : Auth::user()->email !!}</span>
		</a>
		@if(Auth::user()->people)
			@if(Auth::user()->people->designation)
				<a href="{{url('/people',Auth::user()->people->id)}}" ><span class="designation">{!!Auth::user()->people->designation->name ?Auth::user()->people->designation->name:'-' !!}</span></a>
			@endif
		@endif
		<span class="mailid">
			<a href="{{url('/people',Auth::user()->people->id)}}" >{{-- <a href="mailto:{{Auth::user()->email}}"> --}}{{Auth::user()->email}}</a>
		</span>
	</div>
</a>
@if(\Carbon\Carbon::parse(Auth::user()->people->dob)->format('d-m')==date('d-m'))
	<div class="birthday">
	<div class="welcomepopup-link"><a href="#welcomepopup" class="welcomepopup"><img src="{{asset('img/birthday.gif')}}"></a></div>
	</div>
	<div class="welcome-popup" id="welcomepopup" style="display: none;">
 		<img src="{{asset('img/birthday.gif')}}">   
	 </div>
@endif


@if(Auth::user()->roles=='admin')
<a href="{{url('/people',Auth::user()->people->id)}}" >
	<div class="roles">
		{!!ucfirst(Auth::user()->roles)!!}
		{!!Auth::user()->is_teamlead=='true'?'| Team Lead':''!!}
	</div>
</a>
@endif

@if(Auth::user()->roles=='employee'&& Auth::user()->is_teamlead ==true)
<a href="{{url('/people',Auth::user()->people->id)}}" >
	<div class="roles">
		{!!Auth::user()->is_teamlead=='true'?'Team Lead':''!!}
	</div>
</a>
@endif

