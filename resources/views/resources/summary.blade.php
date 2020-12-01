@extends('layouts.app')
@section('title','Resource Avaibility')
@section('content')
<div ng-controller="ResourceCtrl" >
	<div class="page-user-log">
		@include('shared.user_login_detail')
	</div>
	<style type="text/css">
		#team-members-dt_wrapper{
			margin-top: 30px;
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
	</style>
	 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
	<div class="container-fluid">
		@include('shared.session')
		<div class="panel panel-transparent">
			<div class="panel-heading clearfix">
				<div class="panel-title">Resources Listing</div>
			</div>
			<div class="panel-body">
				     @include('shared.session')  
				    
                  <div class="filtter clearfix">
                    <div class="container-fluid">
                        <div class="row" >
                            <form name='searchEverythingTask' action="{!!route('filter-resource-availability')!!}" method="get" class='form' role='form'>
                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                    <div class="form-inline">
                                       @if(Auth::user()->roles=='admin')
                                        <div class="form-group" ng-cloak>
                                            <label class="label"><span>Users</span></label>
                                             {!! Former::select("user_id","")->options($users )!!}
                                        </div>
                                        @endif
                                        <div class="form-group">
                                            <div class="form-group" >
                                                <label class="label"><span>Date Range</span></label>
                                                <div class="input-group datepicker"  date-set=
                                                @if(isset($start_date))
                                                   {!! $start_date !!}
                                                @else
                                                    {!!Carbon\Carbon::now()->subdays(2)!!}
                                                @endif
                                                date-format="dd-MM-yyyy" 
                                                    selector="form-control">
                                                    <input type="text" name="start_date" class="form-control" placeholder="Pick a start date" id="searchForm-start-date">
                                                    <label class="input-group-addon" for="searchForm-start-date">
                                                        <i class="fa fa-calendar"></i>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                               <i class="fa fa-arrows-h"></i>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group datepicker" date-set=
                                                @if(isset($end_date))
                                                   {!! $end_date or ''!!}
                                                @else
                                                    {!!Carbon\Carbon::now()!!}
                                                @endif date-format="dd-MM-yyyy"  selector="form-control">
                                                    <input type="text"  name="end_date" class="form-control" placeholder="Pick a end date" id="searchForm-end-date">
                                                    <label class="input-group-addon" for="searchForm-end-date">
                                                        <i class="fa fa-calendar"></i>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="label"><span>TeamLead</span></label>
                                            {!! Former::select("teamlead_id","")->options($teamleads)!!}
                                        </div>
                                      

                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <ul class="list-inline">
                                        <li>
                                        <div class="form-group">
                                            <label class="label">&nbsp;</label>
                                            <input type="submit" name='filter' value="Filter" class="btn btn-md btn-default">
                                        </div>
                                        </li>
                                       
	                                        <li>
	                                            <div class="form-group">
	                                                <label class="label">&nbsp;</label>
	                                                <div class="dropdown drop-arrow rightside padd">
	                                                    <button class="btn btn-md btn-default" type="button" id="export" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
	                                                          Export
	                                                         <span class="caret"></span>
	                                                    </button>
	                                                    <ul class="dropdown-menu" aria-labelledby="export">
	                                                        <li>
	                                                            <button  class="btn-block btn btn-sm btn-default" type="submit" name="excel" value="Excel">Excel</button>
	                                                        </li>
	                                                    </ul>
	                                                </div>
	                                            </div>
	                                        </li>
                                        
                                    </ul>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

				 @if($workloads->count() > 0)
					<table id="team-members-dt" class="table vc" ng-cloak>
						<thead>
							<tr>
								<th>Date</th>
								<th>Resource Name</th>
								<th>TeamLead</th>
								<th>Work Load</th>
								<th>Projects</th>
								<th>Others</th>
								<th>On Leave</th>
							</tr>    
						</thead>
						{{-- <tfoot>
							<tr>
								<th>Date</th>
								<th>Resource Name</th>
								<th>TeamLead</th>
								<th>Work Load</th>
							</tr>
						</tfoot> --}}
						<tbody>
							@forelse($workloads as $workload)
								<tr>
									<td>{{$workload->date}}</td>
									<td><a href="{{url('/people',$workload->user->people->id)}}"> {{$workload->user->people->name}}</a></td>
									<td ng-cloak><a href="{{url('/people',$workload->teamlead->people->id)}}" style="text-transform: uppercase;">{{$workload->teamlead->people->name}}</a></td>
									<td>
										<div class="row" >
											<div class="col-sm-6" ng-cloak>
												<input type="text" class='employee-daily-workload' name="members-slider" data-id="{{$workload->id}}" 
												data-teamlead-id="{{$workload->teamlead_id}}" 
												data-member-id="{{$workload->member_id}}" 
												data-provide="slider" slider-min="0" data-slider-max="100" data-slider-step="1"  data-slider-tooltip="show" data-slider-value="{{$workload->work_load}}" data-slider-enabled="false" >
											</div>
											<div class="col-sm-6" >
												<span class="workload_label">{{$workload->work_load}} %</span>
											</div>
										</div>
									</td>
									<td style="width: 150px; word-break: keep-all;">{{ $workload->projects }}</td>
									<td style="width: 150px; word-break: keep-all;">{{ $workload->others }}</td>
									<td>{{$workload->on_leave ?ucwords(str_replace('_', ' ', $workload->on_leave)):''}}</td>
								</tr>
							@empty

							@endforelse
						</tbody>
					</table>
				@else
				<br>&nbsp;<br>
					<div class="col-md-12">
						<div style="text-align:center;">
							<img src="{!! asset('img/noMilestone1.png') !!}"  height="100px" width="100px" />
							<h3 ng-cloak>No Record Found</h3>
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>
	
		{{-- <div class="container-fluid">
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
								<div class="col-md-12">
									<div style="text-align:center;">
										<img src="{!! asset('img/noMilestone1.png') !!}"  height="100px" width="100px" />
										<h3 ng-cloak>No Record Found</h3>
									</div>
								</div>
							@endif
							</div>
					</div>
				</div>				
			</div>
		</div> --}}
