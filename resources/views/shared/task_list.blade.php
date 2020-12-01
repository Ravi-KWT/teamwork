<nav class="page-sidebar" data-pages="sidebar" id="leftmenu">
<div class="mCustomScrollbar" data-mcs-theme="minimal-dark" data-height="100%">
	@foreach($task_categories as $task_cat)
		<span><i class="fa fa-tasks"></i>
			<a href="{!! url('task-categories/{task_cat->id}') !!}">{!! $task_cat->name !!}</a>
		</span>
	@endforeach
</div>
</nav>