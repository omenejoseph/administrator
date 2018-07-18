<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Http\Requests\CreateCompanyRequest;
use Datatables;

class CompanyController extends Controller
{
    //

    public function index(){
        $comps = Company::all();
        return view('/company/index', compact('comps'));
    }

    public function create(){
        return view('/company/create');
    }

    public function store(CreateCompanyRequest $request){
        $comps = Company::all();
        $company = new Company;

        $company->name = $request->name;
        $company->email = $request->email;
        $company->website = $request->website;

        if ($file = $request->file('logo')){
            $name = time() . $file->getClientOriginalName(); 
            $file->move('images', $name);
            $company->logo = $name;  
        }
        $company->save();

        return redirect(url('/companies'));
    }

    public function update(Request $request) {
        $comp = Company::findOrFail($request->id);
        if(!$comp) 
            return response()->json(['status'=> 404,'Description' => 'Company Updated']);
        if($request->name) $comp->name = $request->name;
        if($request->email) $comp->email = $request->email;
        if($request->website) $comp->website = $request->website;

        $comp->update();

        return response()->json(['status'=>'Successful','Description' => 'Company Updated']);

    }

    public function destroy(Request $request){
        $comp = Company::findOrFail($request->id);
        if(!$comp) 
            return response()->json(['status'=> 'unsuccesful','Description' => 'company not found'], 404);
        $comp->delete();
        return response()->json(['status'=>'Successful','Description' => 'Company Updated']);
    }
}
