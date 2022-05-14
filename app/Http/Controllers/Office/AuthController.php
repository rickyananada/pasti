<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:office')->except('do_logout');
    }
    public function index()
    {
        return view('page.office.auth.main');
    }
    public function do_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('email')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('email'),
                ]);
            }else{
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('password'),
                ]);
            }
        }
        $check = (Http::post("127.0.0.1:8002/api/auth/login",[
            'email' => $request->email,
            'password'  => $request->password,
            'role' => 'admin'
        ]));
        if($check->getStatusCode() == 200){
            $collection = json_decode($check->getBody());
            Session::put('admin' , $collection->data->token);
            return response()->json([
                'alert' => 'success',
                'message' => 'Welcome back ' . $collection->data->name, 
                'response' => $check->getStatusCode(),
                'session' => Session::get('admin')
            ]);
        }
        if($check->getStatusCode() == 403 || $check->getStatusCode() == 401){
            return response()->json([
                'alert' => 'error',
                'message' => 'Wrong email or password',
                'response' => $check->getStatusCode(),
            ]);
        }
    }
    public function do_logout()
    {
        $employee = Auth::guard('office')->user();
        Auth::logout($employee);
        return redirect()->route('office.auth.index');
    }
}
