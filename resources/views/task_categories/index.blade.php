@extends('layouts.app')
@section('title','Task Category')
@section('content')
<div ng-controller="TaskCategoryCtrl" ng-cloak>
    <div class="page-user-log ">
        @include('shared.user_login_detail')
    </div>
   <div class="container-fluid">
        <ul class="breadcrumb" ng-cloak>
            <li><a href="{!!url('/')!!}"><span><i class="fa fa-home"></i></span></a></li>
            <li class="active"><span>Task Category</span></li>
        </ul>
    
    <div class="panel panel-transparent" >
        <div class="panel-heading clearfix">
            <div class="panel-title" >Task Category</div>
            <div class="action" >
                @if(Auth::user()->roles == "admin")
                <div class="cols">
                    <button data-target="#addNewAppModal" data-toggle='modal' class="btn btn-md btn-default"><i class="fa fa-plus"></i> Add Task Category</button>
                </div>
                @endif
            </div>
        </div>
        <div class="panel-body">
           <div class="loader" ng-if="loading"></div>
           <table  ng-cloak datatable="ng" dt-options="dtOptions" dt-column-defs="dtColumnDefs" dt-instance="dtInstance"  class="vc table-striped"  ng-if="task_categories.length>0" >
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Project</th>
                        <th>
                            @if(Auth::user()->roles == "admin")
                            <div class="text-right">Action</div>
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody ng-cloak>
                    <tr ng-repeat="category in task_categories" ng-show="task_categories.length != 0"  ng-cloak>
                        <td  class="datas box_real">{% category.name ? category.name : '-' %}</td>
                        <td>{%category.project.name%}</td>
                        <td>
                            @if(Auth::user()->roles == "admin")
                            <div class="text-right">
                                <a class="btn btn-md btn-default" ng-click="editCategory(category.id)" ><i class="fa fa-edit"></i></a>
                              {{--  <a href="{!!url('/task-categories/{%category.id%}')!!}" class="btn btn-md btn-default"><i class="fa fa-eye"></i></a>--}}
                                <a class="btn btn-md btn-default" ng-click="deleteCategory(category.id)" ><i class="fa fa-trash"></i></a>
                            </div>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div ng-cloak class="col-md-12" ng-if="task_categories.length==0">
                <div ng-cloak style="text-align:center;">
                    <img  ng-cloak src="{!! asset('img/noTaskCategory.png') !!}"  height="100px" width="100px" />
                    <h3>No records</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade stick-up" id="addNewAppModal" tabindex="-1" role="dialog" aria-labelledby="addNewAppModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <button type="button" class="close" ng-click="cancelAll()" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-close"></i>
                </button>
                <h4>{%modal_title%} Task Category</h4>
            </div>
            <form name='taskCategory'  ng-submit="submit(taskCategory)" class='form' role='form' novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="label"><span>Select Project<em>*</em></span></label>
                                <select class="form-control selcls" ng-model="task_category.project_id" id='sel1' name='project_id' required>
                                    <option value="" selected="selected">Select Project</option>
                                    <option ng-value="project.id" ng-repeat="project in projects " ng-selected="edit==true && task_category.project_id==project.id">{%project.name%}</option>
                                        
                                </select>
                                <span class="error" ng-show="submitted && taskCategory.project_id.$error.required">* Please select project. </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="label"><span>name<em>*</em></span></label>
                                <input id="appName" type="text" class="form-control"
                                placeholder="Name of Category"  ng-model='task_category.name' required>
                                <span class="error" ng-show="submitted && taskCategory.$error.required">* Please enter task category</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="add-app" type="button" class="btn btn-md btn-add" ng-click="submit(taskCategory)" >{%modal_title%}</button>
                    <button type="button" class="btn btn-md btn-close" id="close" ng-click='clearAll(taskCategory)'>Close</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection

