<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Province;
use App\Models\User;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'edit_profile', 'update_profile', 'do_logout');
    }
    public function index()
    {
        return view('page.web.auth.main');
    }
    public function do_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('email')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('email'),
                ]);
            } else {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('password'),
                ]);
            }
        }
        try {
            // $check = new Client();
            // $url = $check->request('POST','127.0.0.1:8002/api/auth/login', [
            //     'email' => $request->email,
            //     'password'  => $request->password,
            //     'role' => 'user'
            // ]);
            $check = Http::post("127.0.0.1:8002/api/auth/login",[
                'email' => $request->email,
                'password'  => $request->password,
                'role' => 'user'
            ]);
            if($check->getStatusCode() == 200){
                $collection = json_decode($check->getBody());
                Session::put('token' , $collection->data->token);
                Session::put('id' , $collection->data->id);
                Session::put('name' , $collection->data->name);
                Session::put('email' , $collection->data->email);
                Session::put('role' , $collection->data->role);
                Session::put('phone' , $collection->data->phone);
                return response()->json([
                    'alert' => 'success',
                    'message' => 'Selamat Datang Kembali ' . $collection->data->name, 
                    'response' => $check->getStatusCode(),
                    'session' => Session::get('token')
                ]);
            }
            if($check->getStatusCode() == 400 || $check->getStatusCode() == 401){
                return response()->json([
                    'alert' => 'error',
                    'message' => 'Unauthorized Wrong credentials email / password',
                    'response' => $check->getStatusCode(),
                ]);
            }
            // try {
            //     if (! $token = JWTAuth::attempt($check)) {
            //         return response()->json([
            //         	'success' => false,
            //         	'message' => 'Login credentials are invalid.',
            //         ], 400);
            //     }
            // } catch (JWTException $e) {
            // return $check;
            //     return response()->json([
            //         	'success' => false,
            //         	'message' => 'Could not create token.',
            //         ], 500);
            // }
        
            //Token created, return with success response and jwt token
            // return response()->json([
            //     'success' => true,
            //     'token' => $token,
            // ]);
            // dd($check->getStatusCode());
            // if($check->getStatusCode() == 403 || $check->getStatusCode() == 401){
            //     return response()->json([
            //         'alert' => 'error',
            //         'message' => 'Unauthorized ',
            //         'response' => $check->getStatusCode(),
            //     ]);
            // }
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
    public function do_register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required|min:9|max:15',
            'password' => 'required|min:8|max:12',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('name')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('name'),
                ]);
            } elseif ($errors->has('email')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('email'),
                ]);
            } elseif ($errors->has('phone')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('phone'),
                ]);
            } elseif ($errors->has('password')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('password'),
                ]);
            }
        }
        try {
            $req = Http::post("127.0.0.1:8002/api/auth/register",[
                'name'=> $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'phone' => $request->phone,
                'role' => 'user',
            ]);

            if($req->getStatusCode() == 500 || $req->getStatusCode() == 404 || $req->getStatusCode() == 403) {
                    return response()->json([
                        'alert' => 'error',
                        'message' => 'request failed',
                        'response' => $req->getStatusCode(),
                    ]);
            }
            return response()->json([
                'alert' => 'success',
                'message' => 'Customer ' . $request->name . ' Sukses Terdaftar',
                'response' => $req->getStatusCode(),
            ]);
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

    public function profile(Request $request)
    {
        if ($request->ajax()) {
            $token = Auth::user()->id;
            $keywords = $request->keywords;
            $collection = Order::where('user_id', $token)
                ->orderBy('id', 'DESC')
                ->paginate(10);
            return view('page.web.auth.list', compact('collection'));
        }
        return view('page.web.auth.profile');
    }
    public function edit_profile(User $user)
    {
        $provinsi= Province::get();
        return view('page.web.auth.edit', compact('user','provinsi'));
    }
    public function update_profile(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|max:255',
            'phone' => 'required|min:9|max:15',
            'address' => 'required',
            'province' => 'required',
            'city' => 'required',
            'subdistrict' => 'required',
            'photo' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('name')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('name'),
                ]);
            } elseif ($errors->has('email')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('email'),
                ]);
            } elseif ($errors->has('phone')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('phone'),
                ]);
            } elseif ($errors->has('address')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('address'),
                ]);
            }
             elseif ($errors->has('province')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('province'),
                ]);
            } elseif ($errors->has('city')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('city'),
                ]);
            } elseif ($errors->has('subdistrict')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('subdistrict'),
                ]);
            }
        }
        if (request()->file('photo')) {
            Storage::delete($user->photo);
            $file = request()->file('photo')->store("user");
            $user->photo = $file;
        }
        $user->name = Str::title($request->name);
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->province_id = $request->province;
        $user->city_id = $request->city;
        $user->subdistrict_id = $request->subdistrict;
        $user->postcode = $request->postcode;
        $user->username = Str::before($request->email, '@');
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->update();
        return response()->json([
            'alert' => 'success',
            'message' => 'Profile Updated',
        ]);
    }
    public function do_logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect()->route('web.home');
    }
}
