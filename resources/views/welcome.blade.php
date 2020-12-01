@extends('layouts.app')
@section('title','Dashboard')
@section('content')
@include('shared.session')
<div class="page-user-log ">
	@include('shared.user_login_detail')
</div>
<div class="home_page">
	<div class="container-fluid">
		<div class="employee_project_clients mb_30">
			<div class="row">
			@if(Auth::user()->roles == 'admin')
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<div class="our-employee box">
						<span class="icons"><img src="{!!asset('img/employe-icon.png')!!}" alt=""></span>
						<span class="count employee">{{DB::table('users')->where('id','!=', 0)->count()}}</span>
						<span class="title"><a class="employee" href='{!! url('people') !!}' >Our People</a></span>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<div class="our-projects box">
						<span class="icons"><img src="{!!asset('img/projects-icon.png')!!}" alt=""></span>
						@if(Auth::user()->roles =='admin')
						<span class="count projects">{!!DB::table('projects')->where('status','active')->count()!!}</span>
						@else
							<span class="count projects">{{count(Auth::user()->projects->where('status','active'))}}</span>
						@endif
						<span class="title"><a class="projects" href='{!! url('projects') !!}' >Current Projects</a></span>
					</div>
				</div>

					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
						<div class="our-clients box">
							<span class="icons"><img src="{!!asset('img/client-icon.png')!!}" alt=""></span>
							<span class="count clients">{!!DB::table('companies')->count()!!}</span>
							<span class="title"><a class="clients" href='{!!url('companies') !!}'>Our Clients</a></span>
						</div>
					</div>
				@else
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div class="our-employee box">
							<span class="icons"><img src="{!!asset('img/employe-icon.png')!!}" alt=""></span>
							<span class="count employee">{{DB::table('users')->where('id','!=', 0)->count()}}</span>
							<span class="title"><a class="employee" href='{!! url('people') !!}' >Our People</a></span>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div class="our-projects box">
							<span class="icons"><img src="{!!asset('img/projects-icon.png')!!}" alt=""></span>
							@if(Auth::user()->roles =='admin')
							<span class="count projects">{!!DB::table('projects')->where('status','active')->count()!!}</span>
							@else
								<span class="count projects">{{count(Auth::user()->projects->where('status','active'))}}</span>
							@endif
							<span class="title"><a class="projects" href='{!! url('projects') !!}' >Current Projects</a></span>
						</div>
					</div>
				@endif
			</div>
		</div>
		<div class="latest-task">
			<div class="panel panel-transparent">
				<div class="panel-heading clearfix">
					<div class="panel-title">Latest Task</div>
				</div>
				<div class="panel-body">
					@if($tasks->count()>0)
					<table id="user-log-dt" data-paging-type='simple_numbers' ng-cloak cellspacing="0" cellpadding="0" class="user-log-dt" style="width:100%">
						<thead>
							<tr>
								<th>Date</th>
								<th class="text-left">Name</th>
								<th>Project</th>
								<th>Assign To</th>
							</tr>
						</thead>
						{{-- <tfoot>
							<tr>
								<th>Date</th>
								<th>Name</th>
								<th>Project</th>
								<th>Assign To</th>
							</tr>
						</tfoot> --}}
						<tbody>
							@foreach($tasks as $task)
							<tr>
								<td>
									@if($task->created_at)
									{!! $task->created_at->format('d/m/Y') !!}
									@endif
								</td>
								<td class="text-left">
									{{-- <a href="{!!url('/projects'),'/',$task->project->id,'/tasks/',$task->id!!}"> {!!$task->name!!}</a> --}}
									{!!$task->name!!}
								</td>
								<td>
									{{-- <a href="{!!url('/projects'),'/',$task->project->id,'/tasks/'!!}"> {!!$task->project->name or 'hello'!!}</a> --}}
									{!!$task->project->name or ''!!}
								</td>
								<td>
									@forelse($task->users as $user)
									@if($user->id == 0)
									Anyone
									@else
									<a href="{{url('/people',$user->people->id)}}"> {!!$user->people->name or '-'!!}{!! count($task->users) > 1 ? ',' : '' !!}</a>
									@endif
									@empty
									Anyone
									@endforelse
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					@else
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div style="text-align:center;">
							<img src="{!! asset('img/noTasks.png') !!}"  height="100px" width="100px" />
							<p><h3>No Records</h3></p>
						</div>
					</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>


{{-- <a data-fancybox="gallery" href="{{asset('img/birthday.gif')}}"><img src="{{asset('img/birthday.gif')}}"></a> --}}
@endsection
@section('scripts')
<script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.16/sorting/date-uk.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
	<script type="text/javascript">
	$(document).ready(function(){
	
		$('.user-log-dt').DataTable({
			responsive: true,
          	columnDefs: [
		       { type: 'date-uk', targets: 0 }
		    ],
		    oLanguage: {
		        oPaginate: {
			        sNext: '<span class="pagination-fa"><i class="fa fa-chevron-right" ></i></span>',
			        sPrevious: '<span class="pagination-fa"><i class="fa fa-chevron-left" ></i></span>'
			    }
    		}
		});
		// $('#user-log-dt tfoot th').each( function () {
		// 	var title = $(this).text();
		// 	if(title!='Action'){
		// 		$(this).html( '<input type="text" placeholder="Search '+title+'" />' );
		// 	}
		// } );
		// 	// DataTable
		// 	var table = $('#user-log-dt').DataTable({
		// 		responsive: true,
  //             	columnDefs: [
		// 	       { type: 'date-uk', targets: 0 }
		// 	    ]
		// 	});
		// 	// Apply the search
		// 	table.columns().every( function () {
		// 		var that = this;
		// 		$( 'input', this.footer() ).on( 'keyup change', function () {
		// 			if ( that.search() !== this.value ) {
		// 				that
		// 				.search( this.value )
		// 				.draw();
		// 			}
		// 		} );
		// 	} );
		});
</script>
@endsection