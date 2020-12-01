@extends('layouts.app')
@section('title','Milestones')
@section('content')
<div ng-controller="milestoneCtrl" >
	<div class="page-user-log ">
        @include('shared.user_login_detail')
    </div>
	<div class="container-fluid">
		<div class="inner mb_30">
			<ul class="nav nav-tabs clearfix">
            
				<li><a href="{!!url('/projects/{% Pro_Id %}/tasks')!!}">Task</a></li>
				<li class="active"><a href="javascript:;">Milestone</a></li>
				<li><a href="{!! url('/projects/{% Pro_Id %}/people') !!}">People</a></li>
			</ul>
		</div>
		<div class="panel panel-transparent">
			<div class="panel-heading clearfix">
				<div class="panel-title">Milestone</div>
				<div class="action" ng-cloak>
					<div class="cols">
						<button id="#addNewAppModal" data-target="#addNewAppModal" data-toggle='modal' class="btn btn-md btn-default"><i class="fa fa-plus"></i> Add Milestone</button>
					</div>
				</div>
			</div>
			<div class="panel-body">
			    <div ng-cloak   class="loader" ng-if="loading"></div>
				<ul class="milestone_list">
					<li dir-paginate="milestone in milestones| orderBy:'-id' | filter:q | itemsPerPage: pageSize" current-page="currentPage" ng-show="milestones.length != 0" ng-cloak>
						<div class="row" ng-cloak>
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
								<div class="dates">
									<div class="month">{%milestone.due_date |date:"MMM y"%}</div>
									<div class="body">
										<div class="date">{%milestone.due_date |date:"dd "%}</div>
										<div class="day">{%milestone.due_date |date:"EEEE"%}</div>
									</div>
								</div>
							</div>
							<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12" >
								<div class="data clearfix" >
									<div class="checkbox" ng-init="milestone[$index].completed=milestone.completed">
										<input type="checkbox" name="completed" ng-value='true' ng-model="milestone[$index].completed" id="completed{%$index%}" ng-click="milestone_completed(milestone.id, milestone[$index].completed)" ng-checked='milestone.completed'>
										<label for="completed{%$index%}" >
											 <span ng-class="milestone[$index].completed?'name name-true':'name name-false'">{% milestone.name %}</span>
											 <span class="complete" ng-if="milestone[$index].completed">Completed</span>
                                        </label>
									</div>
									<div class="discription">
										{% milestone.description %}
									 </div>
									<div class="notes">
										<p><label>Note :- </label>{% milestone.notes %}</p>
									</div>
									<div class="responsible">
										<label>Responsible Persons:</label>

										<span class="user" ng-repeat="t in milestone.users | orderBy:'people.fname'" >

											{% (t.people.fname|ucfirst) + " " + t.people.lname|ucfirst %}


										</span>
									</div>
									<div class="action text-left">
										<a class="" ng-click="editMilestone(milestone.id)" >
											<i class="fa fa-edit"></i>
											Edit
										</a>
										<a class="" ng-click="deleteMilestone(milestone.id)" >
											<i class="fa fa-trash"></i>
											Delete
										</a>
									</div>
									<span class="agodays" title="Due on" data-toggle="tooltip" data-placement="bottom">{%milestone.due_date|timeAgo%}</span>
								</div>
							</div>
						</div>
					</li>
				</ul>
				<div ng-cloak class="col-md-12" ng-if="(milestones|filter:q).length==0">
					<div style="text-align:center;">
						<img src="{!!asset('/img/noMilestone1.png')!!}" height="100px" width="100px">
						<p><h3>No Milestone </h3></p>
					</div>
				</div>
			</div>
			<dir-pagination-controls boundary-links="true"
			on-page-change="pageChangeHandler(newPageNumber)"></dir-pagination-controls>
		</div>
	</div>
	<div class="modal fade milestoenpopup" id="addNewAppModal" tabindex="-1" role="dialog"
		aria-labelledby="addNewAppModal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header clearfix">
					 <button type="button" class="close" data-dismiss="modal" aria-hidden="true" ng-click="clearAll()"><i class="fa fa-close"></i></button>

                    <h4>{% modal_title %} Milestone</h4>
				</div>
				<form name="Milestone" ng-submit="submit(Milestone)" class='p-t-15' role='form' novalidate>
					<div class="modal-body clearfix">
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" ng-if='edit==false'>
							<div class="dates">
								<div class="month">{!! $time->format('M Y')!!}</div>
								<div class="body">
									<div class="date">{!! $time->format('d')!!}</div>
									<div class="day">{!! $time->format('l')!!}</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" ng-if='edit==true'>
							<div class="dates">
								<div class="month">{%milestone.due_date1 |date:"MMM y"%}</div>
								<div class="body">
									<div class="date">{%milestone.due_date1 |date:"dd "%}</div>
									<div class="day">{%milestone.due_date1 |date:"EEEE"%}</div>
								</div>
							</div>
						</div>
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
							<div class="form-group">
								<label class="label"><span>Name</span></label>
								<div class="">
									<input id="name" type="text" name="name" class="form-control"
									placeholder="Name of Milestone" ng-model='milestone.name' required>
									<span class="error"
									ng-show="submitted && Milestone.name.$error.required">* Please enter milestone name.</span>
								</div>
							</div>
							<div class="form-group">
								<label class="label"><span>Description</span></label>
								<input id="description" type="text" name="description" class="form-control"
								placeholder="Description of Milestone" ng-model='milestone.description' required>
								<span class="error" ng-show="submitted && Milestone.description.$error.required">   * Please enter milestone description.
								</span>
							</div>
							<div class="form-group">
								<label class="label"><span>Due Date</span></label>
								<div class="input-group datepicker" date-format="dd-MM-yyyy" selector="form-control">
									<input type="text" name="due_date" class="form-control" placeholder="Pick a due date" id="milestone-due-date" ng-model='milestone.due_date' >
                                    
                                    <label class="input-group-addon" for="milestone-due-date" >
                                        <i class="fa fa-calendar"></i>
                                    </label>
									{{-- <span class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</span> --}}
								</div>
							</div>
							<div class="form-group">
								{{-- <select class="selectpicker" multiple data-selected-text-format="count" data-width="100%" id="user_id" multiple name="user_id" ng-model='milestone.user_id' >
									@foreach($users as $user)
									   <option value="{!! $user->id !!}">{!! $user->name !!}</option>
									@endforeach
								</select> --}}
                            <label class="label"><span>Assign to.</span></label>
							 <div ng-dropdown-multiselect="" name='user_id' showCheckAll='false' showUncheckAll='false' options="users" selected-model="example14model" checkboxes="true" extra-settings="example14settings"></div>


                            <div class="clearfix"></div>

                            </div>
                            <div class="form-group">
                                <div ng-if='example14model.length>0' >
                                    <label class="label"><span>Assign To:</span></label>
                                    <span ng-repeat='tu in example14model' class="labels label-warning">
                                        {%tu.label%}
                                    </span>
                                </div>
                            </div>
							<div class="form-group">
								<label class="label"><span>Notes</span></label>
								<input id="notes" type="text" name="notes" class="form-control"
								placeholder="Notes of Milestone" ng-model='milestone.notes' required>
								<span class="error" ng-show="submitted && Milestone.notes.$error.required">
									* Please enter milestone notes.
								</span>
							</div>
							<div class="form-group">
								<div class="checkbox">
									<input type="checkbox" name="reminder" ng-model='milestone.reminder' value="1" id="reminder">
									<label for="reminder">Reminder</label>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button id="add-app" type="button" class="btn btn-md btn-add" ng-click="submit(Milestone)">{%modal_title%}</button>
						<button type="button" class="btn btn-md btn-close" id="close" ng-click='clearAll(Milestone)'>
							Close
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
