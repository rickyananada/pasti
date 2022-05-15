<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Models\OrderDetail;
use App\Models\OrderRate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Mail\Thankyou;
use PDF;

class OrderController extends Controller
{
    public function index()
    {
        //
    }
    public function create()
    {
        //
    }
    public function ongkir(Request $request)
    {
        $rajaongkir_asal_kota = 6; // Kota Bandung
        $rajaongkir_berat = $request->berat; // Gram
        // $rajaongkir_berat = 10000; // Gram
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=501&destination=114&weight=1700&courier=jne",
            // "origin=$rajaongkir_asal_kota&originType=city&destination=$request->param
            //     &destinationType=subdistrict&weight=$rajaongkir_berat&courier=$request->courier",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: 0a3d18cbb83bb1f81eff7be8aa2150b8"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }
    public function store(Request $request)
    {
        $token = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|max:255',
            'phone' => 'required|min:9|max:15',
            'address' => 'required',
            'province' => 'required',
            'city' => 'required',
            'subdistrict' => 'required',
            'postcode' => 'required',
            'ekspedisi' => 'required',
            'ongkir' => 'required',
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
            } elseif ($errors->has('province')) {
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
            } elseif ($errors->has('postcode')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('postcode'),
                ]);
            } elseif ($errors->has('ekspedisi')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('ekspedisi'),
                ]);
            } elseif ($errors->has('ongkir')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('ongkir'),
                ]);
            } elseif ($errors->has('photo')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('photo'),
                ]);
            }
        }
        $total_harga = 0;

        $cart = Cart::where('user_id', $token)->get();

        $order = new Order;
        $file = request()->file('photo')->store("order");
        $order->user_id = $token;
        $order->photo = $file;
        $order->name = Str::title($request->name);
        $order->email = $request->email;
        $order->phone = $request->phone;
        $order->address = $request->address;
        $order->province_id = $request->province;
        $order->city_id = $request->city;
        $order->subdistrict_id = $request->subdistrict;
        $order->ekspedisi = $request->ekspedisi;
        $order->type = Str::before($request->ongkir, '_');
        $order->ongkir = Str::of(Str::after($request->ongkir, '_'))->replace(',', '');
        $order->postcode = $request->postcode;
        if ($request->notes) {
            $order->notes = $request->notes;
        }
        $order->st = 'Wait for confirmation';
        $order->save();

        foreach ($cart as $item) {
            $order_detail = new OrderDetail;
            $price = "price_" . $item->type;
            $total_harga += $item->product->$price * $item->qty;
            $order_detail->order_id = $order->id;
            $order_detail->product_id = $item->product_id;
            $order_detail->type = $item->type;
            $order_detail->titles = $item->product->titles;
            $order_detail->price = $item->product->$price;
            $order_detail->qty = $item->qty;
            $order_detail->subtotal = $item->product->$price * $item->qty;
            $order_detail->save();
            $item->delete();
        }
        $order->total = $total_harga;
        $order->update();
        // ($request->email)->send(new Thankyou($order));
        return response()->json([
            'alert' => 'success',
            'message' => 'Order has been created',
            'redirect' => 'profile',
        ]);
    }
    public function show(Order $order)
    {
        //
    }
    public function edit(Order $order)
    {
        //
    }
    public function update(Request $request, Order $order)
    {
        //
    }
    public function destroy(Order $order)
    {
        //
    }
    public function cancel(Order $order)
    {
        $order->st = 'Cancel';
        $order->update();
        return response()->json([
            'alert' => 'success',
            'message' => 'Order has been cancel',
        ]);
    }
    public function receive(Order $order)
    {
        $order->st = 'Received';
        $order->update();
        return response()->json([
            'alert' => 'success',
            'message' => 'Order has been received',
        ]);
    }
    public function review(Order $order)
    {
?>
        <div class="modal-header">
            <h4 class="modal-title" id="reviewFormModalLabel">Submit a Review</h4>
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
            <form class="row mb-0" id="form_review">
                <div class="col-12 mb-3">
                    <label for="template-reviewform-rating">Rating</label>
                    <select name="rating" class="form-select">
                        <option value="">-- Select One --</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
                <div class="w-100"></div>
                <div class="col-12 mb-3">
                    <label for="template-reviewform-comment">Comment <small>*</small></label>
                    <textarea class="required form-control" name="review" rows="6" cols="30"></textarea>
                </div>
                <div class="col-12">
                    <button class="button button-3d m-0" type="button" onclick="handle_save_modal(`#tombol_simpan`,`#form_review`, `<?php echo route('user.order.do_review', $order->id); ?>`,`#reviewFormModal`,`Submit Review`);" id="tombol_simpan">Submit Review</button>
                </div>
            </form>
        </div>
<?php
    }
    public function do_review(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required',
            'review' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('rating')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('rating'),
                ]);
            } elseif ($errors->has('review')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('review'),
                ]);
            }
        }

        foreach ($order->order_detail as $order_detail) {
            $order_rate = new OrderRate;
            $order_rate->user_id = Auth::user()->id;
            $order_rate->order_id = $order->id;
            $order_rate->product_id = $order_detail->product_id;
            $order_rate->rates = $request->rating;
            $order_rate->review = $request->review;
            $order_rate->save();
        }
        return response()->json([
            'alert' => 'success',
            'message' => 'Order has been review'
        ]);
    }
    public function pdf(Order $order)
    {  
        $order = Order::where('id', $order->id)->get();
    	$pdf = PDF::loadview('page.user.auth.pdf',['order'=> $order]);
    	return $pdf->stream('invoice.pdf');
    }
}
