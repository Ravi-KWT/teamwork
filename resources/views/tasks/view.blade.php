@extends('layouts.app')
@section('title','Task')
@section('content')
<div ng-controller="TasksCtrl">
	<div class="page-user-log ">
	    @include('shared.user_login_detail')
	</div>
	<div class="container-fluid">
		<div class="inner mb_30">
			<ul class="nav nav-tabs clearfix">
				<li class="{{ url('/projects/{% Pro_Id %}/tasks') ? 'active' : '' }}"><a href="{!!url('/projects/{% Pro_Id %}/tasks')!!}">Task</a></li>
				{{-- <li><a href="{!! url('/projects/{% Pro_Id %}/milestones') !!}">Milestone</a></li> --}}
				<li><a href="{!! url('/projects/{% Pro_Id %}/people') !!}">People</a></li>
			</ul>
		</div>
		<div class="panel panel-transparent">
			<div class="panel-heading clearfix">
				<div class="panel-title">Task Details</div>
				<div class="action">
					<div class="cols">
						<a  class="btn btn-md btn-default"  ng-click="editTask(taskDetail.id)">Edit Task</a>
					</div>
				</div>
			</div>
			<div class="panel-body">
                <div ng-cloak   class="loader" ng-if="loading"></div>
                <div class="panel panel-gray">
                    <div class="panel-body">
                        <ul class="task_add_list no-border additional" ng-cloak >
                            <span class="agodays" ng-cloak>{%taskDetail.created_at|timeAgo%}</span>
                            <li ng-cloak>
                                <div class="checkbox" ng-init="tsk1.completed=taskDetail.completed">
                                    <input type="checkbox" name="completed" ng-checked='taskDetail.completed' ng-model="tsk1.completed" id="completed" ng-click="task_completed(taskDetail.id, tsk1.completed)">
                                    <label for="completed"></label>
                                </div>
                            </li>
                            <li ng-cloak>
                            	{{-- Added on 23-06-2018  --}}
                            	<span ng-if='tsk.users.length==0'>
                                    	<strong > Anyone </strong>
                                </span>
                                <span ng-if='taskDetail.users.length==1' ng-repeat="tu in taskDetail.users">
                                    <strong ng-if="tu.id == 0">
                                         Anyone
                                    </strong>
                                    <strong ng-if="tu.people.lname != null">
                                         <a href="/people/{%tu.people.id%}">{%tu.people.fname+ ' ' +tu.people.lname%}</a>
                                    </strong>
                                    <strong ng-if="tu.people.lname == null">
                                    <a href="/people/{%tu.people.id%}">{%tu.people.fname%}</a>
                                    </strong>
                                </span>
                                <div class="dropdown" ng-if='taskDetail.users.length>1'>
                                    <span id="user_task_1" data-toggle="dropdown">
                                        <strong  >
                                        {%'You+'+(taskDetail.users.length-1)%}
                                        </strong>
                                        <span class="caret"></span>
                                    </span>

                                    <ul class="dropdown-menu" aria-labelledby="user_task_1">
                                        <li ng-repeat='tu in taskDetail.users' class='tskuser'> <a href="/people/{%tu.people.id%}">{%tu.people.fname%} {%tu.people.lname%}</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li ng-cloak>
                                <span ng-class="tsk1.completed?'taskname task_name-true':'taskname task_name-false'" title="Taks Id: #{% taskDetail.id %}">
                                    <a href="{!!url('/projects/{% taskDetail.project_id %}/tasks/{%taskDetail.id%}')!!}">{% taskDetail.name %}
                                    </a>
                                </span>
                            </li>
                            <li ng-cloak>
                                <div class="addEntery" ng-cloak>
                                	
                                	<a data-task-id="{% taskDetail.id %}" ng-style="taskDetail.timers.length != 0 && {'display':'none'}" class="start-timer edit" data-project-id="{% taskDetail.project_id %}" title="Start Timer" data-toggle="tooltip">
                                	    <i class="fa fa-play"></i>
                                	</a>
                                    <a class="timer" title="Logtime" ng-click="showLogModal($event,tsk.id)" id="timer_button">
                                        <i class="fa fa-clock-o"></i>
                                    </a>
                                    <a class="edit" title="Edit Task" id="view_button2"  ng-click="editTask(taskDetail.id)">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a class="trash" title="Delete Task" id="view_button3" ng-click="deleteSingleTask(taskDetail.id)">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </li>

                        </ul>

                    </div>
                    <div class="panel-footer">
                        <div ng-cloak class="task-additional-detail">
                            <ul class="list-inline">
                                <li><label>Start Date :</label> {% taskDetail.start_date %}</li>
                                <li><label>End Date :</label> {% taskDetail.due_date %}</li>
                                <li><label>Priority :</label> {% taskDetail.priority|ucfirst %}</li>
                            </ul>
                            <ul class="list-inline" ng-if='taskDetail.notes'>
                                <li><label>Notes :</label> {% taskDetail.notes ? taskDetail.notes:"" %}</li>
                            </ul>
                        </div>
                        <div class="filter-total-time" ng-cloak>
							<ul ng-cloak>
								{{-- <li><label>Filtered Totals:</label></li> --}}
								<li ng-cloak><span>Logged:</span>{%secondsToTime(total_task_minute*60).h +' hrs '+secondsToTime(total_task_minute*60).m+' mins ('+total_task_hours+')'%}</li>
								<li ng-cloak><span>Non-billable:</span>{%secondsToTime((total_task_minute-billable)*60).h +" hrs "+secondsToTime((total_task_minute-billable)*60).m+ " mins (" +total_task_non_billable_hours+')'%}</li>
								<li ng-cloak><span>Billable:</span>{%secondsToTime(billable*60).h +' hrs '+secondsToTime(billable*60).m+' mins ('+total_task_billable_hours+')'	%}</li >
							</ul>
						</div>
                    </div>
                </div>
			</div>
		</div>
		<div class="panel panel-transparent clearfix">
			<div class="panel-heading clearfix">
				<div class="panel-title">Time Logs</div>
				<div class="action">
					<div class="cols" ng-if='logs.length>0'>
						<a title="Logtime" ng-click="showLogModal($event,tsk.id)" id="timer_button" class="btn btn-md btn-default">Add More Logtime</a>
					</div>
				</div>
			</div>
		
			<div class="panel-body" ng-cloak>
				<div  ng-if="logs.length>0">
					<table datatable="ng" dt-options="dtOptions" dt-column-defs="dtColumnDefs" class="table vc table-striped" ng-if='logs.length>0' ng-cloak>
						{{-- <table class="table" id="tableWithDynamicRows" ng-if='logs.length>0' ng-cloak> --}}
							<thead>
								<tr>
									<th class="text-left">Date</th>
									<th>Who</th>
									<th width="200">Description</th>
									<th>Start</th> <div uib-timepicker ng-model="mytime" ng-change="changed()" hour-step="hstep" minute-step="mstep" show-meridian="ismeridian"></div>
									<th>End</th>
									<th>Times</th>
									{{-- <th>Hours</th> --}}
									<th>Billable</th>
									<th class="text-right">Action</th>
								</tr>
							</thead>
							<tbody >
								<tr ng-repeat="log in logs |orderBy:'-id'"  ng-if="logs.length != 0">
									<td class="text-left"><span style="display: none;">{% log.date | taskViewYearMonthDayFormat %}</span> {% log.date | taskViewDateFormat %}</td>
									<td><a href="/people/{%log.user.people.id%}">{% log.user.people.fname ? log.user.people.fname : '-' %} {% log.user.people.lname %}</a></td>
									<td title="{%log.description%}" data-toggle="tooltip" data-placement="bottom">{%log.description?log.description:'' | strLimit:40%}</td>
									<td>{%log.start_time%}</td>
									<td>{%log.end_time%}</td>
									<td>{%secondsToTime(log.minute*60).h +' hrs '+secondsToTime(log.minute*60).m+' mins'%}</td>
							{{-- 		<td>{%log.hour%}</td> --}}
									<td ng-if='log.billable'>
										{{-- <img src="/img/billable.png" width="20px" height="20px" ng-click='changeBillable(log.id,log.billable=false)'> --}}
										<span ng-click='changeBillable(log.id,log.billable=false)' class="billable"><i class="fa fa-check"></i></span>
									</td>
									<td ng-if='log.billable==false'>
										{{-- <img src="/img/non_billable.png" width="20px" ng-click='changeBillable(log.id,log.billable=true)' height="20px"> --}}
										<span ng-click='changeBillable(log.id,log.billable=true)' class="nobillable"><i class="fa fa-close"></i></span>
									</td>
									<td class="text-right">
										<a class="btn btn-md btn_edit" ng-click="editLog(log.id)" ><i class="fa fa-edit"></i></a>
										<a class="btn btn-md btn_delete" ng-click="deleteLog(log.id)" ><i class="fa fa-trash"></i></a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div ng-show="logs.length>5">
						<a title="Logtime" ng-click="showLogModal($event,tsk.id)" id="timer_button" >Add more logtime</a>
					</div>
					<div  ng-show="logs.length==0" style="text-align: center;" ng-cloak>
						<img  ng-cloak src="{!! asset('img/noTaskCategory.png') !!}"  height="100px" width="100px" />
						<p>No records</p>
						<a title="Logtime" ng-click="showLogModal($event,tsk.id)" id="timer_button" class="btn btn-md btn-default">Add Logtime</a>
					</div>
				</div>
			</div>
		</div>
    
		<div class="modal fade stick-up" id="addNewAppModal" data-backdrop='static' keyboard='false'
			 tabindex="-1" role="dialog" aria-labelledby="addNewAppModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header clearfix ">
						<button type="button" class="close" data-dismiss="modal" ng-click="cancelAll()" aria-hidden="true"><i class="fa fa-close"></i></button>
						<h4 >{%modal_title%} Task</h4>
					</div>
                         <div class="alert alert-danger" ng-show="formError > 0"><span><center>Please provide required information.</center></span></div>
					<form name="Task" ng-submit="submit(Task)" class='form' role='form' novalidate>
						<input type="hidden" ng-model='task.task_id' name='task_id' value='task.task_id'>
                        <input type="hidden" name="project_id"  ng-model="project_id" >
						<div class="modal-body">
							<!-- Nav tabs -->
							<ul class="nav nav-tabs my-tabs">
								<li class="active" id='default-home'><a data-toggle="tab" href="#home" aria-expanded="true"><span>Description</span></a></li>
								<li><a data-toggle="tab" href="#menu1" aria-expanded="true"><span>Date</span></a></li>
								<li><a data-toggle="tab" href="#menu2" aria-expanded="true"><span>Priority</span></a></li>
							</ul>
							<!-- Tab panes -->
							<div class="tab-content">
								<div class="tab-pane slide-left active" id="home">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="label"><span>Task Name</span></label>
												<input id="name" type="text" name="name" class="form-control" placeholder="Name Of Task" ng-model='task.name' required>
												<span class="error" ng-show="submitted && Task.name.$error.required">* Please enter Task name</span>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group clearfix">
												<label class="label"><span>Assign To</span></label>
												<div ng-dropdown-multiselect="" name='user_id' showCheckAll='false' showUncheckAll='false' options="users" selected-model="example14model" checkboxes="true" extra-settings="example14settings" ></div>
												<span class="error" ng-show="submitted && Task.user_id.$error.required">* Please select people
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="form-group">
													<div ng-if='example14model.length>0'>
														Assign To:
													</div>
													<span ng-repeat='tu in example14model' class="labels label-warning">
														{%tu.label%}
													</span>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label class="label"><span>Notes</span></label>
													<input id="notes" type="text" name="notes" class="form-control" placeholder="Notes Of Task" ng-model='task.notes' {{-- required --}}>
													{{-- <span class="error" ng-show="submitted && Task.notes.$error.required">* Please enter Task Notes</span> --}}
												</div>
											</div>
											<input type="hidden" name="project_id"  ng-model="task.project_id">
											<input type="hidden" name="category_id" ng-model="task.category_id">
										</div>
									</div>
									<div class="tab-pane slide-left" id="menu1">
										<div class="row">
											<div class="col-md-12">
												<div class="form-group clearfix">
													<label class="label"><span>Start Date</span></label>
													<div class="input-group datepicker" date-format="yyyy-MM-dd" date-max-limit="{% task.due_date %}" selector="form-control">
														<input type="text" name="start_date" class="form-control" placeholder="Pick a start date" id="task-start-date" ng-model="task.start_date " ng-value="{%task.start_date|date:'dd/MM/yyyy'%}" readonly>
														 <label class="input-group-addon" for="task-start-date">
                                                           <span class="fa fa-calendar"></span>
                                                        </label> 
                                                        {{-- <span class="input-group-addon">
															<i class="fa fa-calendar"></i>
														</span> --}}
													</div>
												</div>
											</div>
										</div>
										<div class="row clearfix">
											<div class="col-md-12 ">
												<div class="form-group clearfix">
													<label class="label"><span>Due Date</span></label>
													<div class="input-group datepicker" date-format="yyyy-MM-dd" date-min-limit="{% task.start_date %}" selector="form-control">
														<input type="text" name="due_date" class="form-control" placeholder="Pick a due date" id="task-due-date" ng-model='task.due_date' readonly>
                                                        <label class="input-group-addon" for="task-due-date">
                                                           <span class="fa fa-calendar"></span>
                                                        </label> 
														{{-- <span class="input-group-addon">
															<i class="fa fa-calendar"></i>
														</span> --}}
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane slide-left" id="menu2">
										<div class=" row ">
											<div class="col-md-12">
												<label class="label"><span>set priority</span></label>
												<div class="inline-radio" ng-init="task.priority='medium'">
													<div class="radio">
														<input type="radio" ng-model="task.priority" name='priority' id="none" ng-value="'none'">
														<label for="none">None</label>
													</div>
													<div class="radio">
														<input type="radio" ng-model='task.priority' name='priority' id="low" ng-value="'low'">
														<label for="low">Low</label>
													</div>
													<div class="radio">
														<input type="radio" ng-model='task.priority' name='priority' id="medium" ng-value="'medium'">
														<label for="medium">Medium</label>
													</div>
                                                   
                                                   
													<div class="radio">
														<input type="radio" ng-model='task.priority' name='priority' id="high" ng-value="'high'">
														<label for="high">High</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button id="add-app" type="button" class="btn btn-md btn-add" ng-click="submit(Task)" >{%modal_title%}</button>
								<button type="button" class="btn btn-md btn-close" id="close" ng-click="clearAll(Task)">Close</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="modal fade stick-up" id="logTimeModal" data-backdrop='static' keyboard='false' ng-keyup="$event.keycode == 27?logClearAll(Logtime):'hello'" tabindex="-1" role="dialog" aria-labelledby="logTimeModal" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header clearfix ">
							<button type="button" class="close" ng-click="logCancel()" data-dismiss="modal" aria-hidden="true"> <i class="fa fa-close"></i>
							</button>
							<h4>Log time on this task</h4>
						</div>
						<form name="Logtime" ng-submit="submit(Logtime)" class='form' role='form' novalidate>
							<div class="modal-body">
								<div class="tab-content">
									<div class='form-group'>
										<label class="label"><span>Task : </span></label>
										<span> {%taskDetail.name %} </span>
									</div>
									<div>
										<div class="form-group">
											<label class="label"><span>Logged Hours :</span></label>
											<span> {%secondsToTime(total_task_minute*60 ).h  +' hrs '+secondsToTime(total_task_minute*60).m+' mins ('+total_task_hours +')'%}</span>
										</div>
									</div>
									<div class="tab-pane slide-left active" id="loghome">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
												<label class="label"><span>User</span></label>
													<input type="text" ng-if="edit==false" value='{!!Auth::user()->people->fname.' '. (Auth::user()->people->lname?Auth::user()->people->lname:'')!!}' readonly class="form-control"></input>

                                                    <input type="text" tabindex="-1" name='user' ng-if="edit==true" value="{%username.people.fname%} {%username.people.lname?username.people.lname:''%}" readonly class="form-control"></input>
												</div>
											</div>
											<div class="col-md-6 ">
												<div class="form-group">
													<label class="label"><span>Date</span></label>
													<div class="datepicker log-date-picker" date-format="dd-MM-yyyy" date-set = "{% currentDate.toDateString()%}" selector="form-control">
														<div class="input-group">
															<input type="text" name="date" class="form-control" placeholder="Pick a date" id="log-date" ng-model='logtime.date' required readonly>
															<label class="input-group-addon" for="log-date">
                                                                <span class="fa fa-calendar"></span>
                                                            </label> 
                                                            {{-- <span class="input-group-addon">
																<i class="fa fa-calendar"></i>
															</span> --}}
														</div>
														<span class="error" ng-show="submitted && Logtime.date.$error.required">* Please select date</span>
													</div>
												</div>
											</div>
										</div>
										
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<!-- <div><div uib-timepicker ng-model="mytime" ng-change="changed()" hour-step="hstep" minute-step="mstep" show-meridian="ismeridian"></div></div> -->
													<label class="label"><span>Start Time</span></label>
													<div class="input-group bootstrap-timepicker">
														<input id="timepicker_1" type="text" name="start_time" class="form-control" ng-model="logtime.start_time" data-default-value="{%logtime.start_time%}" placeholder="HH:MM AM" required>
														<span class="input-group-addon" ><i class="glyphicon glyphicon-time"></i></span>
													</div>
													<span class="error" ng-show="submitted && Logtime.start_time.$error.required">* Please select start time</span>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="label"><span>End Time</span></label>
													<div class="input-group bootstrap-timepicker">
														<input id="timepicker_2" type="text" name="end_time" class="form-control" ng-model="logtime.end_time" placeholder="HH:MM AM" min-time="{%logtime.start_time%}"  required>
														<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
													</div>
													<span class="error" ng-show="submitted && Logtime.end_time.$error.required">* Please select start time</span>
												</div>
											</div>
										</div>
										
										<div class="row " >
											<div class="col-md-12">
												<div class="form-group">
													<label class="label"><span>Spent Time </span></label>
													<input  type="text" tabindex="-1"  class="form-control"  placeholder="HH:MM AM/PM" value="{% calc_spent_time(logtime.end_time,logtime.start_time)  %}" readonly>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label class="label"><span>Description</span></label>
													<textarea id="description" name="description" type="text" class="form-control" placeholder="Description of Log Time" ng-model='logtime.description' {{-- required --}}></textarea>
													<span class="error" ng-show="submitted && Logtime.description.$error.required">* Please enter log description</span>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="billable-nonbillable-check">
													<div>
														<input class="form-check-input" type="radio" name="billable" ng-model="logtime.billable" id="billable" value="Billable" ng-checked="(logtime.billable == true)">
														  <label class="form-check-label" for="billable">
														    Billable
														  </label>
													</div>
													  <div>
													  	
														<input class="form-check-input" type="radio" name="billable" ng-model="logtime.billable" ng-value="false" id="NonBillable" ng-checked="(logtime.billable == false)">
														  <label class="form-check-label" for="NonBillable">
														    Non Billable
														  </label>
													  </div>
												</div>
													<span class="error" ng-show="submitted && Logtime.billable.error">* Please select the log type</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button id="edit_log" type="button" class="btn btn-md btn-add" ng-click="submitLog(Logtime)" ng-disabled="calc_spent_time(logtime.end_time,logtime.start_time)=='0 min'">{%modal_title%}</button>
								<button type="button" class="btn btn-md btn-close" id="close"  ng-click="logClearAll(Logtime)">Close</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		@endsection
		@section('scripts')
		    <script>
		        $(document).on('click','.start-timer',function(e){
		            e.preventDefault();
		            var _this = $(this);
		            var task_id = $(this).attr('data-task-id');
		            var project_id = $(this).attr('data-project-id');
		            
		            $.ajax({
		                url:'{{ url('/start-log-timer') }}',
		                type:'post',
		                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  },
		                data:{task_id:task_id, project_id:project_id},
		                success:function(response){
		                    $(_this).hide();
		                    $(document).find('.log-timer').css('display','block');
		                    $(document).find('.log-timer').html(response.render_log_timers);
		                    start_timer(response.timer.id,response.timer.last_started_at, response.timer.duration);
		                    // console.log(response.timer.id,response.timer.last_started_at, response.timer.duration);
		                }
		            });
		        });

		    </script>
		@endsection