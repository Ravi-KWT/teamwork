@extends('layouts.app')
@section('title','Resource Avaibility')
@section('content')
<div  ng-controller="ResourceCtrl">
	<div class="page-user-log">
		@include('shared.user_login_detail')
	</div>
	<style type="text/css">
		#all-team-members-dt_wrapper th span:before{
			width: 0;
			border:0; 
		}
		#team-members-dt_wrapper th span:before{
			width: 0;
			border:0; 
		}
		.resource-list{
			background: #e8eaea;
			margin-bottom: 10px;
			padding-top: 10px;
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
		.multiselect-container>li>a>label>input[type=checkbox] {
		margin-bottom: 3px;
		border: 1px solid #000;
		opacity: 1;
		position: relative;
		margin-right: 5px;
		visibility: visible;
		}
		.multiselect-container>li.active a .checkbox {
		color: #ffffff;
		}
		table.dataTable.nowrap th:nth-child(4), table.dataTable.nowrap td:nth-child(4) {
		white-space: normal;
		overflow: hidden;
		max-width: 150px;
		word-break: keep-all;
		}
		table.dataTable.nowrap th:nth-child(5), table.dataTable.nowrap td:nth-child(5) {
		white-space: normal;
		overflow: hidden;
		max-width: 150px;
		word-break: keep-all;
		}
	</style>
	  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

	  {{-- master code --}}
	  		<div class="container-fluid">
	  			{{-- <ul class="breadcrumb">
	  				<li><a href="{!!url('/')!!}"><span><i class="fa fa-home"></i></span></a></li> --}}
	  				{{-- <li class="active"><span>Resource Avaibility</span></li> --}}
	  			{{-- </ul> --}}
	  			<div class="loader" ng-if="loading"></div>
	  			@include('shared.session')
	  			<div class="panel panel-transparent">
	  				<div class="panel-heading clearfix">
	  					<div class="panel-title">Resources Listing</div>
	  					{{-- @if(Auth::user()->is_teamlead == false)
	  					<div class="text-right">
	  						<label>Available</label>
	  						<div>
	  							<label class="switch">
	  							  <input type="checkbox" name="available" id="available" @if(Auth::user()->is_available	 == true) checked @endif>
	  							  <span class="slider round"></span>
	  							</label>
	  						</div>
	  					</div>
	  					@endif --}}
	  					@if(Auth::user()->is_teamlead == true && isset($teamlead))
	  						@if($workloads->count() <= 0 )
	  							<div class="action">
	  								<div class="cols">
	  									<button type='button' data-target="#addNewAppModal" data-toggle='modal' class="btn btn-md btn-default"><i class="fa fa-plus"></i> Add </button>
	  								</div>
	  							</div>
	  						@else
	  							<div class="action">
	  								<div class="cols">
	  									<button type='button' data-target="#addNewAppModal" data-toggle='modal' class="btn btn-md btn-default"><i class="fa fa-plus"></i> Edit </button>
	  								</div>
	  							</div>
	  						@endif
	  					@endif
	  				</div>
	  				<div class="panel-body">
	  					@if(Auth::user()->is_teamlead == true && isset($teamlead))
	  						@if($workloads->count() > 0)
	  							<table id="team-members-dt" class="hover nowrap" data-page-length="-1" ng-cloak>
	  								<thead>
	  									<tr>
	  										<th class="datatable-nosort">Date</th>
	  										<th class="datatable-nosort">Resource Name</th>
	  										<th class="datatable-nosort">Work Load</th>
	  										<th class="datatable-nosort">Projects</th>
	  										<th class="datatable-nosort">Others</th>
	  										<th class="datatable-nosort">On Leave</th>
	  									</tr>    
	  								</thead>
	  								<tbody ng-clock>
	  									@forelse($workloads as $workload)
	  										<tr ng-clock>
	  											<td ng-clock>{{$workload->date}}</td>
	  											<td ng-clock><a href="{{url('/people',$workload->user->people->id)}}" >{{$workload->user->people->name}}</a></td>
	  											<td ng-clock>
	  												<div class="row" >
	  													<div class="col-sm-6" ng-clock>
	  														<input type="text" class='employee-daily-workload' name="members-slider" data-id="{{$workload->id}}" 
	  														data-teamlead-id="{{$workload->teamlead_id}}" 
	  														data-member-id="{{$workload->member_id}}" 
	  														data-provide="slider" slider-min="1" data-slider-max="100" data-slider-step="1"  data-slider-tooltip="show" data-slider-value="{{$workload->work_load}}" >
	  													</div>
	  													<div class="col-sm-6">
	  														<span class="workload_label">{{$workload->work_load}} %</span>
	  													</div>
	  												</div>
	  											</td>
	  											<td ng-clock>{{ $workload->projects }}</td>
	  											<td ng-clock>{{ $workload->others }}</td>
	  											<td ng-clock>
	  		                                            {!! Former::select("on_leave","")->options([""=>'Select','on_leave'=>'On Leave','half_day'=>'Half Day'],$workload->on_leave)->id($workload->id)->class('change-leave-status form-control')!!}
	  											</td>
	  										</tr>
	  									@empty
	  									@endforelse
	  								</tbody>
	  							</table>
	  						@else
	  						<div class="row clearfix">
	  							<div class="col-md-12">
	  								<div style="text-align:center;">
	  									<img src="{!! asset('img/noMilestone1.png') !!}"  height="100px" width="100px" />
	  									<h3 ng-cloak>Please Add todays's workload for Team Members</h3>
	  								</div>
	  							</div>
	  						</div>
	  						<br>
	  						@endif
	  					@else

	  					@endif
	  					@if(Auth::user()->is_viewer == true || Auth::user()->is_teamlead == true)
	  						@if($todayWorkloads->count() > 0)
	  						
	  							@if(Auth::user()->is_teamlead == true)
	  								<br>
	  								<div class="well">
	  									<h3>Other Resources Availability</h3>
	  								</div>
	  							@endif
	  							
	  							<table id="all-team-members-dt" class="hover nowrap" data-page-length="-1" ng-cloak>
	  								<thead>
	  									<tr>
	  										<th class="datatable-nosort">Date</th>
	  										<th class="datatable-nosort">Resource Name</th>
	  										<th class="datatable-nosort">Team Lead</th>
	  										<th class="datatable-nosort">Work Load</th>
	  										<th class="datatable-nosort">Projects</th>
	  										<th class="datatable-nosort">Others</th>
	  										<th class="datatable-nosort">On Leave</th>
	  									</tr>    
	  								</thead>
	  							
	  								<tbody>
	  									@forelse($todayWorkloads as $workload)
	  										<tr>
	  											<td>{{$workload->date}}</td>
	  											<td><a href="{{url('/people',$workload->user->people->id)}}" >{{$workload->user->people->name}}</td>
	  											<td><a href="{{url('/people',$workload->teamlead->people->id)}}" >{{$workload->teamlead->people->name}}</a></td></td>
	  											<td>
	  												<div class="row">
	  													<div class="col-sm-6" ng-cloak>
	  														<input type="text" class='' name="members-slider" 
	  														data-provide="slider" slider-min="1" data-slider-max="100" data-slider-step="1"  data-slider-tooltip="show" 
	  														data-slider-value="{{$workload->work_load}}" 
	  														data-slider-enabled="false">
	  													</div>
	  													<div class="col-sm-6">
	  														<span class="workload_label">{{$workload->work_load}} %</span>
	  													</div>
	  												</div>
	  											</td>
	  											<td>{{ $workload->projects }}</td>
	  											<td>{{ $workload->others }}</td>
	  											<td>{{$workload->on_leave ?ucwords(str_replace('_', ' ', $workload->on_leave)):''}}</td>
	  										</tr>
	  									@empty
	  									
	  									@endforelse
	  								</tbody>
	  							</table>
	  						@else
	  							@if(Auth::user()->is_viewer == true && Auth::user()->is_teamlead == false)
	  								<div class="col-md-12">
	  									<div style="text-align:center;">
	  										<img src="{!! asset('img/noMilestone1.png') !!}"  height="100px" width="100px" />
	  										<h3 ng-cloak>No Data</h3>
	  									</div>
	  								</div>
	  							@endif

	  						@endif			
	  					@endif
	  				</div>
	  			</div>
	  		</div>
	  	@if(Auth::user()->is_teamlead == true)
	  		<div class="modal fade stick-up" id="addNewAppModal" tabindex="-1" role="dialog" aria-labelledby="addNewAppModal" aria-hidden="true">
	  			<div class="modal-dialog">
	  				<div class="modal-content">
	  					<div class="modal-header clearfix">
	  						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
	  						<h4>Resource Avaibility</h4>
	  					</div>
	  					<form  method="post" class='form' role='form' action="{{route('resources.store')}}" novalidate>
	  						<input type="hidden" name="_token" value="{!! csrf_token() !!}">
	  						<div class="modal-body">
	  							<div class="row">
	  								@forelse($resources as $key=>$res)
	  									@php
	  										$workLoads = \App\Resource::where('member_id',$res->member->id)->where('date', date('Y-m-d'))->first();
	  									@endphp
	  									<div class="col-sm-12 resource-list">
	  										<div class="detail-group">
	  											<div class="row col-md-12">
	  												<div class="col-md-6">
	  													<div class="slider-wrap mb_15">
	  														<label class="label"><span>{!!$res->member->people->name!!}</span></label>
	  														<input type="text" name="members[{{$res->member->id}}][slider_value]" data-provide="slider" slider-min="0" data-slider-max="100" data-slider-step="1" class='' data-slider-tooltip="show" data-slider-value="{{ $workLoads ? $workLoads->work_load : 0 }}" >
	  														<input type="hidden" name="teamlead_id" value="{{$res->teamlead_id}}">
	  													</div>
	  												</div>	
	  												<div class="col-md-6">
	  													<div class="form-group">
	  														<label class="label">On Leave</label>
	  														<select name="members[{{$res->member->id}}][on_leave]" class="form-control">
	  															<option value="">Select</option>
	  															<option value="on_leave" @if($workLoads && $workLoads->on_leave == "on_leave")selected @endif>On Leave</option>
	  															<option value="half_day" @if($workLoads && $workLoads->on_leave == "half_day")selected @endif>Half Day</option>
	  														</select>
	  					                                    
	  					                                </div>
	  												</div>
	  											</div>
	  											<div class=" row col-md-12">
	  												<div class="col-md-6 form-group">
	  													<label>Select Project</label>
	  													<select name="members[{{$res->member->id}}][projects][]" multiple class="form-control projects" >
	  														@foreach($projects as $project)
	  															<option value="{{ $project->name }}" @if($workLoads && (strpos($workLoads->projects, $project->name) !== false)) selected @endif>{{ $project->name }}</option>
	  														@endforeach
	  													</select>		                                    
	  		                                		</div>
	  		                                		<div class="col-md-6">
	  		                                			<label class="label">Others</label>
	  		                                			<input type="text" name="members[{{$res->member->id}}][others]" class="form-control" value="{{ $workLoads ? $workLoads->others : ''}}">
	  		                                		</div>
	  		                                	</div>
	  										</div>								
	  									</div>
	  								@empty
	  									<div style="text-align:center;">
	  										<img src="{!! asset('img/noPeople.png') !!}" />
	  									</div>
	  								@endforelse
	  							</div>
	  						</div>
	  						<div class="modal-footer">
	  							@if($resources->count()>0)
	  								<input  type="submit" class="btn btn-md btn-add" value="Save">
	  								<button type="button" data-dismiss="modal" class="btn btn-md btn-close">Close</button>
	  							@else
	  								<h3 align="center"> No Team Members added please contact to Admin</h3>
	  							@endif
	  						</div>
	  					</form>
	  				</div>
	  			</div>
	  		</div>
	  	@endif
	  	
	  	

  	{{-- @if(Auth::user()->is_teamlead == true)
  	<div class="container-fluid">
  		<div class="loader" ng-if="loading"></div>
  		<div class="panel panel-transparent">
  			<div class="panel-heading clearfix">
  				<div class="panel-title">Resources Availability</div>
  			</div>
  			<div class="panel-body">
  				<div class="col-md-12">
  					<div id="available-user">
  						@if($available_users->count() > 0)
  						<table class="table table-striped">
  							<thead>
  								<th>Id</th>
  								<th>Name</th>
  								<th>Department</th>
  							</thead>
  							<tbody>
  								@foreach($available_users as $user)
  								<tr>
  									<td>{!! $user->id !!}</td>
  									<td>{!! $user->people->fname !!}</td>
  									<td>{!! $user->people->department->name !!}</td>
  								</tr>
  								@endforeach
  							</tbody>
  						</table>
  						{!! $available_users->links() !!}
  						@else
  						<h3 align="center"> No Team Members added please contact to Admin</h3>
  						@endif
  					</div>
  				</div>
  			</div>
  		</div>
  	</div>
  	@endif --}}
	  

	  {{-- end master code --}}


	
@endsection
@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
{{-- <script src="https://js.pusher.com/3.1/pusher.min.js"></script> --}}
<script type="text/javascript">
	//instantiate a Pusher object with our Credential's key
	// var pusher = new Pusher('7e33696f1827248f3d59', {
 //       cluster: 'ap2',
 //       forceTLS: true
 //     });

	// var channel = pusher.subscribe('user-availability');
	// var user = {!! Auth::user() !!};
 //    channel.bind('App\\Events\\UserAvailability', function(data) {
 //    	if(user.roles == "admin" || (user.roles  == 'employee' && user.is_teamlead == true) || user.id == 35){
 //    		this.getAvailableUsers(data.status);
 //    	}
 //    });
    function getAvailableUsers(status){
    	var status = status;
    	$.get('{!! route("get-available-users") !!}',{status:status},function(response){
    		$('#available-user').html(response);
    	});
    }
	$(function() {
	    $('.projects').multiselect({
	      nonSelectedText: 'Select Project',
	      enableFiltering: true,
	      enableCaseInsensitiveFiltering: true,
	      maxHeight: 200
	     });
	});
	jQuery(document).ready(function($){

		$('.loader').hide();
		jQuery('#addNewAppModal').on('hidden.bs.modal', function(){
			jQuery(this).find('form')[0].reset();
		});   

		$(document).on('click','.btn-add',function(){
			$('.loader').show();
		});
		jQuery('input.default-slider-value').slider('setValue','0');			
		jQuery('.employee-daily-workload').on('change',function(e){
			var id = jQuery(this).attr('data-id');
			var work_load = jQuery(this).attr('value');
			console.log(jQuery(this).parents('td').find('span').html(work_load+' %'));
			var member_id = jQuery(this).attr('data-member-id');
			var teamlead_id = jQuery(this).attr('data-teamlead-id');
			e.preventDefault(); // does not go through with the link.
			jQuery.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
				}
			});
			url = "{{route('update-resource-workload')}}";
			jQuery.ajax({
				url: url,
				type: 'POST',
				data: {   
					id: id,
					work_load:work_load,
					member_id:member_id,
					teamlead_id:teamlead_id
				},
				success: function(data) {
					if(data.success == false){
						alert(data.msg);
						window.location.reload();
					}
				}
			});
		});

		jQuery('.change-leave-status').on('change',function(e){
			var id = jQuery(this).attr('id');
			var leave_status = jQuery(this).val();
			e.preventDefault();
			jQuery.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
				}
			});
			url = "{{route('change-leave-status')}}";
			jQuery.ajax({
				url: url,
				type: 'POST',
				data: {   
					id: id,
					leave_status:leave_status
				},
				success: function(data) {
					if(data.success == true){
						window.location.reload();
					}
				}
			});
		});

		
		
			// Setup - add a text input to each footer cell
	jQuery('#team-members-dt tfoot th').each( function () {
		var title = jQuery(this).text();
		if(title!='Action'){
			jQuery(this).html( '<input type="text" placeholder="Search '+title+'" />' );
		}
	} );
		// DataTable
		var table = jQuery('#team-members-dt').DataTable({
			"paging": false,
			columnDefs: [{
				targets: "datatable-nosort",
				orderable: false,
			}],

			"order": [[ 1, 'asc' ]]
		});
		// Apply the search
		// table.columns().every( function () {
		// 		var that = this;
		// 		jQuery( 'input', this.footer() ).on( 'keyup change', function () {
		// 			if ( that.search() !== this.value ) {
		// 				that
		// 				.search( this.value )
		// 				.draw();
		// 			}
		// 		} );
		// 	} );
			jQuery('#all-team-members-dt tfoot th').each( function () {
			// var title = jQuery(this).text();
			// if(title!='Action'){
			// 	jQuery(this).html( '<input type="text" placeholder="Search '+title+'" />' );
			// }
		} );
		// DataTable
		

		 var table = $('#all-team-members-dt').DataTable({
		        "columnDefs": [
		            { "visible": false, "targets": 2 },
	
					{
						targets: "datatable-nosort",
						orderable: false,
					}

		        ],
		         "order": [[ 2, 'asc' ],[ 1, 'asc' ]],
		        "paging": false,
		        "drawCallback": function ( settings ) {
		            var api = this.api();
		            var rows = api.rows( {page:'current'} ).nodes();
		            var last=null;
		 
		            api.column(2, {page:'current'} ).data().each( function ( group, i ) {
		                if ( last !== group ) {
		                    $(rows).eq( i ).before(
		                        '<tr class="group"><td colspan="6">Team Lead : '+group+'</td></tr>'
		                    );
		                    last = group;
		                }
		            } );
		        }
		    } );
		 
		    // Order by the grouping
		    $('#all-team-members-dt tbody').on( 'click', 'tr.group', function () {
		        var currentOrder = table.order()[0];
		        if ( currentOrder[0] === 2 && currentOrder[1] === 'asc' ) {
		            table.order( [ 2, 'desc' ] ).draw();
		        }
		        else {
		            table.order( [ 2, 'asc' ] ).draw();
		        }
		    } );
		    //user availability
		    $(document).on('click','#available',function(e){
		    	var status = $(this).is(':checked');
		    	$.get('{!! route('user-availability') !!}',{status:status},function(response){
		    		// console.log(response);
		    	});
		    	
		    });

	});
</script>
@endsection