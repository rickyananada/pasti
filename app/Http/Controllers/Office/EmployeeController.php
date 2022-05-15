<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\User AS Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $keywords = $request->keywords;
            $collection = Employee::where('role','=','Admin')
            ->where('name','like','%'.$keywords.'%')
            ->paginate(10);
            return view('page.office.employee.list', compact('collection'));
        }
        return view('page.office.employee.main');
    }
    public function create()
    {
        return view('page.office.employee.input', ["employee" => new Employee]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users|max:255',
            'phone' => 'required|unique:users|min:9|max:15',
            'password' => 'required|min:8',
            'photo' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('name')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('name'),
                ]);
            }elseif ($errors->has('email')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('email'),
                ]);
            }elseif ($errors->has('phone')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('phone'),
                ]);
            }elseif ($errors->has('password')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('password'),
                ]);
            }else{
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('photo'),
                ]);
            }
        }
        $file = request()->file('photo')->store("user");
        $employee = new Employee;
        $employee->name = Str::title($request->name);
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->username = Str::before($request->email, '@');
        $employee->photo = $file;
        $employee->password = Hash::make($request->password);
        $employee->role = 'Admin';
        $employee->save();
        return response()->json([
            'alert' => 'success',
            'message' => 'Employee '. $request->name . ' Saved',
        ]);
    }
    public function edit(Employee $employee)
    {
        return view('page.office.employee.input', compact('employee'));
    }
    public function update(Request $request, Employee $employee)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|max:255',
            'phone' => 'required|min:9|max:15',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('name')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('name'),
                ]);
            }elseif ($errors->has('email')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('email'),
                ]);
            }else{
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('phone'),
                ]);
            }
        }
        if(request()->file('photo')){
            Storage::delete($employee->photo);
            $file = request()->file('photo')->store("user");
            $employee->photo = $file;
        }
        $employee->name = Str::title($request->name);
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->username = Str::before($request->email, '@');
        if($request->password){
            $employee->password = Hash::make($request->password);
        }
        $employee->update();
        return response()->json([
            'alert' => 'success',
            'message' => 'Employee '. $request->name . ' Updated',
        ]);
    }
    public function destroy(Employee $employee)
    {
        Storage::delete($employee->photo);
        $employee->delete();
        return response()->json([
            'alert' => 'success',
            'message' => 'Employee '. $employee->titles . ' Deleted',
        ]);
    }
}
