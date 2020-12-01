<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Company;
use App\Project;
use App\Industry;
use Illuminate\Support\Facades\Input;
use Image;
use App\Country;

// use Former\Facades\Former;
class CompaniesController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $industries = Industry::all();
        $countries = Country::all();
        return view('companies.index',compact('industries','countries'));   
    }
    
    public function getCompanies()
    {
       $companies = Company::with('projects','industry')->get();
       $countries=Country::all();
        $industries = Industry::all();
       return response()->json(['companies'=>$companies,'countries'=>$countries,'industries'=>$industries]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $companies = new Company;
        $companies->name = $request->get('name');
        $companies->logo = $request->get('logo');
        $companies->website = $request->get('website');
        $companies->email = $request->get('email');
        $companies->industry_id = $request->get('industry_id');
        $companies->phone = $request->get('phone');
        $companies->fax = $request->get('fax');
        $companies->adrs1 = $request->get('adrs1');
        $companies->adrs2 = $request->get('adrs2');
        $companies->city = $request->get('city');
        $companies->state = $request->get('state');
        $companies->country = $request->get('country');
        $companies->zipcode = $request->get('zipcode');
        $companies->save();
        return response()->json(['success'=>true]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company = Company::find($id);
        
        return view('companies.view',compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function getCompany($id)
    {
        $company = Company::findOrFail($id);
        return response()->json($company);
    }

    public function showCompany($id)
    {
        
        $company = Company::whereId($id)->with('industry')->get();
        return response()->json(['company'=>$company]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $company = Company::find($id);
       
        $company->update($request->all());  
        return response()->json(['success'=>true]); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::find($id);
        $company->delete();    
        return response()->json(['success'=>true]);
    }
}