</div>
@endsection
@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<script src="https://js.pusher.com/3.1/pusher.min.js"></script>
<script type="text/javascript">
	$(function() {
	    $('.projects').multiselect({
	      nonSelectedText: 'Select Project',
	      enableFiltering: true,
	      enableCaseInsensitiveFiltering: true,
	      
	     });
	});
	var pusher = new Pusher('7e33696f1827248f3d59', {
		cluster: 'ap2',
		forceTLS: true
	});

	var channel = pusher.subscribe('user-availability');
	var user = {!! Auth::user() !!};
	channel.bind('App\\Events\\UserAvailability', function(data) {
		if(user.roles == "admin" || (user.roles  == 'employee' && user.is_teamlead == true)){
			this.getAvailableUsers(data.status);
		}
	});
	function getAvailableUsers(status){
		var status = status;
		$.get('{!! route("get-available-users") !!}',{status:status},function(response){
			$('#available-user').html(response);
		});
	}
	jQuery(document).ready(function($){
		jQuery('#team-members-dt tfoot th').each( function () {
			var title = jQuery(this).text();
			if(title!='Action'){
				jQuery(this).html( '<input type="text" placeholder="Search '+title+'" />' );
			}
		} );

		var table = $('#team-members-dt').DataTable({
			"columnDefs": [
			{ "visible": false, "targets": 2 },
			{ "orderable": false, "targets": 1 },
			{ "orderable": false, "targets": 0 },
			{ "orderable": false, "targets": 3 },
			{ "orderable": false, "targets": 4 },
			],
			"order": [[ 2, 'asc' ],[ 0, 'desc' ],[ 1, 'asc' ]],
			"displayLength": 100,
			"lengthMenu": [[25, 50, 75,100, -1], [25, 50, 75,100, "All"]],
			"paging": true,
			"drawCallback": function ( settings ) {
				var api = this.api();
				var rows = api.rows( {page:'current'} ).nodes();
				var last=null;
				
				api.column(2,{page:'current'} ).data().each( function ( group, i ) {

					if ( last !== group ) {

						$(rows).eq( i ).before(
							'<tr class="group"><td colspan="5">Team lead : '+group+'</td></tr>'
							);
						last = group;
					}
				} );
			}
		} );
		
		    // Order by the grouping
		    $('#team-members-dt tbody').on( 'click', 'tr.group', function () {
		    	var currentOrder = table.order()[0];
		    	if ( currentOrder[0] === 2 && currentOrder[1] === 'asc' ) {
		    		table.order( [ 2, 'desc' ] ).draw();
		    	}
		    	else {
		    		table.order( [ 2, 'asc' ] ).draw();
		    	}
		    } );

		});
</script>
@endsection