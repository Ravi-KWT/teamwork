@extends('layouts.app')
@section('title','Project Category')
@section('content')
<div ng-controller="ProjectCategoryCtrl" >
        <div class="container-fluid">
            <div class="inner">
                <ul class="breadcrumb" ng-cloak>
                    <li><a href="{!!url('/')!!}"><span><i class="fa fa-home"></i></span></a></li>
                    @if(Auth::user()->roles == 'admin')
                        <li>
                            <a href="{!!url('project-categories')!!}" ><span>Project Category</span></a>
                        </li>
                    @endif
                    <li class="active"><span>{{$project_category->name}}</span></li> 
                </ul>
            </div>
            <div class="panel panel-transparent">
                <div class="panel-heading clearfix">
                    <div class="panel-title">{{$project_category->name}}</div>
                   {{--  <div class="action">
                        <div class="cols">
                            <button data-toggle="modal" data-target="#addNewAppModal" class="btn btn-md btn-default"><i class="fa fa-plus"></i> Add Project
                            </button>
                        </div>
                    </div> --}}
                </div>
                <div class="panel-body">
                @forelse($projects as $project)
                    <div class="panel panel-gray">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <a data-toggle="collapse" data-target="#{!!$project->id!!}">
                                   @if($projects->count()>0)
                                        {{$project->company->name}}
                                    @endif
                                </a>
                            </div>
                        </div>
                        <div class="panel-body" id="{!!$project->id!!}">
                           <a href="{!! url('/projects'),'/',$project->id,'/tasks'!!}" >{!!$project->name or '-'!!}</a>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12 sm-p-t-15">
                        <div style="text-align:center;">
                            <img src="{!! asset('img/noProjects.png') !!}"  height="100px" width="100px" />
                            <h3>No records</h3>
                        </div>
                    </div>
                @endforelse
            </div>
            {{-- <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)"></dir-pagination-controls> --}}
        </div>
    </div>

</div>

<div ng-controller='ProjectCtrl'>
   <div class="modal fade stick-up" id="addNewAppModal" tabindex="-1" keyboard=true role="dialog" aria-labelledby="addNewAppModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header clearfix ">
                    <button type="button" class="close" ng-click="cancelAll()" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
                    <h4 >{%modal_title%}  Project</h4>
                </div>
                {{-- Form global error message  --}}
                <div class="alert alert-danger" ng-show="formError > 0"><span><center>Please provide required information.</center></span></div>
                {{--End Form global error message  --}}
                <form name='project' ng-submit="submit(project)" class='form' role='form' novalidate>
                    <div class="modal-body">
                        <ul class="nav nav-tabs my-tabs">
                            <li class="active" id='default-home'><a  data-toggle="tab" href="#home">Project</a></li>
                            <li><a data-toggle="tab" href="#menu1">Category</a></li>
                            <li><a data-toggle="tab" href="#menu2">Features</a></li>
                            <li><a data-toggle="tab" href="#menu3">Dates</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="home" class="tab-pane slide-left active">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="label"><span>name</span></label>
                                            <input id="name" name="name" type="text" class="form-control" placeholder="Name of Project" ng-model='project_array.name' required>
                                            <span class="error" ng-show="submitted && project.name.$error.required">* Please enter project name</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="label"><span>Description</span></label>
                                            <textarea id="description" name="description" type="text" class="form-control" placeholder="Description of Project" ng-model='project_array.description' required></textarea>
                                            <span class="error" ng-show="submitted && project.description.$error.required">* Please enter description</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="label" for='se11'><span>Client</span></label>
                                            <select class="form-control selcls" ng-model="client_id" id='sel1' name='client_id'>
                                            <option  value>Select Client</option>
                                                <option ng-repeat='company1 in companies' ng-value="company1.id" ng-selected="company1.id==client_id">{% company1.name %}</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="menu1" class="tab-pane slide-left">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="label" for='pc'><span>Project Category</span></label>
                                         {{--    <input id=pc type="text"ng-model="project_array.category_id"  id='pc'  name="category_id" value=""></input>
                                           <input  type="text" class="form-control" value='{!!$project_category->name!!}' readonly> --}}
                                            {{-- <select class="form-control selcls" ng-model="project_array.category_id"  id='pc'  name="category_id" required>
                                               <option value="" selected > Select Category </option>
                                                <option ng-repeat='projectCategory in projectsCategories' value="{%projectCategory.id%}">{%projectCategory.name %}</option>
                                            </select>
                                            <span class="error" ng-show="submitted && project.category_id.$error.required">* Please Select Project Category</span> --}}
                                        </div>
                                    </div>
                                </div>
                               {{--  <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <span>
                                                <a class="btn btn-md btn-default" ng-click="showProjectCategory()">Add New Project Category</a>
                                            </span>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class='label'><span>Notes</span></label>
                                            <textarea id="notes" name="notes" type="text" class="form-control" placeholder="Notes of Project" ng-model='project_array.notes'></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="menu2" class="tab-pane slide-left">
                                <div class="row status-detail" ng-if='edit==true' >
                                    <div class="col-sm-12" >
                                        <div class="form-group">
                                             <label class="label"><span>Status</span></label>
                                                <div class="inline-radio" >
                                                    <div class="radio">
                                                        <input type="radio" ng-checked="project_array.status=='active'" ng-model='project_array.status' name='status' id="active" ng-value="'active'">
                                                        <label for="active">Active</label>
                                                    </div>
                                                    <div class="radio">
                                                        <input type="radio" ng-checked="project_array.status=='onhold'" ng-model="project_array.status" name='status' id="onhold" ng-value="'onhold'">
                                                        <label for="onhold">On Hold</label>
                                                    </div>
                                                    <div class="radio">
                                                        <input type="radio" ng-checked="project_array.status=='completed'" ng-model='project_array.status' name='status' id="completed" ng-value="'completed'">
                                                        <label for="completed">Completed</label>
                                                    </div>
                                                       <div class="radio">
                                                        <input type="radio" ng-checked="project_array.status=='archive'" ng-model='project_array.status' name='status' id="archive" ng-value="'archive'">
                                                        <label for="archive">Archive</label>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="label"><span>price type</span></label>
                                            <div class="inline-radio" ng-init="price_types='per_hour'">
                                                <div class="radio">
                                                    <input type="radio" ng-model="price_types" name='price_types' id="fix" ng-value="'fix'" ng-click="viewHours()">
                                                    <label for="fix">Fix</label>
                                                </div>
                                                <div class="radio">
                                                    <input type="radio" ng-model='price_types' name='price_types' id="per_hour" ng-value="'per_hour'" ng-click="hideHours()">
                                                    <label for="per_hour">Per Hour</label>
                                                </div>
                                                <div class="radio">
                                                    <input type="radio" ng-model='price_types' name='price_types' id="hiring" ng-value="'hiring'" ng-click="hideHours()">
                                                    <label for="hiring">Hiring</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                             <input class="form-control" type='text' ng-if="price_types=='fix'" id='fix_hours' name='fix_hours' placeholder="Hours" ng-model='project_array.fix_hours' ng-pattern="/^(0|[1-9][0-9]*)$/" required>
                                            <span class="error" ng-show="submitted && project.fix_hours.$error.required">* Please enter hours</span>
                                            <span class="error" ng-show="submitted && project.fix_hours.$error.pattern">Not valid hours!</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="menu3" class="tab-pane slide-left">
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <label class="label"><span>Start Date</span></label>
                                        <div class="datepicker" date-format="yyyy-MM-dd" selector="form-control">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" name="start_date" class="form-control" placeholder="Pick a date" id="start-date" ng-model='project_array.start_date' >
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <label class="label"><span>End Date</span></label>
                                        <div class="datepicker" date-format="yyyy-MM-dd" selector="form-control">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" name="end_date" class="form-control" placeholder="Pick a date" id="end-date" ng-model='project_array.end_date' >
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="add-app" type="button" class="btn btn-md btn-add" ng-click="submit(project)" >{%modal_title%}</button>
                        <button type="button" class="btn btn-md btn-close" id="close" ng-click="clearAll(project)">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
