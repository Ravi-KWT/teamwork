@extends('layouts.app')
@section('title','Industry')
@section('content')
<div ng-controller="IndustryCtrl" >
    <div class="page-user-log ">
        @include('shared.user_login_detail')
    </div>
    <div class="container-fluid">
        <ul class="breadcrumb" ng-cloak>
            <li><a href="{!!url('/')!!}"><span><i class="fa fa-home"></i></span></a></li>
            
            <li class="active"><span>Industry</span></li>
        </ul>
        <div class="panel panel-transparent">
            <div class="panel-heading">
                <div class="panel-title">Industry Listing</div>
                <div class="action">
                    @if(Auth::user()->roles == "admin")
                        <div class="cols">
                            <button data-target="#addNewAppModal" data-toggle='modal'  class="btn btn-md btn-default"><i class="fa fa-plus"></i> Add Industry</button>
                        </div>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <div class="loader" ng-if="loading"></div>
                <table datatable="ng" dt-options="dtOptions" dt-column-defs="dtColumnDefs" dt-instance="dtInstance" class="table vc table-striped" ng-if='industries.length>0' ng-cloak>
                    <thead>
                        <tr>
                            <th class="text-left">Name</th>
                            <th class="text-right">
                                @if(Auth::user()->roles == "admin")
                                <div class="datas people_action pull-right">Action</div>
                                @endif
                            </th>
                        </tr>
                    </thead>
                    <tbody ng-cloak>
                        <tr ng-repeat="industry in industries| orderBy:'-id'" ng-show="industries.length != 0">
                            <td ng-cloak class="row border_class text-left">{% industry.name ? industry.name : '-' %}</td>
                            <td class="text-right">
                                @if(Auth::user()->roles == "admin")
                                <div class="datas people_action pull-right">
                                    <a class="btn btn-md btn_edit" ng-click="editIndustry(industry.id)" ><i class="fa fa-edit"></i></a>
                                    <a class="btn btn-md btn_delete" ng-click="deleteIndustry(industry.id)" ><i class="fa fa-trash"></i></a>
                                </div>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                    <div ng-cloak class="col-md-12" ng-if="industries.length==0">
                        <div style="text-align:center;">
                            <img src="{!! asset('img/noIndustry.png') !!}"  height="100px" width="100px" />
                            <p><h3>No records</h3></p>
                        </div>
                    </div>
            </div>
        </div>
    </div>
<div class="modal fade stick-up" id="addNewAppModal" tabindex="-1" role="dialog" aria-labelledby="addNewAppModal" aria-hidden="true" ng-cloak>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix ">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" ng-click="clearAll()"><i class="fa fa-close"></i></button>
                <h4>{%modal_title%} Industry</h4>
            </div>
            <form name='Industry'  ng-submit="submit(Industry)" class='form' role='form' novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="label"><span>Name<em>*</em></span></label>
                                <input id="appName" type="text" name="name"  class="form-control" placeholder="Name of Industry"  ng-model='industry.name' required>
                                <span class="error" ng-show="submitted && Industry.name.$error.required">* Please enter industry</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="add-app" type="button" class="btn btn-md btn-add" ng-click="submit(Industry)" >{%modal_title%}</button>
                    <button type="button" class="btn btn-md btn-close" id="close" ng-click="clearAll(Industry)">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
