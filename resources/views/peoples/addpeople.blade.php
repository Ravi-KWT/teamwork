@extends('layouts.app')
@section('title','People')
@section('content')
<div ng-controller="PeopleCtrl">
<div class="page-user-log ">
            @include('shared.user_login_detail')
        </div>
    <div class="container-fluid">
    	<div class="inner mb_30">
    		<ul class="nav nav-tabs clearfix">
    			<li><a href="{!!url('/projects/{% Pro_Id %}/tasks')!!}">Task</a></li>
    			{{-- <li><a href="{!! url('/projects/{% Pro_Id %}/milestones') !!}">Milestone</a></li> --}}
    			<li class="active"><a href="javascript:;">People</a></li>
    		</ul>
    	</div>
    	<div class="clearfix"></div>
    	<div class="panel panel-transparent">
    		<div class="panel-heading clearfix">
    			<div class="panel-title">People On This Project</div>
    			<div class="action">
    				@if(Auth::user()->roles == "admin" || Auth::user()->is_teamlead == true || Auth::user()->is_projectlead == true)
    					<div class="cols">
    						<button data-target="#addPeopleToProjectModal" data-toggle="modal" class="btn btn-md btn-default">Add / Remove People
    						</button>
    					</div>
    				@endif
    			</div>
    		</div>
    		<div class="panel-body">
    		<div ng-cloak class="loader" ng-if="loading"></div>
    			<div class="people-table">

    				<table  datatable="ng" dt-options="dtOptions2" dt-column-defs="
                    dtColumnDefs2" dt-instance="dtInstancePeople" class="table vc table-striped" ng-show="selected_users.length != 0 " ng-cloak>
    					<thead>
    						<tr role='row'>
    							<th width="80px" id='removeAsc'>Photo</th>
    							<th>Name</th>
    							<th>Email</th>
    						</tr>
    					</thead>
    					<tbody>
                        
    						<tr ng-repeat="user in projectPeople| orderBy:'email'"  {{-- ng-show="selected_users.length != 0 && selected_users.indexOf(user.id) > -1" --}} >
    							<td class="v-align-middle">
    								<div class="datas people_id_pic">
                                    <a href="/people/{%user.people.id%}">
    									<div ng-cloak class="avtar" ng-if="user.people.photo == null">
    										<div class="img avatar-sm">
    											<img ng-src="{!! asset('img/user.png') !!}"/>
    										</div>
    									</div>
    									<div ng-cloak class="avtar" ng-if="user.people.photo != null">
    										<div class="img avatar-sm">
    										  <img ng-src="{!! asset('uploads/people-thumb/{%user.people.photo%}') !!}"/>
    										</div>
    									</div>
                                        </a>
    									{{-- <p  ng-cloak>{% people.id %}</p> --}}
    								</td>
    								<td>
    									<p ng-cloak ng-if="user.people.lname != null"> 
                                        <a href="/people/{%user.people.id%}">
                                            {% user.people.fname ? (user.people.fname|ucfirst) +" "+ (user.people.lname|ucfirst):"-"%}</a>
                                        </p>
                                        <p ng-cloak ng-if="user.people.lname == null"> 
                                            <a href="/people/{%user.people.id%}">{% user.people.fname ? (user.people.fname|ucfirst) : "-"%}</a>
                                        </p>
    								</td>
                                    <td>
                                        <a href='mailto:{%user.email?user.email: "-"%}' ng-cloak> {%user.email?user.email: "-"%}</a>
                                    </td>
    							</tr>
    						</tbody>
                            <tfoot>
                            </tfoot>
    					</table>
    				</div>
    				<div ng-cloak class="col-md-12" ng-if="projectPeople.length==0">
    					<div style="text-align:center;">
    						<img src="{!! asset('img/noPeople.png') !!}" />
    						<p><h3>No records</h3></p>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    		<div class="modal fade stick-up add_project_people_modal" id="addPeopleToProjectModal" tabindex="-1" role="dialog"
    		aria-labelledby="addPeopleToProjectModal" aria-hidden="true">
    		<div class="modal-dialog modal-lg">
    			<div class="modal-content">
    				<div class="modal-header clearfix">
    					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i>
    					</button>
    					<h4>Add / Remove people to project </h4>
    				</div>
    				<form name='addPeople' class='form' role='form' novalidate>
    					<div class="modal-body">
    						<div class="form-group" ng-show="projectPeople.length > 0">
    							<label class="label"><span>Search People</span></label>
    							<input ng-model="query" type="text" id="search-table" class="form-control" placeholder="Search">
    						</div>
    						<div class="add_project_people add_project_people_list" ng-cloak>
    							<ul data-toggle="buttons">
    								<li ng-repeat="people in peoples|orderBy:'fname'|filterBy:query">
    									<div class="datas people_id_pic" ng-click="people.user.roles != 'admin' ? toggleSelection(people.user_id) : '' ">
    										<div ng-cloak class="avtar" ng-if="people.photo == null">
                                                <div class="img avatar-sm">
        											<a href="{!!url('/people/{%people.user_id%}')!!}" >
        												<img ng-src={!! asset("img/user.png") !!} />
        											</a>
                                                </div>
                                                <span class="name" data-toggle="tooltip" data-placement="bottom" title="{%people.fname | capitalize%} {% people.lname ? people.lname : '' | capitalize %}">{%people.fname | capitalize%} {% people.lname ? people.lname : '' | capitalize %} </span>
                                               
    										</div>
    										<div ng-cloak  class="avtar"  ng-if="people.photo != null">
                                                <div class="img avatar-sm">
        											<a href="{!!url('/people/{%people.user_id%}')!!}" >
        												<img ng-src={!! asset("uploads/people-thumb/{%people.photo%}") !!} />
        											</a>
                                                 </div>

    											     <span class="name" data-toggle="tooltip" data-placement="bottom" title="{%people.fname | capitalize%} {% people.lname ? people.lname : '' | capitalize %}">{%people.fname | capitalize%} {% people.lname ? people.lname : '' | capitalize %} </span>
                                               
    										</div>
    									</div>

                                        <div class="checkbox" ng-if="people.user.roles == 'admin' ">
    										<input type="checkbox" name="selectedPeoples[]" value="people_{% people.user_id %}" id={%people.user_id%} ng-checked="selected_users.indexOf(people.user_id) > -1" >
    										<label ng-disabled="true" for={%people.user_id%}></label>
    									</div>
                                        <div class="checkbox" ng-if="people.user.roles != 'admin' ">
                                            <input type="checkbox" name="selectedPeoples[]" value="people_{% people.user_id %}" id={%people.user_id%} ng-checked="selected_users.indexOf(people.user_id) > -1" >
                                            <label ng-click="toggleSelection(people.user_id)" for={%people.user_id%}></label>
                                        </div>
    								</li>
    							</ul>
    						</div>
    					</div>
    					<div class="modal-footer">
    						<button  type="button" class="btn btn-md btn-add" ng-click="addPeopleToProject()" ng-disabled="selected_users==0">Save</button>
    						<button type="button" class="btn btn-md btn-close" data-dismiss="modal" id="close" ng-click="clearPeople(addPeople)">Close</button>
    					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
