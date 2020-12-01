@extends('layouts.app')
@section('title','Department')
@section('content')
<div ng-controller="DepartmentCtrl" >
<div class="page-user-log">
    @include('shared.user_login_detail')
</div>
<div class="container-fluid">
    <ul class="breadcrumb" ng-cloak>
       <li><a href="{!!url('/')!!}"><span><i class="fa fa-home"></i></span></a></li>
        
        <li class="active"><span>Department</span></li>
    </ul>
    <div class="panel panel-transparent">
        <div class="panel-heading clearfix">
            <div class="panel-title">Department Listing</div>
            <div class="action">
                @if(Auth::user()->roles == "admin")
                    <div class="cols">
                        <button type='button' data-target="#addNewAppModal" data-toggle='modal' class="btn btn-md btn-default"><i class="fa fa-plus"></i> Add Department</button>
                    </div>
                @endif
            </div>
        </div>
        <div class="panel-body">
            <div class="loader" ng-if="loading"></div>
            <table datatable="ng" dt-options="dtOptions" dt-column-defs="dtColumnDefs" dt-instance="dtInstance" class="vc table-striped" ng-if='departments.length>0' ng-cloak>
                <thead>
                    <th class="text-left">Name</th>
                    <th class="text-right">
                        @if(Auth::user()->roles == "admin")
                           Action
                        @endif
                    </th>
                </thead>
                <tbody ng-cloak>
                    <tr ng-repeat="department in departments| orderBy:'created_at'"  ng-cloak>
                        <td ng-cloak class="row text-left">
                            {% department.name ? department.name : '-' %}
                        </td>
                        <td class="text-right">
                            @if(Auth::user()->roles == "admin")
                                <a class="btn btn-md btn_edit" ng-click="editDepartment(department.id)" ><i class="fa fa-edit"></i></a>
                                <a class="btn btn-md btn_delete" ng-click="deleteDepartment(department.id)"><i class="fa fa-trash"></i></a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="col-md-12" ng-show="departments.length==0" ng-cloak>
                <div style="text-align:center;">
                    <img src="{!! asset('img/noDepartment.png') !!}"  height="100px" width="100px" />
                    <h3 ng-cloak>No records</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade stick-up" id="addNewAppModal" tabindex="-1" role="dialog" aria-labelledby="addNewAppModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" ng-click="clearAll()"><i class="fa fa-close"></i></button>
                <h4>{%modal_title%} Department</h4>
            </div>
            <form name='Department' ng-submit="submit(Department)" class='form' role='form' novalidate>
                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="label"><span>name<em>*</em></span></label>
                                <input id="appName" type="text"   name="name" class="form-control" placeholder="Name of Department" ng-model='department.name' required>
                                <span class="error" ng-show="submitted && Department.name.$error.required">* Please Enter Department</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-md btn-add" ng-click="submit(Department)" >{%modal_title%}</button>
                    <button type="button" class="btn btn-md btn-close"  ng-click="clearAll(Department)">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
