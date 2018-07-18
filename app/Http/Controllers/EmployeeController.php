<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Employee;
use App\Http\Requests\AddEmployeeRequest;

class EmployeeController extends Controller
{
    //
    public function index(){
        $emps = Employee::all();
        return view('employee/index', compact('emps'));
    }

    public function create(){
        $comps = Company::pluck('name', 'id')->all();
        return view('employee/create', compact('comps'));
    }

    public function store(AddEmployeeRequest $request){
        $employee = new Employee;
        if(!$employee)
            return response()->json(['status'=> 'unsuccesful','Description' => 'Employee not found'], 404);

        $employee->firstName = $request->firstName;
        $employee->lastName = $request->lastName;
        $employee->email = $request->email;
        $employee->company_id = $request->company_id;
        $employee->phone = $request->phone;

        $employee->save();
        return redirect(url('/employees/'));
    }

    public function update(Request $request){
        $employee = Employee::findOrFail($request->id);
        if(!$employee) 
            return response()->json(['status'=> 'unsuccesful','Description' => 'Employee not found'], 404);

        if($request->firstName) $employee->firstName = $request->firstName;
        if($request->lastName) $employee->lastName = $request->lastName;
        if($request->email) $employee->email = $request->email;
        if($request->phone) $employee->phone = $request->phone;

        $employee->update();
        return response()->json(['status'=>'Successful','Description' => 'Employee Updated']);
    }

    public function destroy(Request $request){
        $employee = Employee::findOrFail($request->id);
        if(!$employee) 
            return response()->json(['status'=> 'unsuccesful','Description' => 'Employee not found'], 404);

        $employee->delete();
        return response()->json(['status'=>'Successful','Description' => 'Employee Updated']);
    }
}
