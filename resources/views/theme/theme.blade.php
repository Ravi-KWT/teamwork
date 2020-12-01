@extends('layouts.app')
@section('title','Theme')
@section('content')
<div ng-controller="ThemeCtrl" ng-cloak>
    <div class="container-fluid">
        <div class="thmepage">
            <div class="theme-tab">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#theme_1" data-toggle="tab">Brand</a></li>
                    <li><a href="#theme_2" data-toggle="tab">Social Media</a></li>
                    <li><a href="#theme_3" data-toggle="tab">Custom</a></li>
                    <li><a href="#theme_4" data-toggle="tab">Image</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="theme_1">
                        <ul class="theme_list">
                            <li>
                                <a href="javascript:;" class="color-btn" ng-click="changeTheme('default')">
                                    <span class="color bgcolor" data-color="#e36f45"></span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;" class="color-btn" ng-click="changeTheme('green')">
                                    <span class="color bgcolor" data-color="#009093"></span>
                                </a>
                            </li>
                             <li>
                                <a href="javascript:;" class="color-btn" ng-click="changeTheme('red')">
                                    <span class="color bgcolor" data-color="#9e1b1f"></span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;" class="color-btn" ng-click="changeTheme('yellow')">
                                    <span class="color bgcolor" data-color="#f2c217"></span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;" class="color-btn" ng-click="changeTheme('blue')">
                                    <span class="color bgcolor" data-color="#04a5cf"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="theme_2">
                        <ul class="theme_list">
                            <li>
                                <a href="javascript:;" class="color-btn" ng-click="changeTheme('facebook')">
                                    <span class="color bgcolor" data-color="#3b5998"></span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;" class="color-btn" ng-click="changeTheme('twitter')">
                                    <span class="color bgcolor" data-color="#00aced"></span>
                                </a>
                            </li>
                             <li>
                                <a href="javascript:;" class="color-btn" ng-click="changeTheme('linkedin')">
                                    <span class="color bgcolor" data-color="#007bb6"></span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;" class="color-btn" ng-click="changeTheme('google')">
                                    <span class="color bgcolor" data-color="#dd4b39"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="theme_3">
                        <ul class="theme_list">
                            <li>
                                <a href="javascript:;" class="color-btn" ng-click="changeTheme('black')">
                                    <span class="color bgcolor" data-color="#000000"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="theme_4">
                        <ul class="theme_list">
                            <li>
                                <h2>Comming Soon</h2>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="theme-body">
                <div class="theme_structure">
                    <div class="header">Header</div>
                    <div class="page-header">Page Title</div>
                    <div class="body clearfix">
                        <div class="page-sidebar"></div>
                        <div class="content-part">
                            <div class="panel panel-transparent">
                                <div class="panel-heading clearfix">
                                    <div class="panel-title">Panel heading without title</div>
                                    <div class="action">
                                        <div class="cols">
                                            <div class="dropdown drop-arrow">
                                              <button class="btn btn-md btn-default" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                Dropdown
                                                <span class="caret"></span>
                                              </button>
                                              <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                <li><a href="#">Action</a></li>
                                                <li><a href="#">Another action</a></li>
                                                <li><a href="#">Something else here</a></li>
                                                <li role="separator" class="divider"></li>
                                                <li><a href="#">Separated link</a></li>
                                              </ul>
                                            </div>
                                        </div>
                                        <div class="cols">
                                            <button data-toggle="modal" data-target="#modal" class="btn btn-md btn-default"><i class="fa fa-plus"></i> Add Project Category</button>
                                        </div>
                                    </div>
                                </div>
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#demo_tab_1" data-toggle="tab">Tab 1</a></li>
                                    <li><a href="#demo_tab_2" data-toggle="tab">Tab 2</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="demo_tab_1">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</div>
                                    <div class="tab-pane" id="demo_tab_2">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</div>
                                </div>
                                <div class="panel-body">
                                    <div class="panel panel-gray">
                                        <div class="panel-heading"><div class="panel-title">Panel heading without title</div></div>
                                        <div class="panel-body">
                                            <div class="dataTables_wrapper">
                                            <table class="dataTable table vc table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>First Name</th>
                                                        <th>Last Name</th>
                                                        <th>Username</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Mark</td>
                                                        <td>Otto</td>
                                                        <td>Otto</td>
                                                        <td>Username</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Mark</td>
                                                        <td>Otto</td>
                                                        <td>Otto</td>
                                                        <td>Username</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Mark</td>
                                                        <td>Otto</td>
                                                        <td>Otto</td>
                                                        <td>Username</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Mark</td>
                                                        <td>Otto</td>
                                                        <td>Otto</td>
                                                        <td>Username</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Mark</td>
                                                        <td>Otto</td>
                                                        <td>Otto</td>
                                                        <td>Username</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                        <div class="panel-footer">Panel footer</div>
                                    </div>
                                </div>
                                <div class="panel-footer">Panel footer</div>
                            </div>
                        </div>
                    </div>
                    <div class="footer">Footer</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="fa fa-close"></span></button>
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body">
        <p>One fine body</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-md btn-add">Save changes</button>
        <button type="button" class="btn btn-md btn-close" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection