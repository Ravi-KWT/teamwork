@if(Auth::user()->timers->where('completed',false)->count() > 0)
	<div class="tl-header">
		<div class="tl-count-item">{{ Auth::user()->timers->count() }} Active Timers</div>
		@if(Auth::user()->timers->count() > 1)
			<div class="tl-item-toggle">
				<a href="javascript:;" id="current-timer-show" data-type="down"><i class="fa fa-angle-down"></i></a>
			</div>
		@endif
	</div>
	@foreach(Auth::user()->timers->sortByDesc('running') as $timer)
	{{-- <div class="log">
		<div class="row col-md-12">
			<div class="form-group col-md-6">
				<p>{{ $timer->task->project->name }}</p>
				<p>{{ $timer->task->name }}</p>
				<a class="btn btn-default addition-timer-data-show" data-toggle="collapse" data-timer-id="{{ $timer->id }}" aria-controls="collapseExample">
					<i class="fa fa-angle-down"></i>
				</a>
			</div>
			<div class="form-group col-md-6">
				
				<p class="timer-counter-{{ $timer->id }}">@if($timer->runnig == false) {{ gmdate("H:i:s", $timer->duration) }} @endif</p>
				<button class="btn btn-primary resume-timer" data-timer-id="{{ $timer->id }}" @if($timer->running == 1) style="display: none;" @endif>Resume</button>
				<button class="btn btn-primary pause-timer" data-timer-id="{{ $timer->id }}" @if($timer->running == 0) style="display: none;" @endif>Pause</button>
				<button class="btn btn-primary submit-log-timer" data-timer-id="{{ $timer->id }}">Log</button>
				<button class="btn btn-primary delete-log" data-timer-id="{{ $timer->id }}">Delete</button>
			</div>
		</div>
		<div class="collapse row col-md-12 description addition-timer-data" id="collapseExample{{ $timer->id }}" style="display: none;">
		  <div class="form-group">
		  	<label for="description">Description</label>
		  	<textarea name="description" class="description form-control" style="color: black;" rows="5"></textarea>
		  </div>
		  <div class="from-group">
		  	<label>Billable</label>
		  	<div >
		  		<label class="switch">
		  		  <input type="checkbox" name="billable" class="billable" checked >
		  		  <span class="slider round"></span>
		  		</label>
		  	</div>
		  </div>
		</div>
	</div> --}}
	<div class="log log-item">
				<div class="time-log-view-header">
					<div class="time-log-title">
						<div class="tl-project-name">{{ $timer->task->project->name }}</div>
						<div class="tl-project-task-name">{{ $timer->task->name }}</div>
						<a class="tl-comment-trigger addition-timer-data-show" data-toggle="collapse"  data-timer-id="{{ $timer->id }}" >
							<i class="fa fa-angle-down"></i>
						</a>
					</div>
					<div class="log-actions">
						<div class="tl-time timer-counter-{{ $timer->id }}">@if($timer->running == 0) {{ gmdate("H:i:s", $timer->duration) }} @endif</div>
						<div class="tl-actions">
							<button class="tl-action-btn resume-timer" data-timer-id="{{ $timer->id }}" @if($timer->running == 1) style="display: none;" @endif> <i class="fa fa-play"></i> Start</button>
							<button class="tl-action-btn pause-timer" data-timer-id="{{ $timer->id }}" @if($timer->running == 0) style="display: none;" @endif><i class="fa fa-pause"></i> Pause</button>
							<button class="tl-action-btn btn-success submit-log-timer" data-timer-id="{{ $timer->id }}"><i class="fa fa-clock-o"></i> Log</button>
						</div>
					</div>
				</div>
				<div class="tl-comment">
					<div class="collapse addition-timer-data" id="collapseExample{{ $timer->id }}" style="display: none;">
						<div class="form-group">
							<label for="description">Description</label>
							<textarea name="description" class="description form-control"></textarea>
						</div>
						<div class="tl-comment-action">
							<div class="tl-billable-check">
								<div >
									<label class="switch small-switch">
										<input type="checkbox" name="billable" class="billable" checked>
										<span class="slider round"></span>
									</label>
								</div>
								<label>Billable</label>
							</div>
							<div class="tl-delete-log">
								<button class="tl-action-btn btn-danger delete-log" data-timer-id="{{ $timer->id }}"> <i class="fa fa-times"></i>Delete</button>
							</div>
						</div>
					</div>
				</div>
			</div>
	@endforeach
@endif