@extends('layouts.app')
@section('title','Designation')
@section('content')
<div ng-controller="DesignationCtrl" >
 <div class="page-user-log ">
        @include('shared.user_login_detail')
    </div>
<div class="container-fluid">
    <ul class="breadcrumb" ng-cloak>  
       <li><a href="{!!url('/')!!}"><span><i class="fa fa-home"></i></span></a></li>
       
        <li class='active'><span>Designation</span></li>
    </ul>
    <div class="panel panel-transparent">
        <div class="panel-heading clearfix">
            <div class="panel-title">Designation Listing</div>
            <div class="action">
                @if(Auth::user()->roles == "admin")
                    <div class="cols">
                        <button  data-toggle="modal" data-target="#addNewAppModal" class="btn btn-md btn-default"><i class="fa fa-plus"></i> Add Designation</button>
                    </div>
                @endif
            </div>
        </div>
        <div class="panel-body">
            <div class="loader" ng-if="loading"></div>
            <table datatable="ng" dt-options="dtOptions" dt-column-defs="dtColumnDefs" dt-instance="dtInstance" class="table vc table-striped" ng-if='designations.length>0' ng-cloak>
                <thead>
                    <th class="text-left">Name</th>
                    <th class="text-right">
                        @if(Auth::user()->roles == "admin")
                            Action
                        @endif
                    </th>
                </thead>
                <tbody ng-cloak>
                    <tr ng-repeat="designation in designations| orderBy:'-id'" ng-show="designations.length != 0">
                        <td class="text-left" ng-cloak>{% designation.name ? designation.name : '-' %}</td>
                        <td class="text-right">
                            @if(Auth::user()->roles == "admin")
                                <a class="btn btn-md btn_edit" ng-click="editDesignation(designation.id)" ><i class="fa fa-edit"></i></a>
                                <a class="btn btn-md btn_delete" ng-click="deleteDesignation(designation.id)" ><i class="fa fa-trash"></i></a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div ng-cloak class="col-md-12" ng-if="(designations|filter:q).length==0">
                <div style="text-align:center;">
                    <img src="{!! asset('img/noDesignation.png') !!}"  height="100px" width="100px" />
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
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" ng-click="clearAll()"><i class="fa fa-close"></i></button>
                <h4>{%modal_title%} Designation</h4>
            </div>
            <form name='Designation'  ng-submit="submit(Designation)" class='form' role='form' novalidate>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="label"><span>Name<em>*</em></span></label>
                                <input id="appName" type="text"  name="name" class="form-control" placeholder="Name of Designation"  ng-model='designation.name' required>
                                <span class="error" ng-show="submitted && Designation.name.$error.required">* Please Enter Designation</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="add-app" type="button" class="btn btn-md btn-add" ng-click="submit(Designation)"> {%modal_title%}</button>
                    <button type="button" class="btn btn-md btn-close" id="close"  ng-click="clearAll(Designation)" >Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
