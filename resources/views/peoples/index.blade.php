@extends('layouts.app')
@section('title','People')
@section('content')
<div ng-controller="PeopleCtrl">
    <div class="page-user-log ">
     @include('shared.user_login_detail')
 </div>
 <div class="container-fluid">
    <ul class="breadcrumb" ng-cloak>
        <li><a href="{!!url('/')!!}"><span><i class="fa fa-home"></i></span></a></li>
        <li class="active"><span>People</span></li>
    </ul>
    <div class="panel panel-transparent">
        <div class="panel-heading clearfix">
            <div class="panel-title">People Listing</div>
            <?php
            $marital_statuses = array('Married'=>'Married',
                'single'=>'single',
                'other'=>'other');
                ?>
                <div class="action">
                    @if(Auth::user()->roles == "admin")
                        <div class="cols">
                            <button data-target="#people_modal" data-toggle='modal' class="btn btn-md btn-default"><i class="fa fa-plus"></i> Add People</button>
                        </div>
                    @endif
                </div>
        </div>
                <div class="panel-body">
                    <div class="loader" ng-if="loading"></div>
                    <div class="all_view">
                        <!-- list view start -->
                        <div class="list_view">
                            <table datatable="ng" dt-options="dtOptions" dt-column-defs="dtColumnDefs" class="table vc table-striped" ng-if="peoples.length>0" ng-cloak>
                                <thead>
                                    <tr ng-cloak>
                                        <th width="80px" class="text-center">Pic</th>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Date Of Birth</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="people in peoples |orderBy:'created_at'">

                                        <td class="text-left" ng-if='people.photo==null' ng-cloak>
                                            <a href="/people/{%people.id%}">
                                                <div class="avtar">
                                                {{-- <div class="lightanimation">
                                                    <div class="light light_1 red"></div>
                                                    <div class="light light_2"></div>
                                                    <div class="light light_3"></div>
                                                    <div class="light light_4"></div>
                                                    <div class="light light_5"></div>
                                                    <div class="light light_6"></div>
                                                    <div class="light light_7"></div>
                                                    <div class="light light_8"></div>
                                                    <div class="light light_9"></div>
                                                    <div class="light light_10"></div>
                                                    <div class="light light_11"></div>
                                                    <div class="light light_12"></div>
                                                </div> --}}
                                                <div class="img avatar-sm">
                                                    <img ng-src={!! asset("img/user.png") !!}  />
                                                    <div class="admin-key" ng-if="people.user.roles=='admin'">
                                                        <i class="fa fa-key"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="text-left" ng-if='people.photo!=null' ng-cloak>
                                       <a href="/people/{%people.id%}">
                                        <div class="avtar">
                                                {{-- <div class="lightanimation">
                                                    <div class="light light_1"></div>
                                                    <div class="light light_2"></div>
                                                    <div class="light light_3"></div>
                                                    <div class="light light_4"></div>
                                                    <div class="light light_5"></div>
                                                    <div class="light light_6"></div>
                                                    <div class="light light_7"></div>
                                                    <div class="light light_8"></div>
                                                    <div class="light light_9"></div>
                                                    <div class="light light_10"></div>
                                                    <div class="light light_11"></div>
                                                    <div class="light light_12"></div>
                                                </div> --}}
                                                <div class="img avatar-sm">
                                                    <img ng-src={!! asset("uploads/people-thumb/{%people.photo%}") !!} />
                                                    <div class="admin-key" ng-if="people.user.roles=='admin'">
                                                        <i class="fa fa-key"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                        {{--   <td ng-cloak>
                                            <div ng-cloak class="pic" ng-if="people.photo == null">
                                                <img ng-src={!! asset("img/user.png") !!} />
                                            </div>
                                            <div ng-cloak class="pic" ng-if="people.photo != null">
                                                <img ng-src={!! asset("uploads/people-thumb/{%people.photo%}") !!} />
                                            </div>
                                        </td> --}}
                                        <td ng-cloak>
                                            <a href="/people/{%people.id%}">
                                                {% people.fname ? people.fname:'-'  %} {% people.lname ? people.lname : '' %}

                                                <span class="text-success" ng-if='people.user.is_teamlead'>(Team Lead)</span>
                                            </a>
                                        </td>
                                        <td ng-cloak><a href="/people/{%people.id%}">{% people.department.name ? people.department.name: '-' %}</a></td>
                                        <td ng-cloak><a href="/people/{%people.id%}">{% people.dob ? people.dob: '-' %}</a></td>
                                        
                                        <td ng-cloak><a href="/people/{%people.id%}"> {% people.phone ? people.phone : '-' %}</a></td>
                                    </a>
                                    @if(Auth::user()->roles == 'admin')
                                    <td ng-cloak>
                                        <a class="btn btn-md btn-close" ng-if='people.user.active==true' ng-click="statusChange(people.user.id + '-suspend')" title="Suspend the user">Click to Suspend</a>
                                        <a class="btn btn-md btn-add"  ng-if='people.user.active==false' ng-click="statusChange(people.user.id +'-active')" title='Activate the user'>Click to Activate</a>
                                    </td>
                                    @else
                                    <td ng-cloak>
                                        <a href="/people/{%people.id%}">
                                          <span ng-if='people.user.active==true'>Active</span>
                                          <span ng-if='people.user.suspend==true'>Suspended</span>
                                        </a>
                                  </td>
                                  @endif
                                  <td ng-cloak class="text-right">

                                    <a class="btn btn-md btn_view" href="/people/{%people.id%}"><i class="fa fa-eye"></i></a>
                                    {{-- Below commented code for view employee info in modals --}}
                                    {{-- <a class="btn btn-md btn_view" ng-click="viewPeople(people.id)"><i class="fa fa-eye"></i></a> --}}

                                    @if(Auth::user()->roles == 'admin')
                                    <a class="btn btn-md btn_edit" ng-click="editPeople(people.id)"><i class="fa fa-edit"></i></a>
                                    <a class="btn btn-md btn_delete" ng-click="deletePeople(people.user_id)"><i class="fa fa-trash"></i></a>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                                {{-- <tfoot>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Date Of Birth</th>
                                    <th>City</th>
                                    <th>Phone</th>
                                </tr>
                            </tfoot> --}}
                        </table>
                    </div>
                    <!-- list view close -->

                </div>
                <div ng-cloak class="col-md-12" ng-if="(peoples|filter:q).length==0">
                    <div class="text-center">
                        <i class="icon-people"></i>
                        <p><h3>No match found</h3></p>
                    </div>
                </div>
            </div>
            {{-- <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)"></dir-pagination-controls> --}}
        </div>
        <!-- END PANEL -->
    </div>
    <!-- END CONTAINER FLUID -->
    <!-- MODAL STICK UP  -->
    <div class="modal fade stick-up" id="people_modal" keyboard=true>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header clearfix">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" ng-click="clearAll()"><i class="fa fa-close"></i></button>
                    <h4>{%modal_title%} People</h4>
                </div>
                <div class="alert alert-danger" ng-show="formError > 0"><span><center>Please provide required information.</center></span></div>
                <form name='people' ng-submit="submit(people)" class='form' role='form' enctype="multipart/form-data" novalidate >
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="user_id" ng-model="people_array.user_id" ng-if="edit==true">
                    <div class="modal-body">
                        <ul class="nav nav-tabs nav-tabs-fillup my-tabs">
                            <li class="active" id='default-home'><a id='home1' data-toggle="tab" href="#tab_1">Personal</a></li>
                            <li><a data-toggle="tab" href="#tab_2">Address</a></li>
                            <li><a data-toggle="tab" href="#tab_3">Education</a></li>
                            <li><a data-toggle="tab" href="#tab_4">Experience</a></li>
                            <li><a data-toggle="tab" href="#tab_5">Employment</a></li>
                            <li><a data-toggle="tab" href="#tab_6">Social</a></li>
                        </ul>
                        <div class="tab-content">
                            <!-- tab 1 start -->
                            <div class="tab-pane active" id="tab_1">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span>First Name<em>*</em></span></label>
                                            <input type="text" name="fname" id='fname' class="form-control" placeholder="First Name" ng-model='people_array.fname' required>
                                            <span class="error" ng-show="submitted && people.fname.$error.required">* Please Enter First Name</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span>Last Name</span></label>
                                            <input type="text" name="lname" class="form-control" placeholder="Last Name" ng-model='people_array.lname'>
                                            {{-- <span class="error" ng-show="submitted && people.lname.$error.required">* Please Enter Last Name </span> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span>Email<em>*</em></span></label>
                                            <input type="text" name="email" id='people_email' ng-model='people_array.email' class="form-control" placeholder="Email" required ng-pattern='/^[_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/' >
                                            <span class="error" ng-show="submitted && people.email.$error.required" >* Please Enter Email </span>
                                            <span class="error" ng-show="submitted && people.email.$error.pattern">* Please Enter Valid Email</span>
                                            <span class="error" ng-show='email_error==true'>*{%user_email_error[0]%} </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span>Mobile</span></label>
                                            <input type="text" name="mobile" id='people_mobile' class="form-control" placeholder="Mobile Number" ng-model='people_array.mobile' ng-pattern="/^([0-9][0-9]*)$/">
                                            {{-- <span class="error" ng-show="submitted && people.mobile.$error.required">* Please Enter Mobile Number </span> --}}
                                            <span class="error" ng-show="submitted && people.mobile.$error.pattern">Not valid number!</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span>Phone</span></label>
                                            <input type="text" name="phone" class="form-control" placeholder="Phone Number" ng-model='people_array.phone' >
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">

                                            <label class="label"><span>Date of Birth<em>*</em></span></label>

                                            <div class="datepicker" date-format="dd-MM-yyyy" date-max-limit="{% max_date.toDateString() %}" date-change="changeDate" selector="form-control">
                                                <div class="custom-datepicker  input-group">
                                                    <input type="text" id='people_dob' name="dob" class="form-control" ng-model='people_array.dob' required readonly>
                                                    <label class="input-group-addon" for="people_dob">
                                                     <span class="fa fa-calendar"></span>
                                                 </label>   
                                                 {{-- <span class="input-group-addon"><i class="fa fa-calendar"></i></span> --}}
                                             </div>
                                         </div>
                                         <span class="error" ng-show="submitted && people.dob.$error.required">* Please Select Date of Birth </span>
                                     </div>
                                 </div>
                                 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="label"><span>Marital Status</span></label>
                                        <select class="form-control selcls" ng-model='people_array.marital_status' name="marital_status" id='sel1' >
                                            <option value={%ms.name%} ng-repeat='ms in marital_status' ng-selected="ms.name=='Single'">{%ms.name%}</option>
                                        </select>
                                        <span class="error" ng-show="submitted && people.marital_status.$error.required">* Please Select  Marital Status </span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label class="label"><span>Role</span></label>
                                        <select class="form-control" ng-model='people_array.roles' name="roles">
                                            <option value='admin'>Admin</option>
                                            <option value='employee'>Employee</option>
                                        </select>
                                    </div>
                                </div>
                               {{--       <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span>Team Lead</span></label>
                                            <select class="form-control " ng-model='people_array.is_teamlead' name="is_teamlead">
                                                <option value=true>Yes</option>
                                                <option value=false ng-selected="true">No</option>  
                                            </select>
                                        </div>
                                    </div> --}}
                                    <!-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span>Team Lead</span></label>
                                            <div class="inline-radio radio-default">
                                                <div class="radio " ng-init="is_teamlead=false">
                                                    <input type="radio"  ng-model='is_teamlead' name='is_teamlead' id="yes" ng-value="true">
                                                    <label for="yes">Yes</label>
                                                    
                                                </div>
                                                <div class="radio">
                                                    <input type="radio" ng-model='is_teamlead' name='is_teamlead'  id="no" ng-value="false">

                                                    <label for="no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span>Team Lead</span></label>
                                            <div class="inline-radio radio-default"  >
                                                <div class="radio " ng-init="is_teamlead=false" >
                                                    <input type="radio"  ng-model='is_teamlead' name='is_teamlead' id="yes" ng-value="true">
                                                    <label for="yes">Yes</label>
                                                </div>
                                                <div class="radio">
                                                    <input type="radio" ng-model='is_teamlead' name='is_teamlead'  id="no" ng-value="false">
                                                    <label for="no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="fileUpload">
                                            <div id="filelist"></div>
                                            <div id="preview">
                                                <div class="avtar inline">
                                                    <div class="img avatar-md">
                                                        <img  src="{!! asset('img/user.png')!!}" >
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div id="progressbar"></div> --}}
                                            <div id="container" >
                                                <span class="upload btn btn-md btn-upload" id="pickfiles">Upload</span>
                                            </div>
                                            <input type="hidden" name='photo' id="photo" ng-modal='people_array.photo'>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span>Gender</span></label>
                                            <div class="inline-radio radio-default"  >
                                                <div class="radio " ng-init="gender='male'" >
                                                    <input type="radio"  ng-model='gender' name='gender' id="male" ng-value="'male'">
                                                    <label for="male">Male</label>
                                                </div>
                                                <div class="radio">
                                                    <input type="radio" ng-model='gender' name='gender'  id="female" ng-value="'female'">
                                                    <label for="female">Female</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span>Project Lead</span></label>
                                            <div class="inline-radio radio-default"  >
                                                <div class="radio " ng-init="is_projectlead=false">
                                                    <input type="radio"  ng-model='is_projectlead' name='is_projectlead'  ng-value="true" id="projectlead_true">
                                                    <label for="projectlead_true">Yes</label>
                                                </div>
                                                <div class="radio">
                                                    <input type="radio" ng-model='is_projectlead' name='is_projectlead'  ng-value="false" id="projectlead_false">
                                                    <label for="projectlead_false">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- tab 1 close -->
                            <!-- tab 2 start -->
                            <div class="tab-pane" id="tab_2">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span>Address 1</span></label>
                                            <textarea id="appName" type="text" rows="5" name="adrs1" class="form-control" placeholder="Address 1" ng-model='people_array.adrs1'> </textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span>Address 2</span></label>
                                            <textarea id="appName" type="text" rows="5" name="adrs2" class="form-control" placeholder="Address 2" ng-model='people_array.adrs2'> </textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span>City</span></label>
                                            <input id="appName" name="city" type="text" class="form-control" placeholder="City" ng-model='people_array.city'>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span>Zipcode</span></label>
                                            <input id="appName" name="zipcode" type="text" class="form-control" placeholder="Zipcode" ng-model='people_array.zipcode' ng-pattern="/^(0|[1-9][0-9]*)$/">
                                            <span class="error" ng-show="submitted && people.zipcode.$error.pattern">Not valid zipcode!</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group form-group-default">
                                            <label class="label"><span>State</span></label>
                                            <input id="appName" name="state" type="text" class="form-control" placeholder="State" ng-model='people_array.state'>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group form-group-default">
                                            <label class="label"><span>Country</span></label>
                                            <select class="form-control selcls" ng-model='people_array.country' name="country" id='sel1'>
                                                <option value="" selected>Select Country</option>
                                                <option value={%country.name%} ng-repeat='country in countries'>{%country.name%}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- tab 2 close -->
                            <!-- tab 3 start -->
                            <div class="tab-pane" id="tab_3">
                                <div class="row row-border" ng-repeat="education in educations">
                                    <legend> Education {%$index+1%} </legend>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group form-group-default">
                                            <label class="label"><span>Qualification</span></label>
                                            <input type="text" name="qualification" class="form-control" placeholder="Qualification" ng-init="educations[$index].qualification = education.qualification" ng-model="educations[$index].qualification" >
                                            <input type="hidden" name="id" class="form-control"  ng-init="educations[$index].id = education.id" ng-model="educations[$index].id">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group form-group-default">
                                            <label class="label"><span>College</span></label>
                                            <input type="text" name="college" class="form-control" placeholder="College" ng-init="educations[$index].college = education.college" ng-model="educations[$index].college" >
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group form-group-default">
                                            <label class="label"><span>University</span></label>
                                            <input type="text" name="university" class="form-control" placeholder="University" ng-init="educations[$index].university = education.university" ng-model="educations[$index].university" >
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group form-group-default">
                                            <label class="label"><span>Passing Year</span></label>
                                            <input type="text" name="passing_year" class="form-control" placeholder="Passing Year" ng-init="educations[$index].passing_year = education.passing_year" ng-model="educations[$index].passing_year" >
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group form-group-default">
                                            <label class="label"><span>Percentage / Grade</span></label>
                                            <input type="text" name="percentage" class="form-control" placeholder="Percentage / Grade" ng-init="educations[$index].percentage = education.percentage" ng-model="educations[$index].percentage" >
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <a class="btn btn-md btn-close" tooltip="Delete" ng-if="!$first && !education.id" ng-click="removeEducationClone(education)">REMOVE</a>
                                            <a class="btn btn-md btn-close" tooltip="Delete" ng-if="education.id" ng-click="removeEducation(education);">REMOVE</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" ng-if="educations.length <5">
                                    <a class="btn btn-md btn-add" tooltip="Add" ng-click="newItem($event)">ADD</a>
                                </div>
                            </div>
                            <!-- tab 3 close -->
                            <!-- tab 4 start -->
                            <div class="tab-pane" id="tab_4">
                                <div class="row row-border" ng-repeat="experience in experiences">
                                    <legend > Experience {%$index+1%} </legend>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span>Company Name</span></label>
                                            <input type="text" name="company_name" class="form-control" placeholder="Company Name" ng-init="experiences[$index].company_name = experience.company_name" ng-model="experiences[$index].company_name">
                                            <input type="hidden" name="id" ng-init="experiences[$index].id = experience.id" ng-model="experiences[$index].id">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span>From</span></label>
                                            <div class="datepicker" date-format="dd-MM-yyyy" selector="form-control">
                                                <div class="custom-datepicker input-group">
                                                    <input type="text" name="form" class="form-control" placeholder="Pick a date"  datepicker-toggle=true id="people_from{%$index%}" ng-init="experiences[$index].from = experience.from" ng-model="experiences[$index].from" id="from{%$index%}" readonly>
                                                    {{-- <span class="input-group-addon"><i class="fa fa-calendar"></i></span> --}}
                                                    <label class="input-group-addon" for="people_from{%$index%}">
                                                     <span class="fa fa-calendar"></span>
                                                 </label>   

                                             </div>
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="label"><span>To</span></label>
                                        <div class="datepicker" date-format="dd-MM-yyyy" selector="form-control">
                                            <div class="custom-datepicker input-group">
                                                <input type="text" name="to" class="form-control" placeholder="Pick a date" id="people_to{%$index%}" ng-init="experiences[$index].to = experience.to" ng-model="experiences[$index].to" readonly>
                                                {{-- <span class="input-group-addon"><i class="fa fa-calendar"></i></span> --}}
                                                <label class="input-group-addon" for="people_to{%$index%}">
                                                 <span class="fa fa-calendar"></span>
                                             </label> 
                                         </div>
                                     </div>
                                 </div>
                             </div>
                             <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="label"><span>Salary</span></label>
                                    <input type="text" name="salary" class="form-control" placeholder="Salary" ng-init="experiences[$index].salary = experience.salary" ng-model="experiences[$index].salary" >
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="label"><span>Reason </span></label>
                                    <input type="text" name="reason" class="form-control" placeholder="Reason for leaving previous job" ng-init="experiences[$index].reason = experience.reason" ng-model="experiences[$index].reason" >
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <a class="btn btn-md btn-close" tooltip="Delete" ng-if="!$first && !experience.id" ng-click="removeExperienceClone(experience);">REMOVE</a>
                                    <a class="btn btn-md btn-close" tooltip="Delete" ng-if="experience.id" ng-click="removeExperience(experience.id, $index);">REMOVE</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" ng-if="experiences.length <5 || experiences.length ==0" >
                            <a class="btn btn-md btn-add" tooltip="Add" ng-click="nextItem($event)">ADD</a>
                        </div>
                    </div>
                    <!-- tab 4 close -->
                    <!-- tab 5 start -->
                    <div class="tab-pane" id="tab_5">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="label"><span>PAN Number</span></label>
                                    <input type="text" name="pan_number" class="form-control" placeholder="Pan Number" ng-model='people_array.pan_number' >
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="label"><span>Department</span></label>
                                    <select class="form-control full-width" name='department_id' id='dept_id' ng-model='people_array.department_id'>
                                        <option disabled selected value="">--Select Department -- </option>
                                        <option ng-repeat="dept in departments |orderBy:'name'" value="{%dept.id%}" ng-selected="edit==true && people_array.department_id == dept.id">{%dept.name%}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="label"><span>Designation/Job Title</span></label>
                                    <select class="form-control full-width" name='designation_id' id='desg_id' ng-model='people_array.designation_id'>
                                        <option disabled selected value=""> --Select Designation -- </option>
                                        <option ng-repeat="desg in designations |orderBy:'name'" value="{%desg.id%}">{%desg.name%}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="label date-txt"><span>Joining Date</span></label>
                                    <div class="datepicker" date-format="dd-MM-yyyy" selector="form-control">
                                        <div class="input-group">
                                            <input type="text"  name="join_date" class="form-control" placeholder="Pick a date" id="joining-date" ng-model='people_array.join_date' readonly>
                                            <label class="input-group-addon" for="joining-date">
                                             <span class="fa fa-calendar"></span>
                                         </label> 
                                                {{--     <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span>Management Level</span></label>
                                            <select class="form-control full-width" name='management_level' ng-model='people_array.management_level'>
                                                <option disabled selected value=""> --Select Management Level -- </option>
                                                <option value="M1">M1</option>
                                                <option value="M2">M2</option>
                                                <option value="M3">M3</option>
                                                <option value="M4">M4</option>
                                                <option value="M5">M5</option>
                                                <option value="M6">M6</option>
                                                <option value="M7">M7</option>
                                                <option value="M8">M8</option>
                                                <option value="M9">M9</option>
                                                <option value="M10">M10</option>
                                                <option value="M11">M11</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- tab 5 close -->
                            <!-- tab 6 start -->
                            <div class="tab-pane" id="tab_6">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span class="icon google"><i class="fa fa-google"></i></span><span>Google</span></label>
                                            <input type="url" name="google" class="form-control" placeholder="Google" ng-model='people_array.google' >
                                            <span class="error" ng-show="submitted && people.google.$error.url">Not valid url!</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span class="icon facebook"><i class="fa fa-facebook"></i></span><span>Facebook</span></label>
                                            <input type="url" name="facebook" class="form-control" placeholder="Facebook" ng-model='people_array.facebook' >
                                            <span class="error" ng-show="submitted && people.facebook.$error.url">Not valid url!</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span class="icon globe"><i class="fa fa-globe"></i></span><span>Web Site</span></label>
                                            <input type="url" name="website" class="form-control" placeholder="Web Site" ng-model='people_array.website' >
                                            <span class="error" ng-show="submitted && people.website.$error.url">Not valid url!</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span class="icon linkedin"><i class="fa fa-linkedin"></i></span><span>Linkedin</span></label>
                                            <input type="url" name="linkedin" class="form-control" placeholder="Linkedin" ng-model='people_array.linkedin' >
                                            <span class="error" ng-show="submitted && people.linkedin.$error.url">Not valid url!</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span class="icon skype"><i class="fa fa-skype"></i></span><span>Skype</span></label>
                                            <input type="text" name="skype" class="form-control" placeholder="Skype" ng-model='people_array.skype' >
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="label"><span class="icon twitter"><i class="fa fa-twitter"></i></span><span>Twitter</span></label>
                                            <input type="url" name="twitter" class="form-control" placeholder="Twitter" ng-model='people_array.twitter' >
                                            <span class="error" ng-show="submitted && people.twitter.$error.url">Not valid url!</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- tab 6 close -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="add-app" type="button" class="btn btn-md btn-add" ng-click="submit(people)">{%modal_title%}</button>
                        <button type="button" class="btn btn-md btn-close" id="close"  ng-click="clearAll(people)">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END MODAL STICK UP  -->
    <!-- view_user_profile start modal -->
    <div class="modal fade view_user_profile" id="view_user_profile" keyboard=true>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header clearfix">
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> --}}
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" ><i class="fa fa-close"></i></button>
                    <h4>Profile Detail</h4>
                </div>
                <div class="modal-body">
                    <div class="user_profile_detail">
                        <ul class="nav nav-tabs my-tabs1">
                            <li class="active" id='default-detail-home'><a href="#detail_1"  data-toggle="tab">Personal</a></li>
                            @if(Auth::user()->roles=='admin')
                            <li><a href="#detail_2" data-toggle="tab">Address</a></li>
                            <li><a href="#detail_3" data-toggle="tab">Education</a></li>
                            <li><a href="#detail_4" data-toggle="tab">Experience</a></li>
                            <li><a href="#detail_6" data-toggle="tab">Employment</a></li>
                            <li><a href="#detail_5" data-toggle="tab">Social</a></li>
                            <li><a href="#detail_7" data-toggle="tab">Current Projects</a></li>
                            <li><a href="#detail_8" data-toggle="tab">Completed Projects</a></li>
                            @endif
                        </ul>
                        <div class="mCustomScrollbar" data-mcs-theme="minimal-dark" data-height="240">
                            <div class="tab-content form-horizontal">
                                <div class="tab-pane active" id="detail_1" >
                                    <div class="pic-name">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div ng-cloak class="avtar inline" ng-if="user_profile_detail.photo == null">
                                                    <div class="img avatar-sm">
                                                        <img ng-src={!! asset("img/user.png") !!} />
                                                        <div class="admin-key" ng-if="user_profile_detail.user.roles=='admin'">
                                                            <i class="fa fa-key"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div ng-cloak class="avtar inline" ng-if="user_profile_detail.photo != null">
                                                    <div class="img avatar-sm">
                                                        <img ng-src={!! asset("uploads/people-thumb/{%user_profile_detail.photo%}") !!} />
                                                        <div class="admin-key" ng-if="user_profile_detail.user.roles=='admin'">
                                                            <i class="fa fa-key"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="name">
                                                    <div ng-if="user_profile_detail.lname != null">
                                                        {% (user_profile_detail.fname |uppercase)+' '+user_profile_detail.lname |uppercase%}
                                                    </div>
                                                    <div ng-if="user_profile_detail.lname == null">
                                                        {% (user_profile_detail.fname |uppercase)%}
                                                    </div>
                                                    <span class="designation"> {% (user_profile_detail.designation_id?user_profile_detail.designation.name:'')%}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="nav nav-pills">
                                        <li>
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                    <label class="label"><span>E-mail:</span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                    <span class="text"> {% user_profile_detail.user.email %}</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                    <label class="label"><span>Gender:</span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                    <span class="text">
                                                        {% user_profile_detail.gender|ucfirst%}
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                    <label class="label"><span>Birthdate:</span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                    <span class="text"> {% user_profile_detail.dob %}</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                    <label class="label"><span>Marital Status:</span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                    <span class="text">
                                                        {% user_profile_detail.marital_status%}
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                    <label class="label"><span>Mobile Number:</span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                    <span class="text">
                                                        {% user_profile_detail.mobile %}
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                    <label class="label"><span>Phone Number:</span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                    <span class="text">
                                                        {% user_profile_detail.phone %}
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                @if(Auth::user()->roles=='admin')
                                <div class="tab-pane" id="detail_2">
                                    <ul class="nav nav-pills">
                                        <li>
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                    <label class="label"><span>Address:</span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                    <span class="text">
                                                        {% (user_profile_detail.adrs1?user_profile_detail.adrs1+', ':'')+(user_profile_detail.adrs2?user_profile_detail.adrs2:'')%}
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                    <label class="label"><span>City:</span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                    <span class="text">
                                                        {% user_profile_detail.city?user_profile_detail.city:'-'%}
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                    <label class="label"><span>Zipcode:</span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                    <span class="text">
                                                        {% user_profile_detail.zipcode?user_profile_detail.zipcode:'-' %}
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                    <label class="label"><span>State:</span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                    <span class="text">
                                                        {% user_profile_detail.state?user_profile_detail.state:'-'%}
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                    <label class="label"><span>Country:</span></label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                    <span class="text">
                                                        {% user_profile_detail.country?user_profile_detail.country:'-'%}
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-pane" id="detail_3" ng-init='i=0'>
                                    <fieldset ng-repeat='user_education in user_educations track by $index' >
                                        <legend > Education {%$index+1%} </legend>
                                        <div class="form-horizontal">
                                            <div class="row">
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"><div class="form-group"><label class="label"><span>Qualification</span></label></div></div>
                                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">{%user_education.qualification?user_education.qualification:'-'%}</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"><div class="form-group"><label class="label"><span>College</span></label></div></div>
                                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                    {%user_education.college?user_education.college:'-'%}</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"><div class="form-group"><label class="label"><span>University</span></label></div></div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">{%user_education.university?user_education.university:'-'%}</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"><div class="form-group"><label class="label"><span>Passing Year</span></label></div></div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">{%user_education.passing_year?user_education.passing_year:'-'%}</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"><div class="form-group"><label class="label"><span>Percentage / Grade</span></label></div></div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                        {%user_education.percentage?user_education.percentage:'-'%}
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <fieldset ng-show='user_educations.length == 0' >

                                            <div class="text-center">
                                                <div class="avtar">
                                                    <div class="img avatar-sm">
                                                        <img src="{!! asset('img/degree_icon.png') !!}" />
                                                    </div>
                                                </div>
                                                <p>Details does not exists</p>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="tab-pane" id="detail_4">
                                        <fieldset ng-repeat='user_experience in user_experiences track by $index' >
                                            <legend > Experience {%$index+1%} </legend>
                                            <div class="form-horizontal">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="label"><span>Company Name</span></label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                        {%user_experience.company_name?user_experience.company_name:'-'%}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="label"><span>Experience From: </span></label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                        {%user_experience.from?user_experience.from:'-'%}
                                                        <strong>To:</strong> &nbsp;{%user_experience.to?user_experience.to:'-'%}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="label"><span>Salary:</span></label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                        {%user_experience.salary?user_experience.salary:'-'%}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="label" data-toggle="tooltip" data-placement="bottom" title="Reason for Leaving previous job"><span>Reason :</span></label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                        {%user_experience.reason?user_experience.reason:'-'%}
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <fieldset ng-show='user_educations.length == 0' >
                                            <div class="text-center">
                                                <div class="avtar">
                                                    <div class="img avatar-sm">
                                                        <img src="{!! asset('img/pro_icon.png') !!}" height="100px" width="100px" />
                                                    </div>
                                                </div>
                                                <p>Details does not exists</p>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="tab-pane" id="detail_5">
                                        <ul class="nav nav-pills">
                                            <li ng-show='user_profile_detail.facebook!=null'>
                                                <div class="row">
                                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                        <label class="label"><span class="icon google"><i class="fa fa-google"></i></span><span>Google:</span></label>
                                                    </div>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <span class="text" ng-if='user_profile_detail.google != null' >
                                                            <a href="{% user_profile_detail.google %}" target="_blank">
                                                                {% user_profile_detail.google %}</a>
                                                            </span>
                                                            <span class="text" ng-if="user_profile_detail.google == ''" >
                                                                -
                                                            </span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li >
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                            <label class="label"><span class="icon facebook"><i class="fa fa-facebook"></i></span><span>Facebook:</span></label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                            <span class="text" ng-if="user_profile_detail.facebook != null ">
                                                                <a href='{% user_profile_detail.facebook %}'  target="_blank">        {% user_profile_detail.facebook %}
                                                                </a>
                                                            </span>
                                                            <span class="text" ng-if="user_profile_detail.facebook == ''" >   -
                                                            </span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li >
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                            <label class="label"><span class="icon globe"><i class="fa fa-globe"></i></span><span>Web Site:</span></label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                            <span class="text" ng-if="user_profile_detail.website != null">
                                                                <a href="{% user_profile_detail.website %}" target="_blank">
                                                                    {% user_profile_detail.website %}
                                                                </a>
                                                            </span>
                                                            <span class="text" ng-if="user_profile_detail.website == ''">
                                                                -
                                                            </span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                            <label class="label"><span class="icon linkedin"><i class="fa fa-linkedin"></i></span><span>LinkedIn :</span></label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                            <span class="text" ng-if="user_profile_detail.linkedin != null">
                                                                <a href="{% user_profile_detail.linkedin %}" target="_blank">
                                                                    {% user_profile_detail.linkedin %}
                                                                </a>
                                                            </span>
                                                            <span class="text" ng-if="user_profile_detail.linkedin == ''">
                                                                -
                                                            </span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 sm-5 col-xs-12">
                                                            <label class="label"><span class="icon skype"><i class="fa fa-skype"></i></span><span>skype:</span></label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                            <span class="text" ng-if="user_profile_detail.skype != null">
                                                                <a href="{%user_profile_detail.skype%}" target="_blank">{%user_profile_detail.skype%}
                                                                </a>
                                                            </span>
                                                            <span class="text" ng-if="user_profile_detail.skype == ''">
                                                                -
                                                            </span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li >
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                            <label class="label"><span class="icon twitter"><i class="fa fa-twitter"></i></span><span>Twitter:</span></label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                            <span class="text" ng-if="user_profile_detail.twitter != null">
                                                                <a href="{% user_profile_detail.twitter %}" target="_blank">
                                                                    {% user_profile_detail.twitter %}
                                                                </a>
                                                            </span>
                                                            <span class="text" ng-if="user_profile_detail.twitter == ''">
                                                                -
                                                            </span>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="tab-pane" id="detail_6">
                                            <ul class="nav nav-pills">
                                                <li >
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                            <label class="label"><span>PAN Number:</span></label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                            <span class="text">
                                                                {% (user_profile_detail.pan_number?user_profile_detail.pan_number:'-')%}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li >
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                            <label class="label"><span>Designation/Job Title:</span></label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                            <span class="text">
                                                                {% (user_profile_detail.designation_id?user_profile_detail.designation.name:'-')%}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li >
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                            <label class="label"><span>Department:</span></label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                            <span class="text">
                                                                {% (user_profile_detail.department_id?user_profile_detail.department.name:'-')%}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li >
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                            <label class="label"><span>Joining Date:</span></label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                            <span class="text">
                                                                {% (user_profile_detail.join_date?user_profile_detail.join_date:'-')%}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li >
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                            <label class="label"><span>Management Level:</span></label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                            <span class="text">
                                                                {% (user_profile_detail.management_level!=0?user_profile_detail.management_level:'-')%}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="tab-pane" id="detail_7">
                                            <div class="current-task-list" ng-if='user_profile_detail.user.projects.length != 0'>
                                                <span>Current Projects:</span>
                                                <ul >
                                                    <li ng-repeat='user_project in
                                                    user_profile_detail.user.projects' >
                                                    <a href="projects/{%user_project.id%}/tasks"  ng-if="user_project.status == 'active'" target="_blank"><i class="fa fa-star-half-full"></i>{%user_project.name%}</a>
                                                </li>
                                            </ul>
                                            <div class="text-center" ng-if='user_profile_detail.user.projects.length == 0'>
                                                <div class="avtar inline">
                                                    <div class="img avatar-md">
                                                        <img src="{!! asset('img/noProjects.png') !!}" />
                                                    </div>
                                                </div>
                                                <p>No current projects</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="detail_8">
                                    {{--  <div class="head">
                                        <a href="#">7 Complete Projects</a>
                                    </div> --}}
                                    <div class="complete-task-list" ng-if=' user_profile_detail.user.projects.length != 0'>
                                        <span>Complete Projects:</span>
                                        <ul>
                                            <li ng-repeat='user_project in
                                            user_profile_detail.user.projects' >
                                            @if(Auth::user()->roles=='admin')

                                            <a href="projects/{%user_project.id%}/tasks"  ng-show="user_project.status == 'completed'" target="_blank">
                                                <i class="fa fa-star-half-full"></i>{%user_project.name%}
                                            </a>
                                            @else
                                            <i class="fa fa-star-half-full"></i>{%user_project.name%}
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                                <div class="text-center" ng-if='user_profile_detail.user.projects.length == 0'>
                                    <div class="avtar inline">
                                        <div class="img avatar-md">
                                            <img src="{!! asset('img/noProjects.png') !!}" />
                                        </div>
                                    </div>
                                    <p>No current projects</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-md btn-close" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- view_user_profile close modal -->
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('.selectpicker').selectpicker({
            style: 'btn-info',
            size: 4
        });
    });
    
</script>
@endsection
