<?php

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Request;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Connection
{
    public function callable(){
        return view('errors.500');
    }

    public function produks()
    {

        // $connect = new Client;
        // $request = $connect->get(Config::get('127.0.0.1:8001/api/produks'));
        // dd($request);
        // if($arr->clientError() == true){
        //     return view('errors.500');
        // }
        
        // return Http::dd()->asForm()->get("127.0.0.1:8001/api/produks")->json(['data']);
        try {
            $url = "127.0.0.1:8001/api/produks";
            // dd($check);
            $connect = new Client([
                'base_uri' => $url,
            ]);
            $response = $connect->request('GET');
            $response = json_decode($response->getBody());
            $response = $response->data;
            // dd($response);
            // $request = $connect->get(Config::get('127.0.0.1:8001/api/produks'));
            // $status = $request->getStatusCode();
            // dd($connect);
            // $connect->throwIf;
            // dd($connect->status());
            // if($connect->onError($connect)){
            //     return view('errors.500');
            // }
            return $response;
        } catch (ConnectException $e) {
            // dd($e);
        } catch (Exception $e) {
            // return $e;
            // dd($e);
        }
    }
}
    // public static function auth($email, $password, $role){
    //     try {
    //         $url = Http::post("127.0.0.1:8002/api/auth/login",[
    //             'email' => $email,
    //             'password'  => $password,
    //             'role' => $role
    //         ]);
    //         $connect = new Client([
    //             'base_uri' => $url,
    //         ]);
    //         dd($connect->getStatusCode());
    //         if($connect->getStatusCode() == 200){
    //             $collection = json_decode($connect->getBody());
    //             Session::put('token' , $collection->data->token);
    //             return response()->json([
    //                 'alert' => 'success',
    //                 'message' => 'Welcome back ' . $collection->data->name, 
    //                 'response' => $connect->getStatusCode(),
    //                 'session' => Session::get('token')
    //             ]);
    //         }
    //         if($connect->getStatusCode() == 403 || $connect->getStatusCode() == 401){
    //             return response()->json([
    //                 'alert' => 'error',
    //                 'message' => 'Unauthorized ',
    //                 'response' => $connect->getStatusCode(),
    //             ]);
    //         }
    //     } catch (ConnectException $e) {
    //         return response()->json([
    //             'alert' => 'error',
    //             'message' => 'Service gagal terkoneksi', 
    //             'response' => $e
    //         ]);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'alert' => 'error',
    //             'message' => 'Service sedang bermasalah', 
    //             'response' => $e
    //         ]);
    //     }
       
    // }

// }
