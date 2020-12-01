<nav class="page-sidebar" data-pages="sidebar" id="leftmenu">
<div class="mCustomScrollbar" data-mcs-theme="minimal-dark" data-height="100%">
    @if(count($projects) > 0)
      @foreach($companies as $company)
                @foreach($projects as $key => $project)
                @if($company->id == $key)
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="title">{!!$company->name!!}</span>
                            <span class="badge">
                                {!!$project->count()!!}
                            </span>
                        </div>
                        <div class="panel-body">
                        <ul>
                            @foreach($project as $p)
                                    <li><a href="{!!url('/projects',array('id'=>$p->id)),'/','tasks'!!}">{!! ucwords($p->name) !!}</a></li>
                            @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            @endforeach
    	@endforeach
    @else
        @if(Auth::user()->roles == 'admin')
            <p>No projects</p>
        @else
            <p>No project allocated to you</p>
        @endif
    @endif
</div>
</nav>