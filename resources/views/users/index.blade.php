@extends('layouts.app')
@section('title','User Permission')
@section('content')

	<div class="page-user-log">
		@include('shared.user_login_detail')
	</div>
	<div class="container-fluid">
		@include('shared.session')
		<div class="panel panel-transparent">
			<div class="panel-heading clearfix">
				<div class="panel-title">USER Permission</div>
				
			</div>
			<div class="panel-body">
				@if(Auth::user()->roles == 'admin')
					<table id="user-permissions" class="hover nowrap" data-page-length="10">
						<thead>
							<tr>
								<th>Name</th>

								<th class="datatable-nosort">Roles</th>
								<th class="datatable-nosort">Teamlead</th>
								<th class="datatable-nosort">Viewer</th>
								<th class="datatable-nosort">Status</th>
							</tr>    
						</thead>
						<tbody >
							@forelse($users as $key=>$user)

								<tr >
									<td>
										{{$user->people->fname}}{{$user->people->lname?" ".$user->people->lname:''}} 
									</td>
									<td>
										<select name="roles" class="form-control change-permission">
											<option value="admin" {{$user->roles == 'admin'?'selected="selected"':''}}>Admin</option>
											<option {{$user->roles == 'employee'?'selected="selected"':''}} value="employee">Employee</option>
										</select>
									</td>
									<td>	
										<select name="is_teamlead" class="form-control change-permission">
											<option {{$user->is_teamlead == true?'selected="selected"':''}} value="true" >Yes</option>
											
											<option {{$user->is_teamlead == false?'selected="selected"':''}} value="false">No</option>
										</select>
									</td>
									<td>
										<select name="is_viewer" class="form-control change-permission">
											<option {{$user->is_viewer == true?'selected="selected"':''}} value="true" >Yes</option>
											<option {{$user->is_viewer == false?'selected="selected"':''}} value="false">No</option>
										</select>
										{!! Former::hidden('id')->value($user->id)->class('change-permission')->id('user_id')!!}
									</td>
										<td>
										<select name="active" class="form-control change-permission" id='status-{{$user->id}}'>
											<option {{$user->active == true?'selected="selected"':''}} value="true" >Active</option>
											<option {{$user->active == false?'selected="selected"':''}} value="false">Inactive</option>
											<option {{$user->suspend == true?'selected="selected"':''}} value="false">Suspend</option>
										</select>
										{!! Former::hidden('id')->value($user->id)->class('change-permission')->id('user_id')!!}
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
								<img src="{!! asset('img/non_billable.png') !!}"  height="100px" width="100px" />
								<br>&nbsp;
								<h3 >You don't have rights to access this area!!!!!</h3>
							</div>
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>
@endsection
@section('scripts')
<script type="text/javascript">
	jQuery(document).ready(function($){
		jQuery('.change-permission').on('change',function(e){
			e.preventDefault();
			var pdata =[];

			var id = $(this).parents('tr').find('#user_id').val();
			pdata.push(id);


			$(this).parents('tr').find('.change-permission').each(function(index,value){
				pdata.push(value.value);
			});
			var status= $(this).parents('tr').find("#status-"+id+' option:selected').text();
			pdata.push(status);

			jQuery.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
				}
			});

			url = "{{route('change-permission')}}";
			jQuery.ajax({
				url: url,
				type: 'POST',
				data: {pdata},
				success: function(data) {
					console.log(data);
					if(data.success == true){
						alert('Updated Successfully')
						window.location.reload();
					}
				}
			});
			
		});
	
		// DataTable
		jQuery('#user-permissions').DataTable({
			"displayLength": 100,
		    "lengthMenu": [[10,20, 50, 75,100, -1], [10,20, 50, 75,100, "All"]],
			"paging": false,
				columnDefs: [{
					targets: "datatable-nosort",
					orderable: false,
				}],

			"order": [[ 0, 'asc' ]],
		});
	});
</script>
@endsection