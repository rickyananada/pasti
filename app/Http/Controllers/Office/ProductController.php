<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $arr = Http::get("127.0.0.1:8001/api/produks")->json(['data']);
            $collection = (object) $arr;
            return view('page.office.product.list',compact('collection'));
        }
        return view('page.office.product.main');
    }
    public function create()
    {
        // $category = Category::get();
        $arr = Http::get("127.0.0.1:8001/api/produks");
        return view('page.office.product.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'categories_id' => 'required',
            'name' => 'required',
            'weight' => 'required',
            'description' => 'required',
            'price' => 'max:19',
            'stock' => 'max:6',
            'photo' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            // if ($errors->has('categories_id')) {
            //     return response()->json([
            //         'alert' => 'error',
            //         'message' => $errors->first('categories_id'),
            //     ]);
            // } 
            // else
            if ($errors->has('name')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('name'),
                ]);
            } elseif ($errors->has('weight')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('weight'),
                ]);
            } elseif ($errors->has('description')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('description'),
                ]);
            } elseif ($errors->has('price')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('price'),
                ]);
            } elseif ($errors->has('stock')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('stock'),
                ]);
            } else {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('photo'),
                ]);
            }
        }
        $file = request()->file('photo')->store("product");
        $store = Http::post("127.0.0.1:8001/api/produks", [
            "name" => Str::title($request->name),
            "weight" => $request->weight,
            "description" => $request->description,
            "price" => Str::of($request->price)->replace(',', '') ?: 0,
            "stock" => Str::of($request->stock)->replace(',', '') ?: 0,
            "photo" => $file,
        ]);
        // dd($store);
        if($store->getStatusCode() == 200){
            return response()->json([
                'alert' => 'success',
                'message' => 'Product ' . $request->name . ' stored',
                'response' => $store->getStatusCode(),
            ]);
        }
        return response()->json([
            'alert' => 'error',
            'message' => 'request failed',
            'response' => $store->getStatusCode(),
        ]);
    }
    public function show($product)
    {
        //
    }
    public function edit($data)
    {
        $product = Http::get("127.0.0.1:8001/api/produks/{$data}")->json(['data']);
        $product = (object) $product;
        return view('page.office.product.input', compact('product'));
    }
    public function update(Request $request, $product)
    {
        $validator = Validator::make($request->all(), [
            // 'categories_id' => 'required',
            'name' => 'required',
            'weight' => 'required',
            'description' => 'required',
            'price' => 'max:19',
            'stock' => 'max:6',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            // if ($errors->has('categories_id')) {
            //     return response()->json([
            //         'alert' => 'error',
            //         'message' => $errors->first('categories_id'),
            //     ]);
            // } 
            // else
            if ($errors->has('name')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('name'),
                ]);
            } elseif ($errors->has('weight')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('weight'),
                ]);
            } elseif ($errors->has('description')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('description'),
                ]);
            } elseif ($errors->has('price')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('price'),
                ]);
            } elseif ($errors->has('stock')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('stock'),
                ]);
            }
        }
        $data = Http::get("127.0.0.1:8001/api/produks/{$product}")->json(['data']);
        $item = (object) $data;
        if (request()->file('photo')) {
            Storage::delete($item->photo);
            $file = request()->file('photo')->store("product");
            $store = Http::put("127.0.0.1:8001/api/produks/{$product}", [
                "id" => $request->id,
                "name" => Str::title($request->name),
                "weight" => $request->weight,
                "description" => $request->description,
                "price" => Str::of($request->price)->replace(',', '') ?: 0,
                "stock" => Str::of($request->stock)->replace(',', '') ?: 0,
                "photo" => $file,
                'user_id' => 1
            ]);
        }else{
            $store = Http::put("127.0.0.1:8001/api/produks/{$product}", [
                "id" => $request->id,
                "name" => Str::title($request->name),
                "weight" => $request->weight,
                "description" => $request->description,
                "price" => Str::of($request->price)->replace(',', '') ?: 0,
                "stock" => Str::of($request->stock)->replace(',', '') ?: 0,
                'user_id' => 1
            ]);
        }
        dd($store->getStatusCode());
        if($store->getStatusCode() == 200){
            return response()->json([
                'alert' => 'success',
                'message' => 'Product ' . $request->name . ' stored',
                'response' => $store->getStatusCode(),
            ]);
        }
        return response()->json([
            'alert' => 'error',
            'message' => 'request failed',
            'response' => $store->getStatusCode(),
        ]);
    }
    public function destroy($product)
    {
        $data = Http::get("127.0.0.1:8001/api/produks/{$product}")->json(['data']);
        Http::delete("127.0.0.1:8001/api/produks/{$data['id']}");
        Storage::delete($data['photo']);
        return response()->json([
            'alert' => 'success',
            'message' => 'Product ' . $data['name'] . ' Deleted',
        ]);
    }
}
