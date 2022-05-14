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
            $collection = (object) $arr->get()->paginate(5);
            return view('page.office.product.list',compact('collection'));
        }
        return view('page.office.product.main');
    }
    public function create()
    {
        $category = Category::get();
        return view('page.office.product.input', ["category" => $category, "product" => new Product]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categories_id' => 'required',
            'berat' => 'required',
            'titles' => 'required|unique:products',
            'description' => 'required',
            'price_s' => 'max:19',
            'price_m' => 'max:19',
            'price_l' => 'max:19',
            'price_xl' => 'max:19',
            'price_xxl' => 'max:19',
            'stock_s' => 'max:6',
            'stock_m' => 'max:6',
            'stock_l' => 'max:6',
            'stock_xl' => 'max:6',
            'stock_xxl' => 'max:6',
            'photo' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('categories_id')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('categories_id'),
                ]);
            } elseif ($errors->has('berat')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('berat'),
                ]);
            } elseif ($errors->has('titles')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('titles'),
                ]);
            } elseif ($errors->has('description')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('description'),
                ]);
            } elseif ($errors->has('price_s')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('price_s'),
                ]);
            } elseif ($errors->has('price_m')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('price_m'),
                ]);
            } elseif ($errors->has('price_l')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('price_l'),
                ]);
            } elseif ($errors->has('price_xl')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('price_xl'),
                ]);
            } elseif ($errors->has('price_xxl')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('price_xxl'),
                ]);
            } elseif ($errors->has('stock_s')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('stock_s'),
                ]);
            } elseif ($errors->has('stock_m')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('stock_m'),
                ]);
            } elseif ($errors->has('stock_l')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('stock_l'),
                ]);
            } elseif ($errors->has('stock_xl')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('stock_xl'),
                ]);
            } elseif ($errors->has('stock_xxl')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('stock_xxl'),
                ]);
            } else {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('photo'),
                ]);
            }
        }
        $description = $request->description;
        $dom = new \DomDocument();
        $dom->loadHtml($description, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $imageFile = $dom->getElementsByTagName('imageFile');

        foreach ($imageFile as $item => $image) {
            $data = $img->getAttribute('src');

            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);

            $imgeData = base64_decode($data);
            $image_name = "/upload/" . time() . $item . '.png';
            $path = public_path() . $image_name;
            file_put_contents($path, $imgeData);

            $image->removeAttribute('src');
            $image->setAttribute('src', $image_name);
        }

        $description = $dom->saveHTML();
        $file = request()->file('photo')->store("product");
        $product = new Product;
        $product->categories_id = $request->categories_id;
        $product->berat = $request->berat;
        $product->titles = Str::title($request->titles);
        $product->description = $description;
        $product->price_s = Str::of($request->price_s)->replace(',', '') ?: 0;
        $product->price_m = Str::of($request->price_m)->replace(',', '') ?: 0;
        $product->price_l = Str::of($request->price_l)->replace(',', '') ?: 0;
        $product->price_xl = Str::of($request->price_xl)->replace(',', '') ?: 0;
        $product->price_xxl = Str::of($request->price_xxl)->replace(',', '') ?: 0;
        $product->stock_s = Str::of($request->stock_s)->replace(',', '') ?: 0;
        $product->stock_m = Str::of($request->stock_m)->replace(',', '') ?: 0;
        $product->stock_l = Str::of($request->stock_l)->replace(',', '') ?: 0;
        $product->stock_xl = Str::of($request->stock_xl)->replace(',', '') ?: 0;
        $product->stock_xxl = Str::of($request->stock_xxl)->replace(',', '') ?: 0;
        $product->slug = Str::slug($request->titles);
        $product->photo = $file;
        $product->save();
        // Client::create($request->all());
        return response()->json([
            'alert' => 'success',
            'message' => 'Product ' . $request->titles . ' Saved',
        ]);
    }
    public function show(Product $product)
    {
        //
    }
    public function edit(Product $product)
    {
        $category = Category::get();
        return view('page.office.product.input', compact('category', 'product'));
    }
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'titles' => 'required',
            'berat' => 'required',
            'description' => 'required',
            'price_s' => 'max:19',
            'price_m' => 'max:19',
            'price_l' => 'max:19',
            'price_xl' => 'max:19',
            'price_xxl' => 'max:19',
            'stock_s' => 'max:6',
            'stock_m' => 'max:6',
            'stock_l' => 'max:6',
            'stock_xl' => 'max:6',
            'stock_xxl' => 'max:6',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('titles')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('titles'),
                ]);
            } elseif ($errors->has('berat')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('berat'),
                ]);
            } elseif ($errors->has('description')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('description'),
                ]);
            } elseif ($errors->has('price_s')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('price_s'),
                ]);
            } elseif ($errors->has('price_m')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('price_m'),
                ]);
            } elseif ($errors->has('price_l')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('price_l'),
                ]);
            } elseif ($errors->has('price_xl')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('price_xl'),
                ]);
            } elseif ($errors->has('price_xxl')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('price_xxl'),
                ]);
            } elseif ($errors->has('stock_s')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('stock_s'),
                ]);
            } elseif ($errors->has('stock_m')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('stock_m'),
                ]);
            } elseif ($errors->has('stock_l')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('stock_l'),
                ]);
            } elseif ($errors->has('stock_xl')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('stock_xl'),
                ]);
            }
        }
        if (request()->file('photo')) {
            Storage::delete($product->photo);
            $file = request()->file('photo')->store("product");
            $product->photo = $file;
        }
        $product->categories_id = $request->categories_id;
        $product->berat = $request->berat;
        $product->titles = Str::title($request->titles);
        $product->description = Str::title($request->description);
        $product->price_s = Str::of($request->price_s)->replace(',', '') ?: 0;
        $product->price_m = Str::of($request->price_m)->replace(',', '') ?: 0;
        $product->price_l = Str::of($request->price_l)->replace(',', '') ?: 0;
        $product->price_xl = Str::of($request->price_xl)->replace(',', '') ?: 0;
        $product->price_xxl = Str::of($request->price_xxl)->replace(',', '') ?: 0;
        $product->stock_s = Str::of($request->stock_s)->replace(',', '') ?: 0;
        $product->stock_m = Str::of($request->stock_m)->replace(',', '') ?: 0;
        $product->stock_l = Str::of($request->stock_l)->replace(',', '') ?: 0;
        $product->stock_xl = Str::of($request->stock_xl)->replace(',', '') ?: 0;
        $product->stock_xxl = Str::of($request->stock_xxl)->replace(',', '') ?: 0;
        $product->update();
        return response()->json([
            'alert' => 'success',
            'message' => 'Product ' . $request->titles . ' Updated',
        ]);
    }
    public function destroy(Product $product)
    {
        Storage::delete($product->photo);
        $product->delete();
        return response()->json([
            'alert' => 'success',
            'message' => 'Product ' . $product->titles . ' Deleted',
        ]);
    }
    public function download(Product $product)
    {
        $extension = Str::of($product->photo)->explode('.');
        return Storage::download($product->photo, $product->titles . '.' . $extension[1]);
    }
}
