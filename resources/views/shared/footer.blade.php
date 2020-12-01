<footer>
	<div class="container-fluid">
		<div class="row">
			{{-- <div class=" col-lg-7 col-md-7 col-sm-12 col-xs-12">
				<ul class="list-inline">
					<li>
						<a href="#">
							<span class="icons"><span class="fa fa-life-ring"></span></span>
							Help
						</a>
					</li>
					<li>
						<a href="#">
							<span class="icons"><span class="fa fa-comment"></span></span>
							Feedback & Support
						</a>
					</li>
					<li>
						<a href="#">
							<span class="icons"><span class="fa fa-bullhorn"></span></span>
							Refer Teamwork
						</a>
					</li>
				</ul>
			</div> --}}
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<p class="text-right">
					© 2008 - {{ date('Y') }} KrishaWeb Technologies Pvt. Ltd. All rights reserved. An ISO 9001:2008 Certified Company.
				</p>
			</div>
		</div>
		{{-- @if(Auth::user()->timers->count() > 0) --}}
		<div id="log-timer" class="log-timer time-log-window" @if(Auth::user()->timers->count() == 0) style="display: none;" @endif>
			<div class="tl-header">
				<div class="tl-count-item">{{ Auth::user()->timers->count() }} Active Timers</div>
				@if(Auth::user()->timers->count() > 1)
					<div class="tl-item-toggle">
						<a href="javascript:;" id="current-timer-show" data-type="up"><i class="fa fa-angle-up"></i></a>
					</div>
				@endif
			</div>
			@foreach(Auth::user()->timers->sortByDesc('running') as $key => $timer)
			<div class="log log-item" @if($key != 0) style="display: none;" @endif>
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
		</div>
		{{-- @endif --}}
	</div>
</footer>