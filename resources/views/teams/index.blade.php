@extends('layouts.app')
@section('title','Team')
@section('content')
<div class="page-user-log">
	@include('shared.user_login_detail')
</div>

{{-- @if(Auth::user()->roles=='admin') --}}
<div class="container-fluid">
	<ul class="breadcrumb" ng-cloak>
		<li><a href="{!!url('/')!!}"><span><i class="fa fa-home"></i></span></a></li>

		<li class="active"><span>Teams</span></li>
	</ul>
	<div class="panel panel-transparent">
		<div class="panel-heading clearfix">
			<div class="panel-title">Team Listing</div>
			<div class="action">
				{{-- @if(Auth::user()->roles == "admin") --}}
				<div class="cols">
					<button type='button' data-target="#team-members-modal" data-toggle='modal' class="btn btn-md btn-default"><i class="fa fa-plus"></i> Add Team</button>
				</div>
				{{-- @endif --}}
			</div>
		</div>
		<div class="panel-body">
			<div class="loader" ng-if="loading"></div>
			<table class="hover nowrap team-members-box" style="" id="team-members-dt" data-page-length="-1">
				<thead>
					<tr class="heading-bg">
						<th>Department/Team</th>
						{{-- <th>Team Lead</th> --}}
						<th>Member Name</th>
						<th>Action</th>
					</tr>
				</thead>
				{{-- <tfoot>
					<tr class="heading-bg">
						<th>Department</th>
						<th>Team Lead</th>
						<th>Member Name</th>
						<th>Action</th>
					</tr>
				</tfoot> --}}
				<tbody>
					@forelse($departmentsList as $team)
						<tr>
							<td>{{($team->name)}}</td>
							{{-- <td>{{$team->teamHead->teamlead->people->name}}</td> --}}
							<td>
								@foreach($team->teamMembers as $key=>$tm)
									@if($tm->member->is_teamlead==true)
										<a href="{{url('/people',$tm->member->people->id)}}"><span class='btn btn-success'>{{$tm->member->people->name}}</span></a>
									@endif
								@endforeach
								@php
									$cnt = 0;
								@endphp
								@foreach($team->teamMembers as $key=>$tm)
									@if($tm->member->is_teamlead==false)
									<a href="{{url('/people',$tm->member->people->id)}}"><span class='btn btn-warning'>{{$tm->member->people->name}}</span></a>
										
										@if($cnt%3==1)
											<br>
										@endif
										@php
									$cnt++
									@endphp
									@endif
								@endforeach
							</td>
							<td>
								<div class="actions choose-actions">
									<a  data-toggle="modal" class="btn-detail edit btn btn-md btn_edit" data-target="#team-members-modal" data-url="{!! route('team-members.edit',[$team->id]) !!}"><i class="fa fa-pencil"></i></a>
									<a class="btn btn-md btn_delete" data-url="{!! route('team-members.destroy',[$team->id]) !!}" data-method="delete"><i class="fa fa-trash"></i></a>
								</div>
							</td>
						</tr> 
					@empty
					
					@endforelse 
				</tbody>
			</table>
		</div>
	</div>

</div>
<div class="modal fade stick-up" id="team-members-modal" tabindex="-1" role="dialog" aria-labelledby="team-members-modal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header clearfix">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
				<h4 class="team-modal-title">Add Team</h4>
			</div>
			{!!Former::Framework('Nude')!!}
			{!! Former::open()->action(URL::route("team-members.store") )->method('post')->enctype("multipart/form-data")->role('form')->id('demo-form2') !!}
			{{csrf_field()}}
			<div class="modal-body clearfix"> 
				<div class="col-lg-12">
					<div class="form-group">
						<label>Department/Team<em>*</em></label>
						{!!Former::select('department_id')->options([''=>'Select Department']+$departments)->class('form-control  selectpicker')->id('department_id')!!}
						<span class="department_id_error has-error"></span>
					</div>
				</div>
				<div class="col-lg-12">
					<div class="form-group">
						<label>TeamLead<em>*</em></label>
						{!!Former::select('teamlead_id')->options([''=>'Select Team Lead']+$teamLeads)->class('form-control  selectpicker')->id('teamlead_id')!!}
						<span class="teamlead_id_error has-error"></span>
					</div>
				</div>
				<div class="col-lg-12">
					<div class="form-group">
						<label>Members</label>
						{!!Former::select('member_id[]')->options($members1)->class('form-control  selectpicker')->setAttribute('multiple','multiple')->id('member_id')!!}
						<span class="member_id_error has-error"></span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-md btn-add btn-crud" >Save</button>   
				{{-- <input type="hidden" id="employee_lwp_id" name="employee_lwp_id" value='0'> --}}
				<button type="button" data-dismiss="modal" class="btn btn-md btn-close" id="close" >Close</button>
			</div>
			{!!Former::close()!!}    
		</div>
	</div>
</div>
{{-- @else
<div class="container-fluid">
	<h1 style="color: red;">You don't have permission to access this area</h1>
</div>
@endif --}}
@endsection
@section('scripts')
<script type="text/javascript" src='{{asset('js/team.js')}}'></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.selectpicker').selectpicker({
			style: 'btn-info',
			size: 4
		});
		$("#team-members-modal").on('hidden.bs.modal', function () {
			$(this).find('form')[0].reset();
		 // jQuery('.selectpicker').selectpicker('render');    
		 $('.modal-backdrop').hide();
		});
		
	// Setup - add a text input to each footer cell

		// DataTable
		var table = $('#team-members-dt').DataTable({
			"displayLength": 100,
			 "search": {
			    "caseInsensitive": false
			  },
		    "lengthMenu": [[25, 50, 75,100, -1], [25, 50, 75,100, "All"]],
		         "paging": true,
			"columnDefs": [
				{ "width": "30%", "targets": 2 },
				{ "orderable": false, "targets": 3 }],
			

		});
	
	});
</script>
@endsection
@section('styles')
<style type="text/css">
	.has-error{
		color: red;
	}
</style>
@endsection