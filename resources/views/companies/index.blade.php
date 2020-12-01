@extends('layouts.app')
@section('title','Companies')
@section('content')
<div ng-controller="companyCtrl" >
    <div class="page-user-log ">
        @include('shared.user_login_detail')
    </div>
	<div class="container-fluid">
		<ul class="breadcrumb" ng-cloak>
            <li><a href="{!!url('/')!!}"><span><i class="fa fa-home"></i></span></a></li>
    	    <li class="active"><span>Company</span></li>
		</ul>
		<div class="panel panel-transparent">
			<div class="panel-heading clearfix">
				<div class="panel-title">Company Listing</div>
				@if(Auth::user()->roles == "admin")
					<div class="action" ng-cloak>
						<div class="cols" ng-cloak>
							<button  data-target="#addNewAppModal" data-toggle="modal" class="btn btn-md btn-default"><i class="fa fa-plus"></i> Add Company</button>
						</div>
					</div>
				@endif
			</div>
			<div class="panel-body">
				<div class="loader" ng-if="loading"></div>
				<table datatable="ng" dt-options="dtOptions" dt-column-defs="dtColumnDefs" class="table vc table-striped" ng-if='companies.length>0' ng-cloak>
					<thead>
						<tr>
							<th class="text-left">Logo</th>
							<th width="180">Industry</th>
							<th width="180">Name</th>
							<th width="180">Website</th>
							<th width="180">Email</th>
							{{-- <th>Phone</th> --}}
							<th width="200px" class="text-right">Action</th>
						</tr>
					</thead>
					<tbody ng-cloak>
						<tr ng-repeat="company in companies" ng-cloak>
							<td  class="text-left" ng-if='company.logo==null' ng-cloak>
								<div class="avtar">
									<div class="img avatar-sm">
										<img ng-src={!! asset("img/noCompany.png") !!}  />
									</div>
								</div>
							</td>
							<td class="text-left" ng-if='company.logo!=null' ng-cloak>
								<div class="avtar">
									<div class="img avatar-sm">
										<img ng-src={!! asset("/uploads/company/{%company.logo%}") !!} />
									</div>
								</div>
							</td>
							<td ng-cloak>{% company.industry.name ? company.industry.name : '-' %}</td> 
							<td ng-cloak>{% company.name ? company.name : '-' %}</td>
							
							<td ng-cloak><a href="{% company.website ? company.website : '-'  %}" target="_blank">{% company.website ? company.website : '-'  %}</a></td>
							<td ng-cloak><a href="mailto:{%company.email%}">{%company.email ? company.email : '-' %}</a></td>
							{{-- <td ng-cloak>{% company.phone ? company.phone : '-' %}</td> --}}
							<td ng-cloak  class="text-right">
							  	<a class="btn btn-md btn_view" ng-click="viewCompany(company.id)" ><i class="fa fa-eye"></i></a>
                                @if(Auth::user()->roles == "admin")
    								<a class="btn btn-md btn_edit" ng-click="editCompany(company.id)" ><i class="fa fa-edit"></i></a>
    								
    								<a class="btn btn-md btn_delete" ng-click="deleteCompany(company.id)" ><i class="fa fa-trash"></i></a>
                                @endif
                              
							</td>
    						</tr>
					</tbody>
					<tfoot>
    					<tr>
    						<th class='no-filter'></th>
    						<th class=''>Industry</th>
    						<th class=''>Name</th>
    						<th class=''>Website</th>
    						<th class=''>Email</th>
    						{{-- <th class=''>Phone</th> --}}
 							<th class='no-filter'></th>
    					</tr>
					</tfoot>
				</table>
				<div ng-cloak class="col-md-12" ng-if="companies.length==0">
					<div style="text-align:center;">
						<img src="{!! asset('img/noCompany.png') !!}"  height="100px" width="100px" />
						<h3>No records</h3>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="cdetail" tabindex="-1" role="dialog" aria-labelledby="cdetail" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content ">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" ng-click="cancelAll()" aria-hidden="true"><i class="fa fa-close"></i>
					</button>
					<h4>Company Detail</h4>
				</div>
				<div class="modal-body">
					<div class="panel panel-transparent">
						<div class="panel-heading">
							<div class='form-group' ng-show="company_detail[0].logo == false">
                                <div class="avtar">
                                    <div class="img avatar-lg">
                                        <img  src="/img/noCompany.png"  />
                                    </div>
                                </div>
                            </div>
							<div class="form-group" ng-show='company_detail[0].logo'>
                                <div class="avtar">
                                    <div class="img avatar-lg">
                                        <img src="/uploads/company/{%company_detail[0].logo%}"/>
                                    </div>
                                </div>
                            </div>
						</div>
						<div ng-cloak class="panel-body" id="company_form">
							<div class="form">
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div class="form-group">
											<label class="label"><span>Name:</span></label>
											<div class="view_input" ng-cloak>{% company_detail[0].name ? company_detail[0].name : '-' %}</div>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div class="form-group">
											<label class="label"><span>Industry: </span></label>
											<div class="view_input" ng-cloak>{% company_detail[0].industry.name ? company_detail[0].industry.name: '-' %}</a></div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div class="form-group">
											<label class="label"><span>Website: </span></label>
											<div class="view_input" ng-cloak><a href="{%company_detail[0].website%}" target="_blank">{% company_detail[0].website ? company_detail[0].website : '-' %}</a></div>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div class="form-group">
											<label class="label"><span>Email: </span></label>
											<div class="view_input" ng-cloak><a href="mailto:{% company_detail[0].email ? company_detail[0].email: '-' %}">{% company_detail[0].email ? company_detail[0].email: '-' %}</a></div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div class="form-group">
											<label class="label"><span>Phone: </span></label>
											<div class="view_input" ng-cloak>{% company_detail[0].phone ? company_detail[0].phone : '-' %}</div>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div class="form-group">
											<label class="label"><span>Fax: </span></label>
											<div class="view_input" ng-cloak>{% company_detail[0].fax ? company_detail[0].fax : '-' %}</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div class="form-group">
											<label class="label"><span>Address: </span></label>
											<div class="view_input" ng-cloak>{% company_detail[0].adrs1 ?company_detail[0].adrs1:  '-' %}</div>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div class="form-group">
											<label class="label"><span>City: </span></label>
											<div class="view_input" ng-cloak>{% company_detail[0].city ? company_detail[0].city : '-'%}</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div class="form-group">
											<label class="label"><span>State: </span></label>
											<div class="view_input" ng-cloak>{% company_detail[0].state ? company_detail[0].state:'-' %}</div>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div class="form-group">
											<label class="label"><span>Country: </span></label>
											<div class="view_input" ng-cloak>{% company_detail[0].country ? company_detail[0].country : '-' %}</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div class="form-group">
											<label class="label"><span>Zipcode: </span></label>
											<div class="view_input" ng-cloak>{% company_detail[0].zipcode ? company_detail[0].zipcode : "-" %} </div>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">&nbsp;</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-md btn-default" id="close" ng-click="closecompany()">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="addNewAppModal"  tabindex="-1" role="dialog" aria-labelledby="addNewAppModal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header clearfix">
					<button type="button" class="close" ng-click="cancelAll()" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i>
					</button>
					<h4>{%modal_title%} Company</h4>
				</div>
				<div class="alert alert-danger" ng-show="formError > 0"><span><center>Please provide required information in all tab.</center></span></div>
				<form name="Companies" ng-submit="submit(Companies)" class='form country' role='form' novalidate>
					<div class="modal-body my-tabs">
						<ul class="nav nav-tabs nav-tabs-fillup ">
							<li class="active" id='default-home'><a data-toggle="tab" id='home1' href="#home">Description</a></li>
							<li><a data-toggle="tab" href="#menu1">Industry</a></li>
							<li><a data-toggle="tab" href="#menu2">Address</a></li>
						</ul>
						<div class="tab-content">
							<div id="home" class="tab-pane slide-left active">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<label class="label"><span>name<em>*</em></span></label>
											<input id="appName" type="text" name="name" class="form-control"
											placeholder="Name of Company" ng-model='company.name' required>
											<span class="error"
											ng-show="submitted && Companies.name.$error.required">* Please enter company name</span>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12" >
										<div class="form-group" id="hidesiteurl">
											<label class="label"><span>Website<em>*</em></span></label>
											<input id="website" name="website" type="text" class="form-control" placeholder="Website URL" ng-model='company.website' ng-pattern='/^((?:http|ftp)s?:\/\/)(?:(?:[A-Z0-9](?:[A-Z0-9-]{0,61}[A-Z0-9])?\.)+(?:[A-Z]{2,6}\.?|[A-Z0-9-]{2,}\.?)|localhost|\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})(?::\d+)?(?:\/?|[\/?]\S+)$/i' required>
											<span class="error" ng-show="submitted && Companies.website.$error.required">* Please enter website</span>
											<span class="error" ng-show="Companies.website.$error.pattern">* Please enter valid website url</span>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<label class="label"><span>Email<em>*</em></span></label>
											{{-- <input type="text" name="email" class="form-control" placeholder="Email" ng-model='company.email'  ng-pattern="/^[_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/" required> --}}
											<input type="email" name="email" class="form-control" placeholder="Email" ng-model='company.email' required> 
											<span class="error" ng-show="submitted && Companies.email.$error.required">* Please enter Email </span>
											<span class="error" ng-show="submitted && Companies.email.$error.pattern">* Please enter valid email</span>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="fileUpload">
											<div id="filelist"></div>
											<div id="preview">
												<div class="avtar inline">
													<div class="img avatar-md"><img src="{!! asset('img/noCompany.png')!!}" id="noimage"></div>
												</div>
											</div>
											{{-- <div id="progressbar"></div> --}}
											<div id="container" >
												<span class="upload btn btn-md btn-upload" id="pickfiles">Upload</span>
											</div>
											<input type="hidden" name='logo' id="logo" ng-modal='company.logo'>
										</div>
									</div>
								</div>
							</div>
							<div id="menu1" class="tab-pane slide-left">
								<div class="row" >
									<div class="col-sm-12">
										<div class="form-group">
											<label class="label"><span>Industry<em>*</em></span></label>
											<select class="form-control selcls industry-select" ng-model='company.industry_id' name="industry_id" id='sel1' required>
												<option value="" selected="selected">Select Industry</option>
												<option value={%industry.id%} ng-repeat='industry in industries' ng-selected="industyId==industry.id">{%industry.name%}</option>
											</select>
											<span class="error" ng-show="submitted && Companies.industry_id.$error.required">*please select industry</span>
										</div>
									</div>
									{{-- <div class="col-sm-12">
										<div class="form-group">
											<label for='se11'>Industry*</label></label>
											<select class="form-control selcls" ng-model="company.industry_id" id='sel1' name='industry_id' required >
												<option value="" selected="selected">Select Industry</option>
												@foreach($industries as $industry)
												<option value="{!! $industry->id !!}">{!! $industry->name !!}</option>
												@endforeach
											</select>
											<span class="error" ng-show="submitted && Companies.industry_id.$error.required">* Please select Industry. </span>
										</div>
									</div> --}}
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<label class="label"><span>Phone</span></label>
											<input id="appName" type="text" name="phone" class="form-control"
											placeholder="Phone" ng-model='company.phone' ng-pattern="/^([0-9][0-9]*)$/">
											<span class="error" ng-show="submitted && Companies.phone.$error.pattern">Not valid number!</span>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<label class="label"><span>Fax</span></label>
											<input id="appName" type="text" name="mobile" class="form-control"
											placeholder="Fax" ng-model='company.fax'>
										</div>
									</div>
								</div>
							</div>
							<div id="menu2" class="tab-pane slide-left">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="label"><span>Address 1</span></label>
											<textarea id="appName"  rows="5" type="text" name="adrs1" class="form-control"
											placeholder="Address 1" ng-model='company.adrs1'> </textarea>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="label"><span>Address 2</span></label>
											<textarea id="appName"  rows="5" type="text" name="adrs2" class="form-control"
											placeholder="Address 2" ng-model='company.adrs2'> </textarea>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="label"><span>City</span></label>
											<input id="appName" name="city" type="text" class="form-control"
											placeholder="City" ng-model='company.city'>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="label"><span>Zipcode</span></label>
											<input id="appName" name="zipcode" type="text" class="form-control"
											placeholder="Zipcode" ng-model='company.zipcode' ng-pattern="/^(0|[1-9][0-9]*)$/">
											<span class="error" ng-show="submitted && Companies.zipcode.$error.pattern">Not valid zipcode!</span>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="label"><span>State</span></label>
											<input id="appName" name="state" type="text" class="form-control"
											placeholder="State" ng-model='company.state'>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="label"><span>Select Country<em>*</em></span></label>
											<select class="form-control selcls" ng-model='company.country' name="country" id='sel1' required>
												<option value="" selected>Select Country</option>
												<option value={%country.id%} ng-repeat='country in countries'>{%country.name%}</option>
											</select>
											<span class="error" ng-show="submitted && Companies.country.$error.required">*please select country</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button id="add-app" type="button" class="btn btn-md btn-add"
						ng-click="submit(Companies)">{%modal_title%}</button>
						<button type="button" class="btn btn-md btn-close" id="close" ng-click='clearAll(Companies)'>Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection