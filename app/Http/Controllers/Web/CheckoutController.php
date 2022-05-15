<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();
        $provinsi= Province::get();
        $token = Auth::user()->id;
        $collection = Cart::where('user_id', $token)
            ->get();
        return view('page.user.checkout.main', compact('collection','provinsi','user'));
    }
    public function list(Request $request)
    {
        $output = '';
        $total_harga = 0;
        $token = Auth::user()->id;
        $collection = Cart::where('user_id', $token)->get();
        foreach ($collection as $data_load) {
            $price = "price_" . $data_load->type;
            $stock = "stock_" . $data_load->type;
            $total_harga += $data_load->product->$price * $data_load->qty;
            $output .= '
                <div class="top-cart-item">
                    <div class="top-cart-item-image">
                        <a href="' . route('user.product.show', $data_load->product->slug) . '">
                        <img src="' . $data_load->product->image . '" alt="' . $data_load->product->titles . '" /></a>
                    </div>
                    <div class="top-cart-item-desc">
                        <div class="top-cart-item-desc-title">
                            <a href="' . route('user.product.show', $data_load->product->slug) . '">' . $data_load->product->titles . ' | ' . Str::title($data_load->type) . '</a>
                            <span class="top-cart-item-price d-block">
                            ' . number_format($data_load->product->$price) . '
                            </span>
                        </div>
                        <div class="top-cart-item-quantity">
                            ' . number_format($data_load->qty) . '
                            <br>
                            <a onclick="handle_delete(`' . route('user.cart.destroy', $data_load->id) . '`);"><i class="icon-trash2"></i></a>
                        </div>
                    </div>
                </div>
            ';
        }
        return response()->json([
            'collection' => $output,
            'total_harga' => $total_harga,
            'total_item' => $collection->count(),
        ]);
    }
    public function store(Request $request)
    {
        $token = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'quantity' => 'required',
            'size' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('quantity')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('quantity'),
                ]);
            } elseif ($errors->has('size')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('size'),
                ]);
            }
        }
        $stock = Product::where('id', $request->product)->first();
        $stocknya = 'stock_' . $request->size;
        if ($stock->$stocknya >= $request->quantity) {
            $check = Cart::where('product_id', $request->product)
                ->where('user_id', $token)
                ->where('type', $request->size)
                ->first();
            if ($check) {
                DB::select(DB::raw("
                update carts set qty = qty+$request->quantity, updated_at = '" . date('Y-m-d H:i:s') . "' where id = '$check->id'
                "));
            } else {
                $cart = new Cart;
                $cart->user_id = $token;
                $cart->product_id = $request->product;
                $cart->type = $request->size;
                $cart->qty = $request->quantity;
                $cart->save();
            }
            return response()->json([
                'alert' => 'success',
                'message' => 'Added to cart',
            ]);
        } else {
            return response()->json([
                'alert' => 'info',
                'message' => 'Stock Produk tidak cukup',
            ]);
        }
    }
    public function destroy(Cart $cart)
    {
        $cart->delete();
        return response()->json([
            'alert' => 'success',
            'message' => 'Cart ' . $cart->product->titles . ' Deleted',
        ]);
    }
}
