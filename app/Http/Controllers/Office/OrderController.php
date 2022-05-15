<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $start = $request->start;
            $end = $request->end;
            $st = $request->st;
            $collection = Order::whereBetween(DB::raw('date(created_at)'), [$start, $end])
            ->where('st', $st)
            ->orderBy('id','DESC')
            ->paginate(10);
            return view('page.office.order.list', compact('collection'));
        }
        return view('page.office.order.main');
    }
    public function show()
    {
    }
    public function edit(Order $order)
    {
        return view('page.office.order.input',compact('order'));
    }
    public function update(Request $request, Order $order)
    {
        $order->resi = $request->resi;
        $order->st = "On the way";
        $order->save();
        return response()->json([
            'alert' => 'success',
            'message' => 'No Resi Inserted',
        ]);
    }
    public function reject(Order $order)
    {
        $order->st = "Payment Rejected";
        $order->update();
        return response()->json([
            'alert' => 'success',
            'message' => 'Payment rejected',
        ]);
    }
    public function acc(Order $order){
        $order->st = "Order on process";
        $order->update();
        foreach($order->order_detail AS $order_detail){
            $stock = "stock_".$order_detail->type;
            DB::select(DB::raw("
            update products set $stock = $stock-$order_detail->qty where id = $order_detail->product_id
            "));
        }
        return response()->json([
            'alert' => 'success',
            'message' => 'Payment accepted',
        ]);
    }
    public function download(Order $order)
    {
        $extension = Str::of($order->photo)->explode('.');
        return Storage::download($order->photo, 'order-'.$order->created_at.'.'.$extension[1]);
    }

    public function pdf(Request $request)
    {  
        $validator = Validator::make($request->all(), [
            'starts' => 'required',
            'ends' =>   'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('starts')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('starts'),
                ]);
            }elseif ($errors->has('ends')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('ends'),
                ]);
            }
        }
        $start = $request->starts;
        $end = $request->ends;
        $st = $request->sts;
        $order = Order::where('st', $st)->whereBetween(DB::raw('date(created_at)'), [$start, $end])
        ->get();
    	$pdf = PDF::loadview('page.office.order.pdf',['order'=> $order, 'st' => $st]);
    	return $pdf->stream('oder-report-on-process.pdf');
    }
    public function invoice(Order $order)
    {  
        $order = Order::where('id', $order->id)->first();
    	$pdf = PDF::loadview('page.office.order.invoice',['order'=> $order]);
    	return $pdf->stream('invoice.pdf');
    }
}
