@extends('layouts.app')
@section('title','People')
@section('content')
<style type="text/css">
    .people-text-middle{
        vertical-align: middle !important;
    }
    .birthday-table{
        width: 200px;
    }
    .dataTables_wrapper th span:before{
        width: 0;
        border:0; 
    }
</style>

    <div class="page-user-log ">
           @include('shared.user_login_detail')
    </div>
    <div class="container-fluid">
    	<div class="clearfix"></div>
    	<div class="panel panel-transparent">
    		<div class="panel-heading clearfix">
    			<div class="panel-title">Birthday Buddies</div>
    		</div>
    		<div class="panel-body">
    		<div ng-cloak class="loader" ng-if="loading"></div>
    			<div class="people-table">

    				<table  class="table vc table-striped user-log-dt" ng-cloak>
    					<thead>
    						<tr role='row'>
    							<th width="80px" class="datatable-nosort">Photo</th>
    							<th class='datatable-nosort'>Name</th>
    							<th class="sorting">Birthdate</th>
                                <th class='datatable-nosort'>Today's Birthday</th>
    						</tr>
    					</thead>
    					<tbody>
                            @foreach($users as $user)
                                <tr >
                                <td class="v-align-middle">
                                    <div class="datas people_id_pic">
                                        <a href="/people/{{$user->people->id}}">
                                            <div ng-cloak class="avtar" >
                                                <div class="img avatar-sm">
                                                    <img ng-src="{!! asset($user->people->photo_url('thumb')) !!}"/>
                                                </div>
                                                
                                            </div>
                                        </a>
                                        
                                    </td>
                                    <td  class="people-text-middle">
                                        <a href="/people/{{$user->people->id}}">
                                            {{$user->people->fname}} {{$user->people->lname}}</a>
                                    </td>
                                    <td class="people-text-middle">

                                         <span style="display: none;">{!! $user->people->dob? \Carbon\Carbon::parse($user->people->dob)->format('md'):'-' !!} </span>
                                       {{$user->people->dob}}
                                    </td>
                                    <td class="people-text-middle">
                                        @if(\Carbon\Carbon::parse($user->people->dob)->format('d')==date('d'))
                                            <div class="birthday-table">
                                                <img src="{{asset('img/birthday.gif')}}" >
                                            </div>
                                             
                                        @endif
                                    </td>
                                </tr>
                            
                            @endforeach
    						
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

@endsection
@section('scripts')
<script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.16/sorting/date-uk.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
    <script type="text/javascript">
    $(document).ready(function(){
        var isSorted = true;
        $('.user-log-dt').DataTable({
            responsive: true,
           "ordering":isSorted, 

            columnDefs: [
               { type: 'date-uk', targets: 1 },
               {
                 targets: "datatable-nosort",
                  orderable: false,
                },
            ],
            "order": [[ 2, "asc" ]],
            oLanguage: {
                oPaginate: {
                    sNext: '<span class="pagination-fa"><i class="fa fa-chevron-right" ></i></span>',
                    sPrevious: '<span class="pagination-fa"><i class="fa fa-chevron-left" ></i></span>'
                }
            }
        });
    });
</script>
@endsection