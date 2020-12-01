@extends('layouts.app')
@section('title','Project')
@section('content')
<div>
    <div class="page-user-log ">
           @include('shared.user_login_detail')
    </div>
    
     <div class="loader" ng-if="loading">Loading</div>
    <div class="container-fluid ">

        <div class="panel panel-transparent ng-cloak">
        
            <div class="panel-heading clearfix">
                <div class="row">
                    <div class="col-lg-6 col-xs-12">
                        <div class="panel-title">&nbsp;</div>
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
                {{-- <div  class="col-md-12">
                    <div style="text-align:center;">
                        <img src="{!! asset('img/noProjects.png') !!}"  height="100px" width="100px" />
                        <h3>No Projects</h3>
                    </div>
                </div> --}}
                </div>
                <div  class="panel-body">
                <div  ng-if='loading'>
                    Loading...........
                </div>
                <div  class="panel-group" role="tablist" aria-multiselectable="true
                    ">
                    <table  class="table table-striped">
                        <thead>
                            <th>Name</th>
                            <th>Key Account Manager</th>
                        </thead>
                        <tbody>
                            @foreach($projects as $key => $project)
                                <tr>
                                    <td>{!! $project->name !!}</td>
                                    <td>{!! $project->projectManager['fname'] !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
