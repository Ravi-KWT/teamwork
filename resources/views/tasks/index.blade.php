@extends('layouts.app')
@section('title','Task')
@section('content')
<div ng-controller="TasksCtrl">
     <div class="page-user-log ">
            @include('shared.user_login_detail')
        </div>
    <div class="container-fluid">
        @include('shared.session')
        @if(Auth::user()->roles == 'admin')
            <div class="panel panel-gray" ng-cloak ng-if="taskcategories.length == 0">
                <div class="panel-heading clearfix">
                    <div class="panel-title">Import task list</div>               
                </div>
                <form name="importTaskList" method="post" action="{!! url('import-task-list') !!}" enctype="multipart/form-data">
                {!! csrf_field() !!}
                    <div class="panel-body">
                        <input type="hidden" name="project_id" value="{{$id}}" />
                        <input type="file" name="import_file" class="file" />
                        <div class="notes marT">
                            * Only Upload XLS or XLSX or CSV file.
                        </div>
                    </div>
                    <div class="panel-footer">
                        <input type="submit" name="send" class="btn btn-md btn-default" value="Upload">
                    </div>
                </form>
            </div>
        @endif
        <ul class="nav nav-tabs clearfix">
            <li class="active"><a href="javascript:;">Task</a></li>
            {{-- <li><a href="{!! url('/projects/{% Pro_Id %}/milestones') !!}">Milestone</a></li> --}}
            <li><a href="{!! url('/projects/{% Pro_Id %}/people') !!}">People</a></li>
            @if(Auth::user()->roles == 'admin' || Auth::user()->is_teamlead == true || Auth::user()->is_projectlead == true)
            <li class="pull-right">
                <button ng-click="showTaskCategoryModal($event)" type="button" class="btn btn-md btn-default"  id="{% Pro_Id %}" > <i class="fa fa-plus"></i> Add Task Category</button>
            </li>
            @endif
        </ul>
        <div ng-cloak class="loader" ng-if="loading"></div>
        <div class="panel panel-transparent">
            <div class="panel-heading clearfix">
                <div class="panel-title">Tasks</div>
                <div class="action">
                 {{--    <div class="cols" ng-show="tasks.length > 0" ng-cloak>
                        <input ng-model="q" type="text" id="search-table" class="form-control pull-right" placeholder="Search" ng-cloak>
                    </div> --}}
                    {{--   <div class="cols">
                        <a class="btn btn-default" href="{!! url('/task-categories') !!}" >Add New Task Category</a>
                    </div> --}}
                </div>
            </div>
            <div class="panel-body">
                <div ng-cloak class="panel-group task-list-group"  role="tablist" aria-multiselectable="true" ng-repeat='task_cat in taskcategories' >
                    <div ng-cloak class="panel panel-gray" id="{%task_cat.id%}" class="accordion">
                        <div  class="panel-heading task-list-header" role="tab" id="headingOne" >
                            <div class="panel-title" >
                                <a  data-toggle="collapse" data-parent="#{%task_cat.id%}" href="#tasklist{% task_cat.id %}" aria-expanded="true" aria-controls="collapseOne" class=''>
                                    {% task_cat.name %}
                                </a>
                            </div>
                        </div>

                        <div id="tasklist{% task_cat.id %}" class="panel-collapse collapse in" role="tabpanel"  aria-labelledby="headingOne" aria-expanded="false"  ng-cloak>
                            <div class="panel-body task-list">
                                <ul class="task_add_list" ng-repeat="tsk in tasks| orderBy:'-id'|filter:q" ng-if="tsk.category_id == task_cat.id " ng-show="tasks.length != 0">
                                    <span class="agodays" ng-cloak>{%tsk.created_at|timeAgo%}</span>
                                    <li>
                                        <div class="checkbox" ng-init="tsk[$index].completed=tsk.completed">
                                            <input type="checkbox" ng-checked="tsk.completed" name="completed{%$index%}" ng-model="tsk[$index].completed" id="completed{%$index%}" ng-click="task_completed(tsk.id, tsk[$index].completed)" ng-value='true'>
                                            <label for="completed{%$index%}"> </label>
                                        </div>
                                    </li>
                                    <li>
                                        <span ng-if='tsk.users.length==0'>
                                            <strong > Anyone </strong>
                                        </span>
                                        <span ng-if='tsk.users.length==1' ng-repeat="tu in tsk['users'] |orderBy:tu.fname">
                                            <strong  ng-if="tu.people.lname != null">
                                                  <a href="/people/{%tu.people.id%}">{%tu.people.fname+ ' ' +tu.people.lname%}</a>
                                            </strong>
                                            <strong  ng-if="tu.people.lname == null">
                                                  <a href="/people/{%tu.people.id%}">{%tu.people.fname%}</a>
                                            </strong>
                                            <strong  ng-if="tu.id == 0">
                                                Anyone
                                            </strong>
                                        </span>
                                        <div class="dropdown drop-arrow" ng-if='tsk.users.length>1'>
                                            <span id="user_task_1" data-toggle="dropdown">
                                                <strong>
                                                {%tsk.users[0].people.fname+ ' + ' +(tsk['users'].length-1)%}
                                                </strong>


                                                <span class="caret"></span>
                                            </span>
                                            <ul class="dropdown-menu" aria-labelledby="user_task_1">
                                                <li ng-repeat="tu in tsk['users'] | orderBy:tu.fname">
                                                    {{-- <a href="javascript:;">{%tu.people.fname+' '+tu.people.lname %}</a> --}}
                                                    <a href="/people/{%tu.people.id%}">{%tu.people.fname+' '+tu.people.lname %}</a>
                                                </li>
                                            </ul>
                                        </div>
                                        {{-- <span class="task_user">
                                            <strong  ng-if='tsk.users.length>1'>
                                            {%'You+'+(tsk.users.length-1)%}
                                            </strong>
                                        </span> --}}
                                        {{-- <div class="task_sub_user" ng-model='user_title' >
                                            <span ng-repeat='tu in tsk.users' class='tskuser'>{%tu.people.fname%} {%tu.people.lname%} dddd</span>
                                        </div> --}}
                                    </li>
                                    <li>

                                        <span ng-class="tsk[$index].completed?'taskname task_name-true':'taskname task_name-false'">
                                            <a href="{!!url('/projects/{% tsk.project_id %}/tasks/{%tsk.id%}')!!}">{% tsk.name %}</a>
                                        </span>

                                    </li>
                                    <li>
                                        <div class="addEntery" ng-if="!tsk.completed">

                                            <a data-task-id="{% tsk.id %}" ng-style="tsk.timers.length != 0 && {'display':'none'}" class="start-timer edit" data-project-id="{% tsk.project_id %}" title="Start Timer" data-toggle="tooltip">
                                                <i class="fa fa-play"></i>
                                            </a>
                                            <a class="timer" title="Logtime" ng-click="showLogModal($event,tsk.id,tsk.project.id)" id="timer_button">
                                                <i class="fa fa-clock-o"></i>
                                            </a>
                                            <a class="logtimer" title="View Logtime" href="{!!url('/projects/{% tsk.project_id %}/tasks/{%tsk.id%}')!!}" id="view_button1">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a class="edit" title="Edit Task" id="view_button2" ng-click="editTask(tsk.id)" >
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a class="trash" title="Delete Task" id="view_button3" ng-click="deleteTask(tsk.id)">
                                                <i class="fa fa-trash"></i>
                                            </a>

                                            {{-- <a data-task-id="{% tsk.id %}" ng-if="tsk.timers[0].running == true" ng-click="pauseTimer(tsk.id,{{ Auth::user()->id}},tsk.project_id,tsk.timers[0].id)">
                                                <i class="fa fa-pause"></i>
                                            </a> --}}
                                        </div>
                                    </li>
                                </ul>
                                <ul class="task_listing">
                                    <li><button ng-click="showModal($event)" type="button" class="btn btn-md btn-default"  id="{% task_cat.id %}" > <i class="fa fa-plus"></i> Add Task </button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div ng-cloak class="col-md-12" ng-if="taskcategories.length==0">
                    <div ng-cloak style="text-align:center;">
                        <img  ng-cloak src="{!! asset('img/noTaskCategory.png') !!}"  height="100px" width="100px" />
                        <h3>No records</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade stick-up"  id="addNewAppModal" tabindex="-1" role="dialog" data-backdrop="static" keyboard='false' aria-labelledby="addNewAppModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header clearfix">
                    <button type="button" class="close" data-dismiss="modal" ng-click="cancelAll()" aria-hidden="true"><i class="fa fa-close"></i>
                    </button>
                    <h4  >{%modal_title%} Task</h4>
                </div>
                <div class="alert alert-danger" ng-show="formError > 0"><span><center>Please provide required information.</center></span></div>
                <form name="Task" ng-submit="submit(Task)" class='form' role='form' novalidate>
                    <div class="modal-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs my-tabs" >
                            <li class="active" id='default-home'><a  data-toggle="tab" href="#home" aria-expanded="true"><span>Description</span></a></li>
                            <li><a data-toggle="tab" href="#menu1" aria-expanded="true"><span>Date</span></a></li>
                            <li><a data-toggle="tab" href="#menu2" aria-expanded="true"><span>Priority</span></a></li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane slide-left active" id="home">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="label"><span>Task Name<em>*</em></span></label>
                                            <input id="name" id='app-name' type="text" name="name" class="form-control" placeholder="Name Of Task" ng-model='task.name' required>
                                            <span class="error" ng-show="submitted && Task.name.$error.required">* Please enter Task name</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group clearfix">
                                            <label class="label"><span>Add People</span></label>
                                            <div ng-dropdown-multiselect="" name='user_id' showCheckAll='false' showUncheckAll='false' options="users" selected-model="example14model"  checkboxes="true" extra-settings="example14settings"></div>
                                        </div>
                                            <span class="error" ng-show="submitted && Task.user_id.$error.required">* Please select people
                                        </div>
                                    </div>
                                    <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <div ng-if='example14model.length>0' >
                                                        <label class="label"><span>Assign To:</span></label>
                                                        <span ng-repeat='tu in example14model' class="labels label-warning">
                                                            {%tu.label%}
                                                        </span>
                                                    </div>
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
                                            {{-- <div class="form-group clearfix">
                                                <label class="label">Start Date</span></label>
                                                <div class="input-group datepicker" date-format="dd-MM-yyyy" selector="form-control">
                                                    <input type="text" name="start_date" class="form-control" placeholder="Start Date" id="task-start-date" ng-model="task.start_date " ng-value="{%task.start_date|date:'dd-MM-yyyy'%}">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                </div>
                                            </div> --}}
                                            <label class="label"><span>Start Date</span></label>
                                            <div class="datepicker" date-format="yyyy-MM-dd" date-max-limit="{% task.due_date %}" selector="form-control">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" name="start_date" class="form-control" placeholder="Start date" id="start-date" ng-model='task.start_date' ng-change="substractDate(task.start_date)" readonly>
                                                        {{-- <span class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </span> --}}
                                                         <label class="input-group-addon" for="start-date">
                                                           <span class="fa fa-calendar"></span>
                                                        </label>   
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-md-12 ">
                                            {{-- <div class="form-group clearfix">
                                                <label class="label">Due Date {%task.start_date%}</span></label>
                                                <div class="input-group datepicker" date-format="dd-MM-yyyy" selector="form-control">
                                                    <input type="text" name="due_date" class="form-control" placeholder="Due Date" id="task-due-date" ng-model='task.due_date' >
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                </div>
                                            </div> --}}

                                            <label class="label"><span>End Date</span></label>
                                            <div class="datepicker" date-format="yyyy-MM-dd" date-min-limit="{% minDate.toDateString() %}" selector="form-control">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" name="due_date" class="form-control" placeholder="Due date" id="task-due-date" ng-model='task.due_date' readonly>
                                                    {{--     <span class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </span> --}}

                                                        <label class="input-group-addon" for="task-due-date">
                                                           <span class="fa fa-calendar"></span>
                                                        </label>  
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane slide-left" id="menu2">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="label-title">set priority</span></label>
                                            <div class="inline-radio" ng-init="task.priority='medium'">
                                                <div class="radio">
                                                    <input type="radio" ng-model="task.priority" name='priority' id="none" ng-value="'none'">
                                                    <label for="none">None</span></label>
                                                </div>
                                                <div class="radio">
                                                    <input type="radio" ng-model='task.priority' name='priority' id="low" ng-value="'low'">
                                                    <label for="low">Low</span></label>
                                                </div>
                                                <div class="radio">
                                                    <input type="radio" ng-model='task.priority' name='priority' id="medium" ng-value="'medium'">
                                                    <label for="medium">Medium</span></label>
                                                </div>
                                                <div class="radio">
                                                    <input type="radio" ng-model='task.priority' name='priority' id="high" ng-value="'high'">
                                                    <label for="high">High</span></label>
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
        {{-- add task modal --}}
        <div class="modal fade stick-up" id="logTimeModal" data-backdrop='static' tabindex="-1" role="dialog" aria-labelledby="logTimeModal" aria-hidden="false" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header clearfix">
                        <button type="button" class="close" ng-click="logCancel()" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
                        <h4>Log time on this task</h4>
                    </div>
                    <form name="Logtime" ng-submit="submit(Logtime)" class='form' role='form' novalidate>
                        <div class="modal-body">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane slide-left active" id="loghome">
                                    <div class="row">
                                        <input type="hidden" name="task_id"  ng-model="logtime.task_id" ng-value="{%tId%}">
                                        
                                          <input type="hidden" name="project_id"  ng-model="project_id" >
                                        
                                        <div class="col-md-6">
                                            <label class="label"><span>User</span></label>
                                            <div class="form-group">
                                                <input type="text" tabindex="-1" value='{!!Auth::user()->people->fname.' '. Auth::user()->people->lname!!}' readonly class="form-control"></input>
                                                {{--  <div ng-dropdown-multiselect="" options="users" selected-model="example14model" extra-settings="example14settings"></div> --}}
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                {{--<label class="date-txt">Date</span></label> --}}
                                                <label class="label"><span>Date</span></label>
                                                <div class="datepicker log-date-picker" date-format="dd-MM-yyyy" date-set="{% currentDate.toDateString() %}" selector="form-control">
                                                    <div class="input-group">
                                                        <input type="text" name="date" class="form-control" placeholder="Date" id="log-date" ng-model='logtime.date'  readonly>
                                                        <label class="input-group-addon" for="log-date">
                                                           <span class="fa fa-calendar"></span>
                                                        </label>   
                                                        {{-- <span class="input-group-addon" id="log-date">
                                                            <i class="fa fa-calendar"></i>
                                                        </span> --}}
                                                        <span class="error" ng-show="submitted && Logtime.date.$error.required">* Please select User</span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="label"><span>Start Time</span></label>
                                                <div class="input-group bootstrap-timepicker">
                                                    <input id="timepicker_1" type="text" name="start_time" class="form-control  input-group-addon" ng-model="logtime.start_time" placeholder="Start Time">
                                                    <span for='timepicker_1' class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="label"><span>End Time</span></label>
                                                <div class="input-group bootstrap-timepicker">
                                                    <input id="timepicker_2" type="text" name="end_time" class="form-control input-group-addon" ng-model="logtime.end_time" placeholder="End Time">
                                                    <span for='timepicker_2'  class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group" >
                                                <label class="label"><span>Spent Time </span></label>
                                                <input type='text' tabindex="-1" class="form-control" value="{% calc_spent_time(logtime.end_time,logtime.start_time) %}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="label"><span>Description</span></label>
                                                <textarea id="description" name="description" type="text" class="form-control" placeholder="Description" ng-model='logtime.description'></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="billable-nonbillable-check">
                                                <div>
                                                    <input class="form-check-input" type="radio" name="billable" ng-model="logtime.billable" value="Billable" id="billable" >
                                                      <label class="form-check-label" for="billable">
                                                        Billable
                                                      </label>
                                                </div>
                                                <div>
                                                    <input class="form-check-input" type="radio" name="billable" ng-model="logtime.billable" value="NonBillable" id="NonBillable" >
                                                    <label class="form-check-label" for="NonBillable">
                                                        Non Billable
                                                    </label>
                                                </div>
                                                {{-- <input type="checkbox" name="billable" ng-model="logtime.billable" id="k"> --}}
                                                {{-- <label for="k">Billable</span></label> --}}
                                            </div>
                                                <span class="error" ng-show="submitted && Logtime.billable.error">* Please select the log type</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="add-app" type="button" class="btn btn-md btn-add" ng-click="submitLog(Logtime)"  ng-disabled="calc_spent_time(logtime.end_time,logtime.start_time)=='0 min'">{%modal_title%}</button>
                            <button type="button" class="btn btn-md btn-close" id="close" ng-click="logClearAll(Logtime)">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- end add task modal --}}
        {{-- add task category modal --}}
        <div class="modal fade stick-up" id="addTaskCategoryModal" data-backdrop='static' tabindex="-1" role="dialog" aria-labelledby="addTaskCategoryModal" aria-hidden="false" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header clearfix">
                        <button type="button" class="close" ng-click="logCancel()" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
                        <h4>Add Task Category</h4>
                    </div>
                    <form name="taskCategory" ng-submit="submitTaskCategory(taskCategory)" class='form' role='form' novalidate>
                        <div class="modal-body">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane slide-left active">

                                    <div class="row">

                                        <input type="hidden" name="project_id"  ng-model="Pro_Id" ng-value="{%Pro_Id%}">
                                        <div class="col-md-6">
                                            <label class="label"><span>Task Category Name</span></label>
                                            <div class="form-group">
                                                <input type="text" tabindex="-1" ng-model="task_category_name" required="" id="task_category_name" name="task_category_name" ng-value="task_category_name"  class="form-control"></input>
                                                 <span class="error" ng-show="submitted && taskCategory.task_category_name.$error.required">* Please enter Task name</span>
                                                {{--  <div ng-dropdown-multiselect="" options="users" selected-model="example14model" extra-settings="example14settings"></div> --}}
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-md btn-add" ng-click="submitTaskCategory(taskCategory)"  >{%modal_title%}</button>
                            <button type="button" class="btn btn-md btn-close" data-dismiss="modal" aria-hidden="true" ng-click="logCancel()">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- start timer modal --}}
        {{-- <div class="modal fade stick-up" id="startTimerModal" data-backdrop='static' tabindex="-1" role="dialog" aria-labelledby="startTimerModal" aria-hidden="false" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header clearfix">
                        <button type="button" class="close" ng-click="logCancel()" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
                        <h4>Log time on this task</h4>
                    </div>
                    <form name="Logtime" ng-submit="submit(Logtime)" class='form' role='form' novalidate>
                        <div class="modal-body">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane slide-left active" id="loghome">
                                    <div class="row">
                                        <input type="hidden" name="task_id" id="task_id" value=""  ng-model="logtime.task_id" ng-value="{%tId%}">
                                        <input type="hidden" name="project_id"  ng-model="project_id">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">    
                                                <input type="text" name="clock" class="clock" readonly>
                                                <a href="javascript:;" class="pause btn btn-default" data-task-id="{% tId %}" >Pause</a>
                                                <a href="javascript:;" class="resume btn btn-default" data-task-id="{% tId %}" style="display: none;">Resume</a>
                                                <a class="submit-log" class="btn btn-default">End</a>
                                            </div>
                                        </div>
                                    </div> --}}
                                    {{-- <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="label"><span>Start Time</span></label>
                                                <div class="input-group bootstrap-timepicker">
                                                    <input id="timepicker_1" type="text" name="start_time" class="form-control  input-group-addon" ng-model="logtime.start_time" placeholder="Start Time">
                                                    <span for='timepicker_1' class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="label"><span>End Time</span></label>
                                                <div class="input-group bootstrap-timepicker">
                                                    <input id="timepicker_2" type="text" name="end_time" class="form-control input-group-addon" ng-model="logtime.end_time" placeholder="End Time">
                                                    <span for='timepicker_2'  class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    {{-- <div class="row spent_time" style="display: none;">
                                        <div class="col-md-12">
                                            <div class="form-group" >
                                                <label class="label"><span>Spent Time </span></label>
                                                <input type='text' tabindex="-1" class="form-control total_spent_time" value="" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="label"><span>Description</span></label>
                                                <textarea id="description" name="description" type="text" class="form-control" placeholder="Description" ng-model='logtime.description'></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="checkbox check-success">
                                                <input type="checkbox" name="billable" ng-model="logtime.billable" id="k">
                                                <label for="k">Billable</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="add-app" type="button" class="btn btn-md btn-add" >{%modal_title%}</button>
                            <button type="button" class="btn btn-md btn-close" id="close" ng-click="logClearAll(Logtime)">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}
        {{-- end timer modal --}}
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