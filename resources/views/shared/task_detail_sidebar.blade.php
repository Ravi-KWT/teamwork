<nav class="page-sidebar" data-pages="sidebar"  id="leftmenu">
<div class="mCustomScrollbar" data-mcs-theme="minimal-dark" data-height="100%">
	<ul class="task-detail-sidebar">
		<li>
			<label>Task Category</label>
			<span>{!!$task_details->category->name or '-'!!}</span>
		</li>
		<li>
			<label>Assigned to</label>
			@forelse($task_details->users as $tu )
                @if($tu->id != 0)
				    <span> {!! $tu->people->fname." ".$tu->people->lname!!}</span>
                @else
                    Anyone
                @endif
			@empty
				<span>  Anyone</span>
			@endforelse
		</li>
		<li>
			<label>Start date</label>
			<span>
            
            {!!$task_details->start_date or '-'!!}
			
            
			</span>


		</li>
		<li>
			<label>Due date</label>
			<span>{!! $task_details->due_date or '-'!!}</span>
		</li>
		<li>
			<label>Assigned by</label>
			<span>{!!$task_details->assignedby or '-'!!}</span>
		</li>
		<li>
			<label>Date assigned</label>
			<span>{!! $task_details->created_at->format('D d M Y h:i A') !!}</span>
		</li>
		<li>
			<label>Date updated</label>
			<span>{!! $task_details->updated_at->format('D d M Y h:i A') !!}</span>
		</li>
		<li>
			<label>Task Id</label>
			<span># {!!$task_details->id!!}</span>
		</li>
	</ul>
</div>
</nav>