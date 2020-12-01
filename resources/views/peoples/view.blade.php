@extends('layouts.app')
@section('title','People')
@section('content')
<div ng-controller="PeopleCtrl">
	<div class="content">
		<div class="per-profile">
			<div class="name-pic">
				<div class="avtar inline">
					<div class="img avatar-sm">
						@if($people->photo == '')
							<img src="{!! asset('img/noPhoto.png') !!}" />
						@else
							<img src="{!! $people->photo_url() !!}" />
						@endif

					</div>
					
				</div>
				<div class="name-post">

					<div class="name">{!! $people->name !!}</div>
					<div class="post">{!! $people->designation ?$people->designation->name:'&nbsp;'  !!}</div>
				</div>
				<a href="{{request()->headers->get('referer')}}" class="pull-right" style="margin-top: 15px;"><i class="fa fa-arrow-left"></i> Back</a>
			</div>
			<div class="detail">
				<div class="detail-group">
					<fieldset>
						<legend>Personal</legend>
						<div class="form-group">
							<label>E-mail</label>
							<div class="input-detail">
								<a href="mailto:{!! $people->user->email!!}">{!! $people->user->email!!}</a>
							</div>
						</div>
						<div class="form-group">
							<label>Birthdate</label>
							<div class="input-detail">
								{!! $people->dob!!}
							</div>
						</div>
						<div class="form-group">
							<label>Mobile</label>
							<div class="input-detail">
								{!! $people->mobile?$people->mobile:'-'!!}
							</div>
						</div>
						<div class="form-group">
							<label>Phone</label>
							<div class="input-detail">
								{!!$people->phone?$people->phone:'-'!!}
							</div>
						</div>
						<div class="form-group">
							<label>Gender</label>
							<div class="input-detail">
								{!! ucfirst($people->gender?$people->gender:'-')!!}
							</div>
						</div>
						<div class="form-group">
							<label>Marital status</label>
							<div class="input-detail">
								{!! ucfirst($people->marital_status?$people->marital_status:'-')!!}
							</div>
						</div>
						@if(Auth::user()->roles != 'admin')
							<div class="form-group">
								<label>Department</label>
									<div class="input-detail">
										{!!$people->department?$people->department->name:'-'!!}
									</div>
							</div>
						@endif
					</fieldset>
				</div>
				<div class="detail-group">
					<fieldset>
						<legend>Address</legend>
						<div class="form-group">
							<label>Address</label>
							<div class="input-detail">
								{!! ucfirst($people->adrs1?$people->adrs1:'')!!} {!! ucfirst($people->adrs2?$people->adrs2:'')!!}
							</div>
						</div>
						<div class="form-group">
							<label>City</label>
							<div class="input-detail">
								{!! ucfirst($people->city?$people->city:'-')!!}
							</div>
						</div>
						<div class="form-group">
							<label>Zipcode</label>
							<div class="input-detail">
								{!! ucfirst($people->zipcode?$people->zipcode:'-')!!}
							</div>
						</div>
						<div class="form-group">
							<label>State</label>
							<div class="input-detail">
								{!! ucfirst($people->state?$people->state:'-')!!}
							</div>
						</div>
						<div class="form-group">
							<label>Country</label>
							<div class="input-detail">
								{!! ucfirst($people->country?$people->country:'-')!!}
							</div>
						</div>
					</fieldset>
				</div>
				@if(Auth::user()->roles == 'admin')
					@if(count($educations)>0)
					<div class="detail-group">
						@foreach($educations as $eduKey=>$education)
						<fieldset>
							<legend>Education - {!!$eduKey+1!!}</legend>
							<div class="form-group">
								<label>Qualification</label>
								<div class="input-detail">
									{!!$education->qualification?$education->qualification:'-'!!}
								</div>
							</div>
							<div class="form-group">
								<label>College</label>
								<div class="input-detail">
									{!!$education->college?$education->college:'-'!!}
								</div>
							</div>
							<div class="form-group">
								<label>University</label>
								<div class="input-detail">
									{!!$education->university?$education->university:'-'!!}
								</div>
							</div>
							<div class="form-group">
								<label>Passing Year</label>
								<div class="input-detail">
									{!!$education->passing_year?$education->passing_year:'-'!!}
								</div>
							</div>
							<div class="form-group">
								<label>Passing Year</label>
								<div class="input-detail">
									{!!$education->percentage?$education->percentage:'-'!!}
								</div>
							</div>
						</fieldset>
						@endforeach
					</div>
					@endif
					@if(count($experiences)>0)
					<div class="detail-group">
						@foreach($experiences as $expKey=>$experience)
						<fieldset>
							<legend>EXPERIENCE - {{$expKey+1}}</legend>
							<div class="form-group">
								<label>Company Name</label>
								<div class="input-detail">
									{!!$education->company_name?$education->company_name:'-'!!}
								</div>
							</div>
							<div class="form-group">
								<label>Experience From</label>
								<div class="input-detail">
									{!!$education->from?$education->from:'-'!!} To {!!$education->to?$education->to:'-'!!}
								</div>
							</div>
							<div class="form-group">
								<label>Salary</label>
								<div class="input-detail">
									{!!$education->salary?$education->salary:'-'!!}
								</div>
							</div>
							<div class="form-group">
								<label>Reason</label>
								<div class="input-detail">
									{!!$education->reson?$education->reson:'-'!!}
								</div>
							</div>
						</fieldset>
						@endforeach
					</div>
					@endif

					<div class="detail-group">
						<fieldset>
							<legend>Emploment</legend>
							<div class="form-group">
								<label>Pan Number</label>
								<div class="input-detail">
									{!!$people->pan_number?$people->pan_number:'-'!!}
								</div>
							</div>
							<div class="form-group">
								<label>Job Title</label>
								<div class="input-detail">
									{!!$people->designation?$people->designation->name:'-'!!}
								</div>
							</div>
							<div class="form-group">
								<label>Department</label>
								<div class="input-detail">
									{!!$people->department?$people->department->name:'-'!!}
								</div>
							</div>
							<div class="form-group">
								<label>Join Date</label>
								<div class="input-detail">
									{!!$people->join_date?$people->join_date:'-'!!}
								</div>
							</div>
							<div class="form-group">
								<label>Management Level</label>
								<div class="input-detail">
									{!!$people->management_level?$people->management_level:'-'!!}
								</div>
							</div>
						</fieldset>
					</div>
					<div class="detail-group">
						<fieldset>
							<legend>Social</legend>
							<div class="form-group">
								<label>Google</label>
								<div class="input-detail">
									{!!$people->google?$people->google:'-'!!}
								</div>
							</div>
							<div class="form-group">
								<label>Facebook</label>
								<div class="input-detail">
									{!!$people->facebook?$people->facebook:'-'!!}
								</div>
							</div>
							<div class="form-group">
								<label>Linkedin</label>
								<div class="input-detail">
									{!!$people->linkedin?$people->linkedin:'-'!!}
								</div>
							</div>
							<div class="form-group">
								<label>Skype</label>
								<div class="input-detail">
									{!!$people->skype?$people->skype:'-'!!}
								</div>
							</div>
							<div class="form-group">
								<label>Twitter</label>
								<div class="input-detail">
									{!!$people->twitter?$people->twitter:'-'!!}
								</div>
							</div>
						</fieldset>
					</div>
					<div class="detail-group">
						<fieldset>
							<legend>Projects</legend>
							<div class="form-group">
								<label>Current Projects</label>
								<div class="input-detail">
									<span class="currentp">{{count($people->user->projects->where('status','active'))}}</span>
								</div>
							</div>
							<div class="form-group">
								<label>Completed Projects</label>
								<div class="input-detail">
									<span class="completedp">{{count($people->user->projects->where('status','completed'))}}</span>
								</div>
							</div>
						</fieldset>
					</div>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection
