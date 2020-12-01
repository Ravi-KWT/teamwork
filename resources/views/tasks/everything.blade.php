@extends('layouts.app')
@section('title','Everything')
@section('content')
    <div class="page-user-log ">
        @include('shared.user_login_detail')
    </div>
    <div class="container-fluid">
        <ul class="breadcrumb" ng-cloak>
            <li><a href="{!!url('/')!!}"><span><i class="fa fa-home"></i></span></a></li>

            
            <li class="active"><span>Everything</span></li>
        </ul>
        <div class="panel panel-transparent">
            <div class="panel-heading clearfix">
                <div class="panel-title">Filter Now</div>
            </div>
            <div class="panel-body">
                 @include('shared.session')  
                  <div class="filtter clearfix">
                    <div class="container-fluid">
                        <div class="row">

                            <form name='searchEverythingTask' action="{!!route('searchEverything')!!}" method="get" class='form' role='form')
                           
                            >
                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                    <div class="form-inline">
                                       @if(Auth::user()->roles=='admin' || Auth::user()->is_teamlead==true || Auth::user()->is_projectlead==true)
                                        <div class="form-group" ng-cloak>
                                            <label class="label"><span>Users</span></label>
                                             {!! Former::select("user_id","")->options($users )!!}
                                        </div>
                                        @endif
                                        <div class="form-group">
                                            <div class="form-group" >
                                                <label class="label"><span>Date Range</span></label>
                                                <div class="input-group datepicker" date-set="
                                                @if(isset($start_date))
                                                   {!! $start_date !!}
                                                @else

                                                    {!!Carbon\Carbon::now()->subdays(2)!!}
                                                @endif"
                                                date-format="yyyy-MM-dd" date-max-limit="{% searchForm.end_date %}" selector="form-control"
                                                    >
                                                    <input type="text" name="start_date" class="form-control" placeholder="Pick a start date" id="searchForm-start-date" ng-model="searchForm.start_date " readonly>
                                                    <label class="input-group-addon" for="searchForm-start-date">
                                                        <i class="fa fa-calendar"></i>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                               <i class="fa fa-arrows-h"></i>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group datepicker" date-set="
                                                @if(isset($end_date))
                                                   {!! $end_date !!}
                                                @else
                                                    {!!Carbon\Carbon::now()!!}
                                                @endif" date-format="yyyy-MM-dd" date-min-limit="{% searchForm.start_date %}" >
                                                    <input type="text"  name="end_date" class="form-control" placeholder="Pick a end date" id="searchForm-end-date" ng-model = 'searchForm.end_date' readonly>
                                                    <label class="input-group-addon" for="searchForm-end-date">
                                                        <i class="fa fa-calendar"></i>
                                                    </label>
                                                   {{--  <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span> --}}
                                                </div>
                                            </div>
                                        </div>

                                     {{--    <div class="form-group">
                                            <div class="form-group">
                                                <label class="label"><span>Date Range</span></label>
                                                <input type="text" class="form-control" name="daterange" value="" />
                                                @php
                                                    $st = Carbon\Carbon::now()->subdays(2);
                                                    $ed = Carbon\Carbon::now()->format('Y-m-d');
                                                    $start_date = isset($start_date)?$start_date:$st;

                                                    $end_date = isset($end_date)?$end_date:$ed;
                                                @endphp
                                                    {!!Former::hidden('start_date')->id('start_date')->value($start_date)!!}
                                                    {!!Former::hidden('end_date')->id('end_date1')->value($end_date)!!}
                                            </div>
                                        </div>
                              --}}
                                       {{-- <div class="form-group">
                                       
                                           <label class="label"><span>Project Category</span></label>
                                           {!! Former::select("project_category_id","")->options($project_category_lists)!!}
                                       </div> --}}
                                        <div class="form-group">
                                        
                                            <label class="label"><span>Projects</span></label>
                                            {!! Former::select("project_id","")->options($projectsList)!!}
                                        </div>

                                       @if(Auth::user()->roles=='admin' )
                                           @if(Auth::user()->is_teamlead == false)
                                               <div class="form-group">
                                                   <label class="label"><span>Departments</span></label>
                                                   {!! Former::select("department_id","")->options($departmentList)!!}
                                               </div>
                                           @endif
                                            <div class="form-group">
                                                <label class="label"><span>Company/Client</span></label>
                                                {!! Former::select("client_id","")->options($companyList)->setAttributeData('size','2')!!}
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <label class="label"><span>Log Type</span></label>
                                            {!! Former::select("billable","")->options([''=>'All Logs','true'=>'Billable','false'=>'Non-Billable'] )!!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <ul class="list-inline">
                                        <li>
                                        <div class="form-group">
                                            <label class="label">&nbsp;</label>
                                            <button type="submit" class="btn btn-fltr btn-md btn-default">FILTER</button>
                                        </div>
                                        </li>
                                        @if($l!=0)
                                            <li>
                                                <div class="form-group">
                                                    <label class="label">&nbsp;</label>
                                                    <div class="dropdown drop-arrow rightside padd">
                                                        <button class="btn btn-md btn-default" type="button" id="export" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                              EXPORT
                                                             <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="export">
                                                            <li>
                                                                <button  class="btn-block btn btn-sm btn-default btn-report" type="submit" name="excel" value="Excel">Excel</button>
                                                            </li>
                                                            @if(Auth::user()->roles=='admin')
                                                                <li>
                                                                    <button  class="btn-block btn btn-sm btn-default btn-report" type="submit" name="pdf" value="PDF" >PDF</button>
                                                                </li>
                                                                <li>
                                                                    <button  class="btn-block btn btn-sm btn-default btn-report" type="submit" name="pdfProjectsReport" value="pdfProjectsReport">PDF Client Projects</button>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
               @if(count($logs))
            <div class="panel-footer">
                <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                        <div class="filter-total" ng-cloak>
                            <div class="hours-count" ng-cloak >
                                <label>Filtered Totals:</label>
                                <div class="logged-hours"  ng-cloak>
                                    <strong>Logged:</strong>
                                        
                                        {!!floor($totalLoggedHours/60)."
                                Hours ".($totalLoggedHours%60)." Minutes ( "
                                  . number_format($totalLoggedHours/60,2)!!} )
                                </div>
                                <div class="billable-hours"  ng-cloak>
                                    <strong>Billable:</strong>
                                        {!!floor($totalBillableHours/60)."
                                Hours ".($totalBillableHours%60)." Minutes ( "
                                  . number_format($totalBillableHours/60,2)!!} )
                                </div>
                                <div class="non-billable-hours"  ng-cloak>
                                    <strong>Non-billable:</strong>
                                      {!!Floor($totalNonBillableHours/60)."
                                Hours ".($totalNonBillableHours%60)." Minutes ( "
                                  . number_format($totalNonBillableHours/60,2)!!} )
                                </div>

                            </div>
                        </div>
                    </div>
                   {{-- for export to excel --}}
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                    </div>
                    {{--End--}}
                </div>
            </div>
            @endif
        </div>
        <div class="panel panel-transparent">
            <div class="panel-heading clearfix">
                <div class="panel-title">All Logs</div>
                <div class="action">
                    {{--<div class="cols" ng-show="everythingLogs.length > 0" ng-cloak>
                        <input ng-model="q" type="text" id="search-table" class="form-control" placeholder="Search" ng-cloak>
                    </div>
                    <div class="cols" ng-show='everythingLogs.length > 0'>
                        <form action="{!! url('/exportTask') !!}" method="GET">
                            <button id="export-button" class="btn btn-default">Export</button>
                        </form>
                    </div> --}}
                </div>
            </div>
            @if(Auth::user()->roles=='admin')
            <div class="panel-body" ng-cloak>
                 {{-- @include('shared.session') --}}
                <div ng-cloak   class="loader" ng-if="loading"></div>

                    @forelse($logAllUserList as $key => $logUser)

                        <?php
                            $dateWiseLoggedHours = 0;
                            $dateWiseBillableHours = 0;
                            $dateWiseNonBillableHours = 0;
                        ?>
                        <div class="everything-date">
                            <h2><a href="{{url('/people',$key)}}">{!!$logUser!!}</a></h2>
                        </div>
                        <table  class="table table-striped example vc dataAdmin" data-paging="false" data-searching="false" data-info="false">
                            <thead>
                                <th class="text-left">Project Name</th>
                                <th>Date</th> 
                             
                                <th>Description</th>
                                <th>Task list</th>
                                <th>start Time</th>
                                <th>End Time</th>
                                <th>Billable</th>
                                <th>Hours</th>
                                {{-- <th class="text-right">Action</th> --}}
                            </thead>
                                    <tbody >
                                     @foreach($logs as $everythingLog)
                                        @if($everythingLog->user->people->fname.($everythingLog->user->people->lname?" ".$everythingLog->user->people->lname:'')==$logUser)
                                        <tr class="text-left"  >
                                            <td>
                                                <a href="{!!url('/projects'),'/',$everythingLog->task->project->id,'/tasks'!!}">{!! $everythingLog->task->project->name!!}</a>
                                            </td>
                                           
                                            <td >
                                                <span style="display: none;">{!! $everythingLog->date? \Carbon\Carbon::parse($everythingLog->date)->format('Ymd'):'-' !!} </span>
                                                {!! $everythingLog->date? \Carbon\Carbon::parse($everythingLog->date)->format('d-m-Y'):'-' !!} 
                                                {{-- {!! $everythingLog->user->people->name?$everythingLog->user->people->name: '-' !!} --}}
                                            </td>
                                           
                                            <td>
                                                <div>
                                                    <div class="task">
                                                        Task: <a href="{!!url('/projects'),'/',$everythingLog->project_id,'/tasks','/',$everythingLog->task_id !!}">{!! $everythingLog->task->name !!}</a>
                                                    </div>
                                                    <div class="task-discription ellipsisH" title="{!! $everythingLog->description !!}" data-toggle="tooltip" data-placement="bottom">
                                                        {!! $everythingLog->description !!}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {!! $everythingLog->task->category->name ? $everythingLog->task->category->name : '-' !!}
                                            </td>
                                            <td>
                                                {!! $everythingLog->start_time ? $everythingLog->start_time : '-' !!}
                                            </td>
                                            <td>
                                                {!! $everythingLog->end_time ? $everythingLog->end_time : '-' !!}
                                            </td>

                                            <td >
                                                @if($everythingLog->billable)
                                                    <span class="billable" style="cursor: default;">
                                                        <i class="fa fa-check"></i>
                                                    </span>
                                                @else
                                                    <span class="nobillable" style="cursor: default;">
                                                        <i class="fa fa-close"></i>
                                                    </span>

                                                @endif
                                            </td>

                                            <td ng-cloak>
                                                {!!$everythingLog->hour ? $everythingLog->hour : '-' !!}
                                            </td>
                                       {{--  <td class="text-right" ng-cloak>
                                            <a class="btn btn-md btn_edit" ng-click="editLog(log.id)"><i class="fa fa-pencil"></i></a>
                                        </td> --}}
                                        </tr>
                                         <?php
                                            if($everythingLog->billable == 'true'){
                                                $dateWiseBillableHours +=  $everythingLog->minute;
                                            }
                                            else {
                                                $dateWiseNonBillableHours +=  $everythingLog->minute;
                                            }
                                        ?>
                                         @endif
                                @endforeach
                                    </tbody>
                            
                        </table>
                        <div class="filter-total text-right everything">
                                <strong>Logged:</strong> {!!floor(($dateWiseBillableHours+$dateWiseNonBillableHours)/60)."
                                    Hours ".(($dateWiseBillableHours+$dateWiseNonBillableHours)%60)." Minutes ( "
                                      . number_format(($dateWiseBillableHours+$dateWiseNonBillableHours)/60,2)!!} )
                                      
                                <strong>Billable:</strong>  {!!floor($dateWiseBillableHours/60)."
                                    Hours ".($dateWiseBillableHours%60)." Minutes ( "
                                      . number_format($dateWiseBillableHours/60,2)!!} )

                                <strong>Non-billable:</strong> {!!floor($dateWiseNonBillableHours/60)."
                                    Hours ".($dateWiseNonBillableHours%60)." Minutes ( "
                                      . number_format($dateWiseNonBillableHours/60,2)!!} )
                            </div>
                       
                        @empty
                            <div ng-cloak class="col-md-12">
                                <div class="no-record-found">
                                    <h3>No record found</h3>
                                </div>
                            </div>
                        @endforelse

                    </div>

            </div>
            @elseif(Auth::user()->roles == 'employee' && Auth::user()->is_projectlead == true)
            <div class="panel-body" ng-cloak>
                 {{-- @include('shared.session') --}}
                <div ng-cloak   class="loader" ng-if="loading"></div>

                    @forelse($logAllUserList as $key => $logUser)
                        
                        <?php
                            $dateWiseLoggedHours = 0;
                            $dateWiseBillableHours = 0;
                            $dateWiseNonBillableHours = 0;
                        ?>
                        <div class="everything-date">
                            <h2><a href="{{url('/people',$key)}}">{!!$logUser!!}</a></h2>
                        </div>
                        <table  class="table table-striped example vc dataAdmin" data-paging="false" data-searching="false" data-info="false">
                            <thead>
                                <th class="text-left">Project Name</th>
                                <th>Date</th> 
                             
                                <th>Description</th>
                                <th>Task list</th>
                                <th>start Time</th>
                                <th>End Time</th>
                                <th>Billable</th>
                                <th>Hours</th>
                                {{-- <th class="text-right">Action</th> --}}
                            </thead>
                                    <tbody >
                                     @foreach($logs as $everythingLog)
                                @if($everythingLog->user->people->fname.($everythingLog->user->people->lname?" ".$everythingLog->user->people->lname:'')==$logUser)
                                        <tr class="text-left"  >
                                            <td>
                                                <a href="{!!url('/projects'),'/',$everythingLog->task->project->id,'/tasks'!!}">{!! $everythingLog->project->name!!}</a>
                                            </td>
                                           
                                            <td >
                                                <span style="display: none;">{!! $everythingLog->date? \Carbon\Carbon::parse($everythingLog->date)->format('Ymd'):'-' !!} </span>
                                                {!! $everythingLog->date? \Carbon\Carbon::parse($everythingLog->date)->format('d-m-Y'):'-' !!} 
                                                {{-- {!! $everythingLog->user->people->name?$everythingLog->user->people->name: '-' !!} --}}
                                            </td>
                                           
                                            <td>
                                                <div>
                                                    <div class="task">
                                                        Task: <a href="{!!url('/projects'),'/',$everythingLog->project_id,'/tasks','/',$everythingLog->task_id !!}">{!! $everythingLog->task->name !!}</a>
                                                    </div>
                                                    <div class="task-discription ellipsisH" title="{!! $everythingLog->description !!}" data-toggle="tooltip" data-placement="bottom">
                                                        {!! $everythingLog->description !!}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {!! $everythingLog->task->category->name ? $everythingLog->task->category->name : '-' !!}
                                            </td>
                                            <td>
                                                {!! $everythingLog->start_time ? $everythingLog->start_time : '-' !!}
                                            </td>
                                            <td>
                                                {!! $everythingLog->end_time ? $everythingLog->end_time : '-' !!}
                                            </td>

                                            <td >
                                                @if($everythingLog->billable)
                                                    <span class="billable" style="cursor: default;">
                                                        <i class="fa fa-check"></i>
                                                    </span>
                                                @else
                                                    <span class="nobillable" style="cursor: default;">
                                                        <i class="fa fa-close"></i>
                                                    </span>

                                                @endif
                                            </td>

                                            <td ng-cloak>
                                                {!!$everythingLog->hour ? $everythingLog->hour : '-' !!}
                                            </td>
                                       {{--  <td class="text-right" ng-cloak>
                                            <a class="btn btn-md btn_edit" ng-click="editLog(log.id)"><i class="fa fa-pencil"></i></a>
                                        </td> --}}
                                        </tr>
                                         <?php
                                            if($everythingLog->billable == 'true'){
                                                $dateWiseBillableHours +=  $everythingLog->minute;
                                            }
                                            else {
                                                $dateWiseNonBillableHours +=  $everythingLog->minute;
                                            }
                                        ?>
                                         @endif
                                @endforeach
                                    </tbody>
                            
                        </table>
                        <div class="filter-total text-right everything">
                                <strong>Logged:</strong> {!!floor(($dateWiseBillableHours+$dateWiseNonBillableHours)/60)."
                                    Hours ".(($dateWiseBillableHours+$dateWiseNonBillableHours)%60)." Minutes ( "
                                      . number_format(($dateWiseBillableHours+$dateWiseNonBillableHours)/60,2)!!} )
                                      
                                <strong>Billable:</strong>  {!!floor($dateWiseBillableHours/60)."
                                    Hours ".($dateWiseBillableHours%60)." Minutes ( "
                                      . number_format($dateWiseBillableHours/60,2)!!} )

                                <strong>Non-billable:</strong> {!!floor($dateWiseNonBillableHours/60)."
                                    Hours ".($dateWiseNonBillableHours%60)." Minutes ( "
                                      . number_format($dateWiseNonBillableHours/60,2)!!} )
                            </div>
                       
                        @empty
                            <div ng-cloak class="col-md-12">
                                <div class="no-record-found">
                                    <h3>No record found</h3>
                                </div>
                            </div>
                        @endforelse

                    </div>

            </div>
            @elseif(Auth::user()->is_teamlead == true)
            <div class="panel-body" ng-cloak>
                 {{-- @include('shared.session') --}}
                <div ng-cloak   class="loader" ng-if="loading"></div>

                    @forelse($logAllUserList as $key => $logUser)

                        <?php
                            $dateWiseLoggedHours = 0;
                            $dateWiseBillableHours = 0;
                            $dateWiseNonBillableHours = 0;
                        ?>
                        <div class="everything-date">
                            <h2><a href="{{url('/people',$key)}}">{!!$logUser!!}</a></h2>
                        </div>
                        <table  class="table table-striped example vc dataAdmin" data-paging="false" data-searching="false" data-info="false">
                            <thead>
                                <th class="text-left">Project Name</th>
                                <th>Date</th> 
                             
                                <th>Description</th>
                                <th>Task list</th>
                                <th>start Time</th>
                                <th>End Time</th>
                                <th>Billable</th>
                                <th>Hours</th>
                                {{-- <th class="text-right">Action</th> --}}
                            </thead>
                                    <tbody >
                                     @foreach($logs as $everythingLog)
                                @if($everythingLog->user->people->fname.($everythingLog->user->people->lname?" ".$everythingLog->user->people->lname:'')==$logUser)
                                        <tr class="text-left"  >
                                            <td>
                                                <a href="{!!url('/projects'),'/',$everythingLog->task->project->id,'/tasks'!!}">{!! $everythingLog->project->name!!}</a>
                                            </td>
                                           
                                            <td >
                                                <span style="display: none;">{!! $everythingLog->date? \Carbon\Carbon::parse($everythingLog->date)->format('Ymd'):'-' !!} </span>
                                                {!! $everythingLog->date? \Carbon\Carbon::parse($everythingLog->date)->format('d-m-Y'):'-' !!} 
                                                {{-- {!! $everythingLog->user->people->name?$everythingLog->user->people->name: '-' !!} --}}
                                            </td>
                                           
                                            <td>
                                                <div>
                                                    <div class="task">
                                                        Task: <a href="{!!url('/projects'),'/',$everythingLog->project_id,'/tasks','/',$everythingLog->task_id !!}">{!! $everythingLog->task->name !!}</a>
                                                    </div>
                                                    <div class="task-discription ellipsisH" title="{!! $everythingLog->description !!}" data-toggle="tooltip" data-placement="bottom">
                                                        {!! $everythingLog->description !!}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {!! $everythingLog->task->category->name ? $everythingLog->task->category->name : '-' !!}
                                            </td>
                                            <td>
                                                {!! $everythingLog->start_time ? $everythingLog->start_time : '-' !!}
                                            </td>
                                            <td>
                                                {!! $everythingLog->end_time ? $everythingLog->end_time : '-' !!}
                                            </td>

                                            <td >
                                                @if($everythingLog->billable)
                                                    <span class="billable" style="cursor: default;">
                                                        <i class="fa fa-check"></i>
                                                    </span>
                                                @else
                                                    <span class="nobillable" style="cursor: default;">
                                                        <i class="fa fa-close"></i>
                                                    </span>

                                                @endif
                                            </td>

                                            <td ng-cloak>
                                                {!!$everythingLog->hour ? $everythingLog->hour : '-' !!}
                                            </td>
                                       {{--  <td class="text-right" ng-cloak>
                                            <a class="btn btn-md btn_edit" ng-click="editLog(log.id)"><i class="fa fa-pencil"></i></a>
                                        </td> --}}
                                        </tr>
                                         <?php
                                            if($everythingLog->billable == 'true'){
                                                $dateWiseBillableHours +=  $everythingLog->minute;
                                            }
                                            else {
                                                $dateWiseNonBillableHours +=  $everythingLog->minute;
                                            }
                                        ?>
                                         @endif
                                @endforeach
                                    </tbody>
                            
                        </table>
                        <div class="filter-total text-right everything">
                                <strong>Logged:</strong> {!!floor(($dateWiseBillableHours+$dateWiseNonBillableHours)/60)."
                                    Hours ".(($dateWiseBillableHours+$dateWiseNonBillableHours)%60)." Minutes ( "
                                      . number_format(($dateWiseBillableHours+$dateWiseNonBillableHours)/60,2)!!} )
                                      
                                <strong>Billable:</strong>  {!!floor($dateWiseBillableHours/60)."
                                    Hours ".($dateWiseBillableHours%60)." Minutes ( "
                                      . number_format($dateWiseBillableHours/60,2)!!} )

                                <strong>Non-billable:</strong> {!!floor($dateWiseNonBillableHours/60)."
                                    Hours ".($dateWiseNonBillableHours%60)." Minutes ( "
                                      . number_format($dateWiseNonBillableHours/60,2)!!} )
                            </div>
                       
                        @empty
                            <div ng-cloak class="col-md-12">
                                <div class="no-record-found">
                                    <h3>No record found</h3>
                                </div>
                            </div>
                        @endforelse

                    </div>

            </div>
            @else
             <div class="panel-body" ng-cloak>
                <div ng-cloak   class="loader" ng-if="loading"></div>
                @include('shared.session')
                    @forelse($logDates as $logDateUnique)
                        <?php
                            $dateWiseLoggedHours = 0;
                            $dateWiseBillableHours = 0;
                            $dateWiseNonBillableHours = 0;
                        ?>
                        <div class="everything-date">
                                {{-- <h2>{!!$logUser!!}</h2> --}}
                            <h2>{!!\Carbon\Carbon::parse($logDateUnique)->format('l, d F')!!}</h2>
                        </div>
                        <table  class="table table-striped example vc dataAdmin dataTable" {{-- id="dataUser" --}} data-paging="false" data-searching="false" data-info="false" >
                            <thead>
                                <th class="text-left">Project Name</th>
                                <th>Description</th>
                                <th>Task list</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Billable</th>
                                <th>Hours</th>
                                {{-- <th class="text-right">Action</th> --}}
                            </thead>
                                <tbody >
                                     @foreach($logs as $everythingLog)
                                      @if($everythingLog->date == $logDateUnique)
                                        <tr class="text-left">
                                            <td>
                                                <a href="{!!url('/projects'),'/',$everythingLog->project_id,'/tasks'!!}">{!! $everythingLog->task->project->name!!}</a>
                                            </td>
                                            <td>
                                    
                                                    <div class="task">
                                                        Task: <a href="{!!url('/projects'),'/',$everythingLog->project_id,'/tasks','/',$everythingLog->task_id !!}">{!! $everythingLog->task->name !!}</a>
                                                    </div>
                                                    <div class="task-discription ellipsisH" title="{!! $everythingLog->description !!}" data-toggle="tooltip" data-placement="bottom">
                                                        {!! $everythingLog->description !!}
                                                    </div>
                                   
                                            </td>
                                            <td>
                                                {!! $everythingLog->task->category->name ? $everythingLog->task->category->name : '-' !!}
                                            </td>
                                            <td>
                                                {!! $everythingLog->start_time ? $everythingLog->start_time : '-' !!}
                                            </td>
                                            <td>
                                                {!! $everythingLog->end_time ? $everythingLog->end_time : '-' !!}
                                            </td>

                                            <td >
                                                @if($everythingLog->billable)
                                                    <span class="billable">
                                                        <i class="fa fa-check"></i>
                                                    </span>
                                                @else
                                                    <span class="nobillable">
                                                        <i class="fa fa-close"></i>
                                                    </span>

                                                @endif
                                            </td>

                                            <td ng-cloak>
                                                {!!$everythingLog->hour ? $everythingLog->hour : '-' !!}
                                            </td>
                                       {{--  <td class="text-right" ng-cloak>
                                            <a class="btn btn-md btn_edit" ng-click="editLog(log.id)"><i class="fa fa-pencil"></i></a>
                                        </td> --}}
                                        </tr>
                                         <?php
                                            if($everythingLog->billable == 'true'){
                                                $dateWiseBillableHours +=  $everythingLog->minute;
                                            }
                                            else {
                                                $dateWiseNonBillableHours +=  $everythingLog->minute;
                                            }
                                        ?>
                                         @endif
                                @endforeach
                                    </tbody>
                            
                        </table>
                        <div class="filter-total text-right everything">
                                <strong>Logged:</strong> {!!floor(($dateWiseBillableHours+$dateWiseNonBillableHours)/60)."
                                    Hours ".(($dateWiseBillableHours+$dateWiseNonBillableHours)%60)." Minutes ( "
                                      . number_format(($dateWiseBillableHours+$dateWiseNonBillableHours)/60,2)!!} )
                                      
                                <strong>Billable:</strong>  {!!floor($dateWiseBillableHours/60)."
                                    Hours ".($dateWiseBillableHours%60)." Minutes ( "
                                      . number_format($dateWiseBillableHours/60,2)!!} )

                                <strong>Non-billable:</strong> {!!floor($dateWiseNonBillableHours/60)."
                                    Hours ".($dateWiseNonBillableHours%60)." Minutes ( "
                                      . number_format($dateWiseNonBillableHours/60,2)!!} )
                            </div>
                       
                        @empty
                            <div ng-cloak class="col-md-12">
                                <div class="no-record-found">
                                    <h3>No record found</h3>
                                </div>
                            </div>
                        @endforelse

                    </div>
                @endif
            </div>

        </div>
    </div>
    <div class="modal fade stick-up" id="logTimeModal" data-keyboard=false data-backdrop='static' tabindex="-1" role="dialog" aria-labelledby="logTimeModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header clearfix">
                    <button type="button" class="close" ng-click="logCancel()" data-dismiss="modal" aria-hidden="true"> <i class="fa fa-close"></i></button>
                    <h4>Log time on this task</h4>
                </div>
                <form name="Logtime" ng-submit="submit(Logtime)" class='form' role='form' novalidate>
                    <div class="modal-body">
                        <div class="tab-content">
                            {{--<div class=''>
                                <label>Task : </label>
                                <span> {%taskDetail[0].name %} </span>
                            </div>
                            <div class="clearfix"></div>
                            <div>
                                <label>Logged Hours :</label>
                                <span> {%secondsToTime(total_task_minute*60 ).h  +' hrs '+secondsToTime(total_task_minute*60).m+' mins ('+total_task_hours +')'%}</span>
                            </div> --}}
                            <div class="tab-pane slide-left active" id="loghome">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="label"><span>User</span></label>
                                        <div class="form-group">
                                            <input type="text"  value='{!!Auth::user()->people->fname.' '. Auth::user()->people->lname!!}' readonly class="form-control"></input>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            {{--<label class="date-txt">Date</label> --}}
                                            <label class="label"><span>Date</span></label>
                                            <div class="datepicker log-date-picker" date-format="yyyy-MM-dd" selector="form-control">
                                                <div class="input-group">
                                                    <input type="text" name="date" class="form-control" placeholder="Pick a date" id="log-date" ng-model='logtime.date' required >
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                </div>
                                                <span class="error" ng-show="submitted && Logtime.date.$error.required">* Please select date</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div><div uib-timepicker ng-model="mytime" ng-change="changed()" hour-step="hstep" minute-step="mstep" show-meridian="ismeridian"></div></div>
                                            <label class="label"><span>Start Time</span></label>
                                            <div class="input-group bootstrap-timepicker">
                                                <input id="timepicker_1" type="text" name="start_time" class="form-control" ng-model="logtime.start_time" placeholder="hh:mm:AM" required>
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                            </div>
                                            <span class="error" ng-show="submitted && Logtime.start_time.$error.required">* Please select start time</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="label"><span>End Time</span></label>
                                            <div class="input-group bootstrap-timepicker">
                                                <input id="timepicker_2" type="text" name="end_time" class="form-control" ng-model="logtime.end_time" placeholder="hh:mm:AM" min-time="{%logtime.start_time%}"  required>
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                            </div>
                                            <span class="error" ng-show="submitted && Logtime.end_time.$error.required">* Please select start time</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="label"><span>Spent Time </span></label>
                                            <input  type="text"  class="form-control"  placeholder="hh" value="{% calc_spent_time(logtime.end_time,logtime.start_time)  %}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="label"><span>Description</span></label>
                                            <textarea id="description" name="description" type="text" class="form-control" placeholder="Description of Log Time" ng-model='logtime.description' required>
                                            </textarea>
                                            <span class="error" ng-show="submitted && Logtime.description.$error.required">* Please enter log description</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="checkbox check-success">
                                            <input type="checkbox" name="billable" ng-model="logtime.billable" id="k">
                                            <label for="k">Billable</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="add-app" type="button" class="btn btn-md btn-default" ng-click="submitLog(Logtime)" ng-bind="edit==false ? 'Add' : 'Edit'" ng-disabled="calc_spent_time(logtime.end_time,logtime.start_time)=='0 min'"></button>
                        <button type="button" class="btn btn-md btn-default" id="close"  ng-click="logClearAll(Logtime)">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')


<script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.15/sorting/date-dd-MMM-yyyy.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript">
    $(document).ready(function() {
       $('.example').dataTable( {
            "order": [[ 1, "desc" ]]
        });
       // //  var startD =  moment($('#start_date').val(), 'DD-MM-YYYY').toDate();;
       //  var endD   = moment($('#end_date1').val(), 'DD-MM-YYYY').toDate();
       //  alert(endD);
       // $('input[name="daterange"]').daterangepicker({
       //      startDate: startD,
       //      endDate: endD,
       //      opens: 'left'

       //    }, function(start,end, label) {
       //          $('#start_date').val(start.format('DD-MM-YYYY'));
       //          $('#end_date1').val(end.format('DD-MM-YYYY'));
       //    });
       $(document).on('click','.btn-report',function(){
            $(this).parents('form').attr('target','_blank');
            // $(this).parents('form').submit();
       });
        $(document).on('click','.btn-fltr',function(){
            $(this).parents('form').removeAttr('target');
            $(this).parents('form').submit();
       });
    });

    
</script>

<script type="text/javascript">
    // $(document).ready(function(){
    //     $(document).on('change','#project_category_id',function(e){
    //         e.preventDefault();
    //         var project_category_id = $(this).val();
    //         $.post('{!! route('get-projects') !!}',{project_category_id:project_category_id, "_token": "{{ csrf_token() }}"}, function(response){
                
    //             var options = '';
    //             options += '<option value="">All</option>';

    //             if (response != '') {
    //                 $.each(response, function(key,value){
    //                     options += '<option value="'+key+'">'+ value +'</option>';
    //                 }) 

    //                 $('#project_id').html(options);
    //             }else{

    //                 $('#project_id ').html(options);
    //             }
                
                
    //         })
    //     })
    // })
</script>

@endsection