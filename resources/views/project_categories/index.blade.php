@extends('layouts.app')
@section('title','Project Category')
@section('content')
<div ng-controller="ProjectCategoryCtrl" >
 <div class="page-user-log ">
        @include('shared.user_login_detail')
    </div>
<div class="container-fluid">
    <div class="inner">
        <ul class="breadcrumb" ng-cloak>
            <li><a href="{!!url('/')!!}"><span><i class="fa fa-home"></i></span></a></li>
            <li class="active"><span>Project Category</span></li>
        </ul>
    </div>
    <div class="panel panel-transparent">
        <div class="panel-heading" >
            <div class="panel-title">Project Category Listing
            </div>
            <div class="action" >
                @if(Auth::user()->roles == "admin")
                    <div class="cols">
                        <button data-toggle="modal" data-target="#addNewAppModal" class="btn btn-md btn-default"><i class="fa fa-plus"></i> Add Project Category</button>
                    </div>
                @endif
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body">
            <div class="loader" ng-if="loading"></div>
            <table datatable="ng" dt-options="dtOptions" dt-column-defs="dtColumnDefs" dt-instance="dtInstance"  class="vc table-striped"  ng-if="categories.length>0" ng-cloak>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>
                        @if(Auth::user()->roles == "admin")
                            <div class="text-right">Action</div>
                        @endif
                    </th>
                </tr>
                </thead>
                <tbody ng-cloak >
                    <tr ng-cloak ng-repeat="category in categories| orderBy:'-id'" ng-if="categories.length > 0">
                        <td ng-cloak class="datas box_real"><a href='/project-categories/{%category.id%}'>{% category.name ? category.name : '-' %}</a></td>
                        <td>
                            @if(Auth::user()->roles == "admin")
                            <div class="text-right">
                                <a class="btn btn-md btn-default" ng-click="editCategory(category.id)" ><i class="fa fa-edit"></i></a>
                                <a class="btn btn-md btn-default" ng-click="deleteCategory(category.id)" ><i class="fa fa-trash"></i></a>
                            </div>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div ng-cloak class="col-md-12" ng-if="categories.length==0">
                <div style="text-align:center;">
                    <img src="{!! asset('img/noProjectCategory.png') !!}"  height="100px" width="100px"/>
                    <h3>No records</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade stick-up" id="addNewAppModal" tabindex="-1" role="dialog" aria-labelledby="addNewAppModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix ">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" ng-click="clearAll()"><i class="fa fa-close "></i>
                </button>
                <h4>{%modal_title%} Project Category</h4>
            </div>
            <form name='projectCategory'  ng-submit="submit(projectCategory)" class='form' role='form' novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="label"><span>name<em>*</em></span></label>
                                <input id="appName" type="text" name="name"  class="form-control" placeholder="Name of Category" ng-model='project_category.name' required>
                                <span class="error" ng-show="submitted && projectCategory.name.$error.required">* Please enter project category</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="add-app" type="button" class="btn btn-md btn-add" ng-click="submit(projectCategory)" >{%modal_title%}</button>
                    <button type="button" class="btn btn-md btn-close" id="close" ng-click="clearAll(projectCategory)">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