<!-- START CONTAINER FLUID -->

  {{--   <div class="container-fluid container-fixed-lg">
	  <!-- START PANEL -->
	  <div class="panel panel-transparent">
		<div class="panel-heading">
		  <div class="panel-title">

			<div class="datas company-logo">
			  @if($people->photo == '')
			  <div class="pic"><img src="{!! url('img/noPhoto.png') !!}" /><span>{!! $people->fname !!}</span></div>
			  @else
			  <div class="pic"><img src="{!! url('uploads/people-thumb',$people->photo) !!}" /><span>{!! $people->name !!}</span></div>
			 @endif
			</div>

		  </div>
		  <div class="clearfix"></div>
		</div>
		<div ng-cloak class="panel-body" id="company_form">

		  <div class="row clerfix">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>Mobile: </b></label>
				   <div class="view_input">{!! $people->mobile or '-'!!}</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>Phone:</b> </label>
				   <div class="view_input">{!! $people->phone or '-' !!}</div>
				</div>
			</div>
		  </div>

		  <div class="row clerfix">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>Address:</b> </label>
				   <div class="view_input">{!! $people->adrs1 or '' !!}    {!! $people->adrs2 or ''!!}</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>City:</b> </label>
				   <div class="view_input">{!! $people->city or '-'!!}</div>
				</div>
			</div>
		  </div>

		  <div class="row clerfix">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>State:</b> </label>
				   <div class="view_input">{!! $people->state or '-'!!}</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>Country:</b> </label>
				   <div class="view_input">{!! $people->country or '-' !!}</div>
				</div>
			</div>
		  </div>

		  <div class="row clerfix">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>Zipcode: </b></label>
				   <div class="view_input">{!! $people->zipcode or '-' !!}</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>D.O.B:</b> </label>
				   <div class="view_input">{!! $people->dob or '-' !!}</div>
				</div>
			</div>
		  </div>

		  <div class="row clerfix">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>Gender: </b></label>
				   <div class="view_input">{!! $people->gender or '-' !!}</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>Marital status:</b> </label>

				   <div class="view_input">{!! $people->marital_status or '-' !!}</div>
				</div>
			</div>
		  </div>

		  <div class="row clerfix">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>Pan Number:</b> </label>
				   <div class="view_input">{!! $people->pan_number or '-' !!}</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>Department:</b> </label>
				   <div class="view_input">{!! $departments->name or '-'!!}</div>
				</div>
			</div>
		  </div>

		  <div class="row clerfix">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>Designation:</b> </label>
				   <div class="view_input">{!! $designations->name or '-' !!}</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>Management Leval: </b></label>
				   <div class="view_input">{!! $people->management_leval or '-'!!}</div>
				</div>
			</div>
		  </div>

		  <div class="row clerfix">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>Join Date:</b></label>
				   <div class="view_input">{!! $people->join_date or '-'!!}</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>Atach:</b> </label>
				   <div class="view_input">{!! $people->attach or '-' !!}</div>
				</div>
			</div>
		  </div>

		  <div class="row clerfix">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>Google: </b></label>

				   <div class="view_input">{{ $people->google or '-' }}</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>Facebook:</b> </label>
				   <div class="view_input">{!! $people->facebook or '-'!!}</div>
				</div>
			</div>
		  </div>

		  <div class="row clerfix">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>Website: </b></label>
				   <div class="view_input">{!! $people->website or '-'!!}</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>Skype: </b></label>
				   <div class="view_input">{!! $people->skype or '-' !!}</div>
				</div>
			</div>
		  </div>

		  <div class="row clerfix">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>Linkedin: </b></label>
				   <div class="view_input">{!! $people->linkedin or '-'!!}</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group">
				  <label><b>Twitter: </b></label>
				   <div class="view_input">{!! $people->twitter or '-' !!}</div>
				</div>
			</div>
		  </div>

		   <div class="row clerfix">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="input-group projects">
				  <label><b>Projects: </b></label>
				  @foreach($people->user->projects as $project)
				   <div class="view_input"><a href={!!url('/projects'),'/',$project->id,'/tasks'!!}>{!! $project->name or '-' !!}</a></div>
				  @endforeach
				</div>
			</div>
		  </div>

		</div>

	  </div>
	  <!-- END PANEL -->
	  <!-- END CONTAINER FLUID -->
	</div> --}}
