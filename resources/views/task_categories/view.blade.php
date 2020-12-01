@extends('layouts.app')
@section('title','Task Category')
@section('content')
<div ng-controller="TaskCategoriesCtrl">
    <div class="page-user-log ">
        @include('shared.user_login_detail')
    </div>
    <div class="container-fluid">
        <ul class="breadcrumb" ng-cloak>
            <li><a href="{!!url('/')!!}"><span><i class="fa fa-home"></i></span></a></li>
            @if(Auth::user()->roles=='admin')
                <li><a href="{!!url('task-categories')!!}"><span>Task Categories</span></a></li>
            @endif
            <li class="active"><span>{{$task_category->name}}</span></li>
        </ul>
        <div ng-cloak class="loader" ng-if="loading"></div>
        <div class="panel panel-transparent">
            <div class="panel-heading clearfix">
                 <div class="action">
                        <div class="cols">
                            <button ng-click="showModal($event)" type="button" class="btn btn-md btn-default"  id="add-task" > <i class="fa fa-plus"></i> Add Task </button>
                        </div>
                       {{--  <div class="cols">
                            <a href="#" class="btn btn-md btn-default">Edit Task</a>
                        </div>
                        <div class="cols">
                            <a href="#" class="btn btn-md btn-default">Option</a>
                        </div> --}}
                    </div> 
            </div>
            <div class="panel-body">
                <div class="panel panel-gray">
                    <div class="panel-heading clearfix">
                        <div class="panel-title">Tasks ( {{$tasks->count()}} )</div>
                    </div>
                    <div class="panel-body">
                        <ul class="list-block">
                            @forelse($tasks as $task)
                            <li>
                                <a data-toggle="tooltip" data-placement="bottom" href="{{url('projects',[$task->project_id,'tasks',$task->id])}}" title="Project : {{$task->project->name}}">{!!$task->name!!}</a>
                            </li>
                            @empty
                            <li>No Tasks</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="panel panel-gray">
                    <div class="panel-heading clearfix">
                        <div class="panel-title">COMPLETED TASKS ( {{$tasksCompleted->count()}} )</div>
                    </div>
                    <div class="panel-body">
                        <ul class="list-block">
                            @forelse($tasksCompleted as $taskCompleted)
                            <li><a href="{{url('projects',[$taskCompleted->project_id,'tasks',$taskCompleted->id])}}">{!!$taskCompleted->name!!}</a></li>
                            @empty
                            <li>No Tasks</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade stick-up" id="addNewAppModal" tabindex="-1" role="dialog" keyboard='true' aria-labelledby="addNewAppModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header clearfix">
                    <button type="button" class="close" data-dismiss="modal" ng-click="cancelAll()" aria-hidden="true"><i class="fa fa-close"></i>
                    </button>
                    <h4  >Add Task</h4>
                </div>
                <div class="alert alert-danger" ng-show="formError > 0"><span><center>Please provide required information.</center></span></div>
                    <form name="Task" ng-submit="submit(Task)" class='form' role='form' novalidate>
                    {!! csrf_field() !!}
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
                                                <div ng-dropdown-multiselect="" name='user_id' showCheckAll='false' showUncheckAll='false' options="users" selected-model="example14model" checkboxes="true" extra-settings="example14settings"></div>
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
                                                <input id="notes" type="text" name="notes" class="form-control" placeholder="Notes Of Task" ng-model='task.notes'>
                                            </div>
                                        </div>
                                        <input type="hidden" name="project_id"  ng-model="task.project_id" ng-init="task.project_id = '{{ $project_id }}'">
                                        <input type="hidden" name="category_id" ng-model="task.category_id" ng-init="task.category_id = '{{$id}}'">
                                    </div>
                                </div>
                                <div class="tab-pane slide-left" id="menu1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="label"><span>Start Date</span></label>
                                            <div class="datepicker" date-format="yyyy-MM-dd" date-max-limit="{% task.due_date %}" selector="form-control">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" name="start_date" class="form-control" placeholder="Start date" id="start-date" ng-model='task.start_date' >
                                                        <label class="input-group-addon" for="start-date">
                                                            <i class="fa fa-calendar"></i>
                                                        </label>
                                      
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-md-12 ">
                                            <label class="label"><span>End Date</span></label>
                                            <div class="datepicker" date-format="yyyy-MM-dd" date-min-limit="{% task.start_date %}" selector="form-control">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" name="due_date" class="form-control" placeholder="Due date" id="task-due-date" ng-model='task.due_date' >
                                                        <label class="input-group-addon" for="task-due-date">
                                                            <i class="fa fa-calendar"></i>
                                                        </label>
                                                        {{-- <span class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </span> --}}
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
                            <button id="add-app" type="button" class="btn btn-md btn-add" ng-click="submit(Task)" >Save</button>
                            <button type="button" class="btn btn-md btn-close" id="close" ng-click="clearAll(Task)">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
