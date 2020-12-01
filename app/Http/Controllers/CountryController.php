<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Country;
class CountryController extends Controller
{
    public function getCountry()
    {
    	$countries=Country::all();
    	 return response()->json($countries); 
    }
}
