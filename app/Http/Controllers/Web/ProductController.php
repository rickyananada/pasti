<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function show($id)
    {
        
        $arr = Http::get("127.0.0.1:8001/api/produks/{$id}")->json();
        $product = $arr['data'];
        $arr = Http::get("127.0.0.1:8001/api/produks")->json(['data']);
        $collection = Collection::make($arr);
        return view('page.web.product.show',compact('product','collection'));
    }
}
