<div class="header">
	<nav class="navbar">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="{!! url('/') !!}">
					<img src="{!! asset('img/logo.png')!!}" alt="logo" data-src="{!! asset('img/logo.png')!!}" data-src-retina="{!! asset('img/logo_2x.png')!!}">
				</a>
			</div>
			<div class="header-profile">
				<ul class="list-inline">
					<li>
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu" aria-expanded="false">
						<i class="fa fa-align-justify"></i>
						</button>
					</li>
					<li class="dropdown drop-arrow rightside">
						<button class="btn btn-md btn-trans" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						@if(Auth::check())
						<span class="name">
                            @if(Auth::user()->people)
                                @if(Auth::user()->people->fname==null)
    							     {!! Auth::user()->email!!}
    							@else
    							     {!! ucwords( Auth::user()->people->fname." ".Auth::user()->people->lname)!!}
    							@endif
                            @else
                                {!! Auth::user()->email!!}
                            @endif
						</span>
						@endif
						@if(Auth::check())
						<div class="avtar inline">
                            @if(Auth::user()->people)
    							@if(Auth::user()->people->photo)
        							<div class='img avatar-xs'>
        								<img src="{!!Auth::user()->people->photo_url('thumb')!!}">
        							</div>
    							@else
        							<div class='img avatar-xm'>
        								<img src="/img/user.png">
        							</div>
    							@endif
                            @else
                                <div class='img avatar-xm'>
                                    <img src="/img/user.png">
                                </div>
                            @endif
						</div>
						@endif
						</button>
						<ul class="dropdown-menu" role="menu">
							{{-- <li><a href="{!! url('/people',Auth::user()->people->id)!!}">Profile</a></li> --}}
							<li><a href="{!! url('/change-password')!!}">Change Password</a></li>
							<li><a href="{!! url('change-profile') !!}">Change Profile</a></li>
							<li><a href="{!! url('theme') !!}">Theme</a></li>
							<li class="bg-master-lighter">
								<a href="javascript:;" onclick="document.getElementById('logout-form').submit();">Logout</a>
							</li>
						        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
						            @csrf
						            <input type="hidden" name="fcm_token" value="" id="fcm_token">
						        </form>
						</ul>
					</li>
					<li>
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#leftmenu" aria-expanded="false">
						<i class="fa fa-align-justify"></i>
						</button>
					</li>
				</ul>
			</div>
			<div class="collapse navbar-collapse" id="menu">
				<ul class="navbar-nav">
					<li class="{{ Route::currentRouteNamed('home') ? 'active' : '' }}"><a href="{!! url('/') !!}">Home</a></li>
                    @if(Auth::user()->roles=='admin')
    					<li class="{{ Route::currentRouteNamed('companies') ? 'active' : '' }} dropdown drop-arrow">
    						<a href="{!! url('companies') !!}" class='company active' data-id='company'>Company
    						</a>
    						@if(Auth::user()->roles == 'admin')
    						<div class="togg" data-toggle="dropdown" id="drop_1">
    							<span class="caret"></span>
    						</div>
    						<ul class="dropdown-menu" aria-labelledby="drop_1">
    							<li class="{{ Route::currentRouteNamed('industries') ? 'active' : '' }}">
    								<a href="{!! url('industries') !!}" class="industry"    data-id='industry'>Industry</a>
    							</li>
    						</ul>
    						@endif
    					</li>
                    @endif
				
					<li class="@if(Route::currentRouteNamed('projects'))
									active
								@elseif(Route::currentRouteNamed('projects-list'))
									active
								@elseif(Route::currentRouteNamed('search-projects'))
									active
								@else
									''
								@endif dropdown drop-arrow">
						<a href="{!! url('projects') !!}" class='project'  data-id='project'>Projects
						</a>
						@if(Auth::user()->roles == 'admin')
						<div class="togg" data-toggle="dropdown" id="drop_3">
							<span class="caret"></span>
						</div>
						<ul class="dropdown-menu">
							<li class="{{ Route::currentRouteNamed('project-categories') ? 'active' : '' }}" >
								<a href="{!! url('project-categories') !!}" class='project_cat' data-id='project_cat'>Project Category
								</a>
							</li>
							<li class="{{ Route::currentRouteNamed('task-categories') ? 'active' : '' }}">
								<a href="{!! url('task-categories') !!}" class='task_cat' data-id='task_cat'>Task Category
								</a>
							</li>
						</ul>
						@endif
					</li>
                    <li class="{{ Route::currentRouteNamed('everything') ? 'active' : '' }}"><a href="{!! url('everything') !!}" class='everything' data-id='everything'>Everything</a></li>
              
                    <li class="{{ Route::currentRouteNamed('people') ? 'active' : '' }} dropdown drop-arrow">
                        <a href="{!! url('people') !!}" class='people'   data-id='people'>People
                        </a>
                        @if(Auth::user()->roles == 'admin')
                        <div class="togg" data-toggle="dropdown" id="drop_2">
                            <span class="caret"></span>
                        </div>
                        <ul class="dropdown-menu" aria-labelledby="drop_2">
                            <li class="{{ Route::currentRouteNamed('designations') ? 'active' : '' }}">
                                <a href="{!! url('designations') !!}" class='designation'     data-id='designation'>Designation</a>
                            </li>
                            <li class="{{ Route::currentRouteNamed('department') ? 'active' : '' }}">
                                <a href="{!! url('departments') !!}" class='department' data-id='department' >Department</a>
                            </li>
                        </ul>
                        @endif
                    </li>
                   	 <li class="{{ Route::currentRouteNamed('resources') ? 'active' : '' }}"><a href="{!! url('resources') !!}" class='resources' data-id='resources'>Resources</a></li>
                

                
                    @if(Auth::user()->roles == 'admin' )
                    	 <li class="{{ Route::currentRouteNamed('team-members') ? 'active' : '' }}"><a href="{!! url('team-members') !!}" class='resources' data-id='resources'>Teams</a></li>
                    @endif
                    <li class="{{ Route::currentRouteNamed('birthdays') ? 'active' : '' }}"><a href="{!! route('birthdays') !!}" class='birthdays' data-id='birthdays'>Birthday</a></li>
                    @if(Auth::user()->roles == "admin" && Auth::user()->is_teamlead == false)
                    	<li class="{{ Route::currentRouteNamed('current-projects') ? 'active' : '' }}"><a href="{!! route('current-projects') !!}" class='current-projects' data-id='current-projects'>Going Projects</a></li>
                    @endif
                    {{-- Remove this functionality due to Merge in people section --}}

                    {{-- @if(Auth::user()->roles == 'admin' )
                    	 <li class="{{ Route::currentRouteNamed('user-permissions') ? 'active' : '' }}"><a href="{!! url('user-permissions') !!}" class='permission' data-id='resources'>Permission</a></li>
                    @endif --}}

                    
				</ul>
			</div>
		</div>
	</nav>
	<div class="page-header">
		<div class="container-fluid">
			@if(Request::segment(1) == '')
			<h1>Dashboard</h1>
			@elseif(Request::segment(1) == 'companies')
			<h1>Company</h1>
			@elseif(Request::segment(1) == 'industries')
			<h1>Industry</h1>
			@elseif(Request::segment(1) == 'departments')
			<h1>Department</h1>
			@elseif(Request::segment(1) == 'designations')
			<h1>Designation</h1>
			@elseif(Request::segment(1) == 'everything')

			<h1>Everything</h1>
            @elseif(Request::segment(1) == 'change-profile')

            <h1>Change Profile</h1>
            @elseif(Request::segment(1) == 'change-password')

            <h1>Change Password</h1>
            
			@elseif(Request::segment(1) == 'people')
			<h1>People</h1>
			@elseif(Request::segment(1) == 'birthdays')
			<h1>BIRTHDAY BUDDIES</h1>
			
			@elseif(Request::segment(1) == 'projects' && Request::segment(2))
			<?php 
                $project= \App\Project::find(Request::segment(2));
                if(empty($project))
                {
                    return redirect()->back()->with('error','The project that you want to access does not exist or deleted by Admin');
                }
            ?>
			<h1><a href ="{!!url('/projects'),'/',$project->id,'/tasks'!!}">{{ucwords($project->name)}}</a></h1>
			<h2>( {{ucwords($project->company->name)}} ) </h2>
			<div class="loggedhours text-right {{$project->price_types!='fix'?'not-fix-log':''}}">
				<div class="row">
                    @if($project->price_types=='fix')
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="fixhours text-right">
                            <span>Estimated Hours</span><strong>{{$project->fix_hours}} hrs</strong>
                        </div>
                    </div>
                    @endif
					<div class="col-lg-{!!$project->price_types=='fix'?'8':'12'!!} col-md-{!!$project->price_types=='fix'?'8':'12'!!} col-sm-{!!$project->price_types=='fix'?'8':'12'!!} col-xs-12">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div class="billables">
									<span class="title">Billable</span>
									<span class="count">{{$billable_hours}} hrs</span>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div class="nonbillables">
									<span class="title">Non Billable</span>
									<span class="count">{{$non_billable_hours}} hrs</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@elseif(Request::segment(1) == 'projects' && Request::segment(2))
			<?php $project= \App\Project::find(Request::segment(2));?>
    			<h1>
    			{{ucwords($project->name)}}
    			</h1>
			<h2>( {{ucwords($project->company->name)}} )</h2>
			@elseif(Request::segment(1) == 'projects' || Route::currentRouteNamed('search-projects'))
			 <h1>Projects</h1>
			@elseif(Request::segment(1) == 'project-categories')
		  	<h1>Project Category</h1>
			@elseif(Request::segment(1) == 'task-categories')
			 <h1>Task Category</h1>
            @elseif(Request::segment(1) == 'theme')
              <h1>Theme Setting</h1>
             @elseif(Request::segment(1) == 'resources' || Request::segment(1) == 'filter-resource-availability')
             	 <h1>Resource Availability</h1>
             @elseif(Request::segment(1) == 'team-members')
             	<h1>Teams</h1>
             @elseif(Request::segment(1) == 'user-permissions')
             	<h1>User Permission</h1>
            @else
                <h1>&nbsp;</h1>
			@endif
		</div>
	</div>
</div>