@extends('layouts.app')
@section('title','Projects')
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
		div.dt-buttons{
			margin-left: 15px;
		}
		div.dt-buttons .dt-button{
			padding: 5px 10px;
		}	
	</style>
	<ul class="nav nav-tabs clearfix">
        <li ><a href="{{url('projects')}}">Current Projects</a></li>
        <li ><a href="{!! route('projects-list',['archive'])!!}">Archived Projects</a></li>
        <li class="active"><a href="{!! route('projects-list',['completed']) !!}">Completed Projects</a></li>
         <li><a href="{!! route('projects-list',['onhold']) !!}">On Hold Projects</a></li>
    </ul>

	<div class="container-fluid">
		@include('shared.session')
		<div class="panel panel-transparent">
			<div class="panel-heading clearfix">
				<div class="panel-title">Projects Listing</div>
			</div>
			<div class="panel-body">
				@include('shared.session')  
				{{--   <div class="filtter clearfix">
					<div class="container-fluid">
						<div class="row" >
							<form name='searchEverythingTask' action="" method="get" class='form' role='form'>
								<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
									<div class="form-inline">
									   @if(Auth::user()->roles=='admin')
										<div class="form-group">
											<label class="label"><span>Status</span></label>
											{!! Former::select("status","")->options(['active'=>'active','onhold'=>'onhold','completed'=>'completed','archive'=>'archive'])!!}
										</div>
									  @endif
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
										{<li>
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
				</div> --}}
				<table id="team-members-dt" class="table vc">
						<thead>
							<tr>
								<th class="datatable-nosort">Name</th>
								<th class="datatable-nosort">Client</th>
								<th class="datatable-nosort">Status</th>
								<th class="datatable-nosort">Action</th>
							</tr>    
						</thead>
						<tbody>
							@forelse($projects as $project)
								<tr>
									<td><a href="{{url('projects/'.$project->id.'/tasks')}}">{{$project->name}}</a></td>
									<td>{{$project->company->name}}</td>
									<td>{{$project->status}}</td>
									<td>{!! Former::select("status",'')->options([""=>'Change Status','active'=>'Active','onhold'=>'On Hold','archive'=>'Archived'])->id($project->id)->class('change-project-status form-control')!!}</td>
								</tr>
							@empty

							@endforelse
						</tbody>
					</table>
			</div>
		</div>
	</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		var table = $('#team-members-dt').DataTable({
		        "columnDefs": [
		            { "visible": false, "targets": 1 },
	
					{
						targets: "datatable-nosort",
						orderable: false,
					}

		        ],
		        "processing": true,
			    "dom": 'lBfrtip',
				"buttons": [
		            {
		                extend: 'collection',
		                text: ' Export',
		                buttons: [
		                    'copy',
		                    'excel',
		                    'csv',
		                    'pdf',
		                    'print'
		                ]
		            }
			    ],
		        "order": [[ 1, 'asc' ],[ 0, 'asc' ]],
		        "paging": true,
		        "drawCallback": function ( settings ) {
		            var api = this.api();
		            var rows = api.rows( {page:'current'} ).nodes();
		            var last=null;
		 
		            api.column(1, {page:'current'} ).data().each( function ( group, i ) {
		                if ( last !== group ) {
		                    $(rows).eq( i ).before(
		                        '<tr class="group"><td colspan="5">Client : '+group+'</td></tr>'
		                    );
		                    last = group;
		                }
		            } );
		        }
		    } );
		
	
		$(document).on('change','.change-project-status',function(e){
			var id = $(this).attr('id');
			var status = $(this).val();
			e.preventDefault();
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			url = "{{route('change-project-status')}}";
			$.ajax({
				url: url,
				type: 'POST',
				data: {   
					id: id,
					status:status
				},
				success: function(data) {
					if(data.success == true){
						alert('Status changed successfully ');
						window.location.reload();
					}
				}
			});
		});
	});
</script>
@endsection