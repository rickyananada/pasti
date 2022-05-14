<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

class WebController extends Controller
{
    public function index()
    {
        $arr = Http::get("127.0.0.1:8001/api/produks")->json(['data']);
        $inspiring = Inspiring::quote();
        $collection = Collection::make($arr);
        return view('page.web.home.main',compact('inspiring','collection'));
    }
    public function about()
    {
        return view('page.web.home.about');
    }
}
