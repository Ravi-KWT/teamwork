@extends('layouts.app')
@section('title','Project')
@section('content')
<div ng-controller="ProjectCtrl" ng-init="getProjects()">
    <div class="page-user-log ">
           @include('shared.user_login_detail')
    </div>
    @if(Auth::user()->roles == 'admin')
        <ul class="nav nav-tabs clearfix">
            <li class="active"><a href="{{url('projects')}}">Current Projects</a></li>
            <li ><a href="{!! route('projects-list',['archive'])!!}">Archived Projects</a></li>
            <li ><a href="{!! route('projects-list',['completed']) !!}">Completed Projects</a></li>
             <li><a href="{!! route('projects-list',['onhold']) !!}">On Hold Projects</a></li>
        </ul>
    @endif
     <div class="loader" ng-if="loading">Loading</div>
    <div class="container-fluid ">

        <div class="panel panel-transparent ng-cloak">
        
            <div class="panel-heading clearfix">
                <div class="row">
                    <div class="col-lg-6 col-xs-12">
                        <div class="project_search">
                            <div class="form-group">
                                <form method="get"  action="{!! route('search-projects') !!}">
                                    <input type="text" id="search-table" name="search_data" class="form-control" placeholder="Search">
                                    <button type="submit" name="search" class="search_btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xs-12">
                        <div class="search-bar">
                            <div class="input-group text-right">
                              @if(Auth::user()->roles == 'admin')
                                  <div class="" ng-if="companies.length !=0" >
                                      <button data-toggle="modal" data-target="#addNewAppModal" class="btn btn-md btn-default">Add Project</button>
                                  </div>
                              @endif 
                            </div>
                        </div>
                    </div>
                </div>
                <style type="text/css">
                    .search-bar{
                        float: right;
                        max-width: 400px;
                        width: 100%;
                    }
                    .search-bar .input-group{
                        width: 100%;
                    }
                    .search-bar .input-group-addon{
                        padding: 0;
                        background-color: transparent;
                        border: 0px;
                    }
                    .search-bar .form-control{
                        height: 40px;
                    }
                    .search-bar .input-group-addon button{
                        border-radius: 0px;
                    }
                    .project_listing li:hover .user-included{
                        display: block;
                    }
                    .user-included{
                        position: absolute;
                        right: 0;
                        bottom: 62px;
                        width: 175px;
                        max-height: 180px;
                        overflow: auto;
                        display: none;
                        z-index: 100;
                        background-color: #f1f1f1;
                        padding: 5px 10px;
                        text-align: center;
                        display: none !important;
                    }
                    .user-included .titles{
                        display: block;
                        text-align: center;
                        font-size: 12px;
                        padding: 0px 10px 2px;
                        color: #000;
                    }
                    .user-included span{
                        width: 30px;
                        height: 30px;
                        display: inline-block;
                        vertical-align: top;
                        background-color: #f1f1f1;
                        border-radius: 100px;
                        overflow: hidden;
                        border: 1px solid transparent;
                        margin: 0 3px 3px;
                        border: 1px solid #d1d1d1;
                    }
                    .user-included span img{
                        width: 30px;
                        height: 30px;
                        border:0px;
                    }
                </style>
                <div  class="col-md-12" ng-if="projects.length==0">
                    <div style="text-align:center;">
                        <img src="{!! asset('img/noProjects.png') !!}"  height="100px" width="100px" />
                        <h3>No Projects</h3>
                    </div>
                </div>
                </div>
                <div  class="panel-body">
                <div  ng-if='loading'>
                    Loading...........
                </div>
                @foreach($projects as $key => $project)
                    <div  class="panel-group" role="tablist" aria-multiselectable="true
                        ">
                        @foreach($companies as $company)
                            <div>
                                @if($company->id == $key)
                                    <div>
                                        <div  class="panel panel-gray" id="{!! $company->id !!}" class="accordion">
                                            <div  class="panel-heading clearfix" role="tab" id="headingOne" >
                                                <div class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#{!! $company->id !!}" href="#project{!! $company->id !!}" aria-expanded="true" aria-controls="collapseOne" class="{!! $project->count() == 0 ? 'collapsed' : '' !!}">
                                                        {!! $company->name !!}
                                                    </a>
                                                </div>
                                            </div>
                                            <div id="project{!! $company->id !!}" class="panel-collapse collapse {!! $project->count() == 0 ? '' : 'in' !!}" role="tabpanel"  aria-labelledby="headingOne" aria-expanded="false">

                                                <div class="panel-body">
                                                    @if($project->count() > 0)
                                                    <ul class="project_listing" >
                                                        @foreach($project as $p)
                                                        <li class="clearfix" >

                                                            <div  class="project_task_detail">
                                                                <i class="fa fa-star-half-full"></i>
                                                                <a href="{!!url('/projects/'.$p->id.'/tasks')!!}" class="project_name">{!! $p->name !!} - (kwt {!! $p->id !!})
                                                                @if($p->latest_logs)
                                                                    {!! dd($p->latest_logs) !!}
                                                                    <span class="update" >updated:{!! $p->latest_logs->updated_at->format('d/m/Y') !!}
                                                                    }
                                                    {{--          {% p.updated_at | parseDate | date:'dd/MM/yyyy @ h:mma'%}   --}}
                                                                    </span>
                                                                    @endif
                                                                    
                                                                    @if(!$p->latest_logs)
                                                                    <span class="update">updated:{!! $p->updated_at->format('d/m/Y') !!}
                                                        {{--          {% p.updated_at | parseDate | date:'dd/MM/yyyy @ h:mma'%}   --}}
                                                                    </span>
                                                                    @endif
                                                                </a>
                                                                
                                                            </div>
                                                            @if(Auth::user()->roles == "admin")
                                                            <div class="actions">
                                                                <a class="btn btn-md btn_edit" ng-click="editProject({!! $p->id !!})" ><i class="fa fa-pencil"></i></a>
                                                                <a class="btn btn-md btn_delete" ng-click="deleteProject({!! $p->id !!})" ><i class="fa fa-trash"></i></a>
                                                            </div>
                                                            @endif

                                                           {{--  <div class="user-included">
                                                                <div class="titles">Added People</div>
                                                                <span class="thumb" ng-repeat='pu in p.users' ng-if='pu.people.photo' title="{%pu.people.fname%}{%pu.people.lname?' '+pu.people.lname:''%}"><img src="/uploads/people/{%pu.people.photo%}" ></span>
                                                                <span class="thumb" ng-repeat='pu in p.users' ng-if="pu.people.photo==null" title="{%pu.people.fname%}{%pu.people.lname?' '+pu.people.lname:''%}"><img src="{!!asset('/uploads/noPhoto.png')!!}"></span>
                                                                {{-- <span class="thumb"><img src=""></span>
                                                                <span class="thumb"><img src=""></span> 
                                                            </div> --}}
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                    @endif
                                                    <div class="nodata text-center">
                                                        @if($project->count() < 0)
                                                        <span > No Projects</span>
                                                        @endif
                                                        @if(Auth::user()->roles == "admin")
                                                            <button ng-click="showModal($event)" type="button" class="btn btn-md btn-default"  id="{!! $company->id !!}" > <i class="fa fa-plus"></i> Add Project </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
   {{-- modal for project --}}
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
                            {{-- <li><a data-toggle="tab" href="#menu2">Features</a></li> --}}
                            <li><a data-toggle="tab" href="#menu3">Dates</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="home" class="tab-pane slide-left active">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="label"><span>name<em>*</em></span></label>
                                            <input id="name" name="name" type="text" class="form-control" placeholder="Name of Project" ng-model='project_array.name' required>
                                            <span class="error" ng-show="submitted && project.name.$error.required">* Please enter project name</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="label"><span>Description{{-- <em>*</em> --}}</span></label>
                                            <textarea id="description" name="description" type="text" class="form-control" placeholder="Description of Project" ng-model='project_array.description'></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="label" for='se11'><span>Client<em>*</em></span></label>
                                            <select class="form-control selcls" ng-model="client_id" id='sel1' name='client_id' required>
                                            <option  value>Select Client</option>
                                                @foreach($companies as $company1)
                                                <option value="{!! $company1->id !!}" ng-selected="{!! $company1->id !!} ==client_id" >{!! $company1->name !!}</option>
                                                @endforeach
                                            </select>
                                            <span class="error" ng-show="submitted && project.client_id.$error.required">* Please select client</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="label" for='se12'><span>Project Manager<em>*</em></span></label>
                                            <select class="form-control selcls" ng-model="projectlead_id" id='sel2' name='projectlead_id' required>
                                            <option  value>Select Project Manager</option>
                                                
                                                @foreach($pm_lists as $pm)
                                                    <option value="{!! $pm->id !!}" ng-selected="{!! $pm->id !!} ==projectlead_id" >{!! $pm->people->fname ." ". $pm->people->lname !!}</option>
                                                @endforeach
                                            </select>
                                            <span class="error" ng-show="submitted && project.client_id.$error.required">* Please select project manager</span>
                                        </div>
                                    </div>
                                </div>
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
                                                    <label for="per_hour">Time & Material</label> 
                                                    {{-- changed to Time & Materials --}}
                                                    {{-- <label for="per_hour">Per Hour</label> --}}
                                                </div>
                                                <div class="radio">
                                                    <input type="radio" ng-model='price_types' name='price_types' id="hiring" ng-value="'hiring'" ng-click="hideHours()">
                                                    <label for="hiring">Hiring</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 <div class="row" ng-if="price_types=='fix'">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                             <input class="form-control" type='text' ng-if="price_types=='fix'" id='fix_hours' name='fix_hours' placeholder="Hours" ng-model='project_array.fix_hours' ng-pattern="/^(0|[1-9][0-9]*)$/" required>
                                            <span class="error" ng-show="submitted && project.fix_hours.$error.required">* Please enter hours</span>
                                            <span class="error" ng-show="submitted && project.fix_hours.$error.pattern">Not valid hours!</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="menu1" class="tab-pane slide-left">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="label" for='pc'><span>Project Category<em>*</em></span></label>
                                            <select class="form-control selcls" ng-model="project_array.category_id"  id='pc'  name="category_id" required>
                                               <option value="" selected > Select Category </option>
                                               @foreach($projectsCategories as $projectCategory)
                                                <option  value="{!! $projectCategory->id !!}" ng-selected="projCategoryryId=={!! $projectCategory->id !!}">{!! $projectCategory->name !!}</option>
                                                @endforeach
                                            </select>
                                            <span class="error" ng-show="submitted && project.category_id.$error.required">* Please Select Project Category</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <span>
                                                <a class="btn btn-md btn-default" ng-click="showProjectCategory()">Add New Project Category</a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class='label'><span>Notes</span></label>
                                            <textarea id="notes" name="notes" type="text" class="form-control" placeholder="Notes of Project" ng-model='project_array.notes'></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- this section merge with Project detail Section --}}
                            {{-- <div id="menu2" class="tab-pane slide-left">
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
                            </div> --}}
                            <div id="menu3" class="tab-pane slide-left">
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <label class="label"><span>Start Date</span></label>
                                        <div class="datepicker" date-format="yyyy-MM-dd" date-max-limit="{% project_array.end_date %}" selector="form-control">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" name="start_date" class="form-control" placeholder="Pick a date" id="start-date" ng-model='project_array.start_date' readonly >
                                                        <label class="input-group-addon" for="start-date">
                                                           <span class="fa fa-calendar"></span>
                                                        </label> 
                                                {{--     <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <label class="label"><span>End Date</span></label>
                                        <div class="datepicker" date-format="yyyy-MM-dd" date-min-limit="{% project_array.start_date %}" selector="form-control">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" name="end_date" class="form-control" placeholder="Pick a date" id="end-date" ng-model='project_array.end_date' readonly>
                                                    <label class="input-group-addon" for="end-date">
                                                           <span class="fa fa-calendar"></span>
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
 <div class="modal fade stick-up" id="addProjectCategory" tabindex="-1" keyboard=true  role="dialog" aria-labelledby="addProjectCategory" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header clearfix">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" ng-click="clearAll()"><i class="fa fa-close "></i></button>
                    <h4>Add New Project Category</h4>
                </div>
                <form name='projectCategory'  ng-submit="submitProjectCategory(projectCategory)" class='form' role='form' novalidate>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="label"><span>name</span></label>
                                    <input id="appName" type="text" name="name"  class="form-control" placeholder="Name of Category" ng-model='project_category.name' required>
                                    <span class="error" ng-show="submittedCategory && projectCategory.name.$error.required">* Please enter project category</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="add-app" type="button" class="btn btn-md btn-add" ng-click="submitProjectCategory(projectCategory)" ng-bind="edit==false ? 'Add' : 'Edit'"></button>
                        <button type="button" class="btn btn-md btn-close" id="close" ng-click="clearProjectCategory(projectCategory)">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script >
    // $(document).ready(function(){
    //     $(document).on('keyup','#search-table',function(e){
    //         e.preventDefault();
    //         var search_data = $(this).val();
    //         $.post('{!! route('search-projects') !!}',{search_data:search_data, "_token": $('meta[name="csrf-token"]').attr('content')}, function(response){
    //                console.log(response);         
    //             // var options = '';
    //             // options += '<option value="">All</option>';

    //             // if (response != '') {
    //             //     $.each(response, function(key,value){
    //             //         options += '<option value="'+key+'">'+ value +'</option>';
    //             //     }) 

    //             //     $('#project_id').html(options);
    //             // }else{

    //             //     $('#project_id ').html(options);
    //             // }
                
                
    //         })
    //     });
    // });
</script>
@endsection
