<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use Exception;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
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
        try {
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
                    'message' => 'Selamat Datang Kembali ' . $collection->data->name, 
                    'response' => $check->getStatusCode(),
                    'session' => Session::get('admin')
                ]);
            }
            if($check->getStatusCode() == 403 || $check->getStatusCode() == 401){
                return response()->json([
                    'alert' => 'error',
                    'message' => 'Email atau password salah',
                    'response' => $check->getStatusCode(),
                ]);
            }
        } catch (ConnectException $e) {
            return response()->json([
                'alert' => 'error',
                'message' => 'Service gagal terkoneksi', 
                'response' => $e
            ]);
        } catch (RequestException $e) {
            if ($e->hasResponse()){
                if ($e->getResponse()->getStatusCode() == '400') {
                        echo "Got response 400" ;
                }
            }
        }catch (Exception $e) {
            return response()->json([
                'alert' => 'error',
                'message' => 'Service sedang bermasalah', 
                'response' => $e
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
