<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use PDF;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $keywords = $request->keywords;
            $collection = User::where('role','!=','Admin')
            ->where('name','like','%'.$keywords.'%')
            ->paginate(10);
            return view('page.office.customer.list', compact('collection'));
        }
        return view('page.office.customer.main');
    }
    public function create()
    {
        return view('page.office.customer.input', ["user" => new User]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titles' => 'required|unique:users',
            'photo' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('titles')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('titles'),
                ]);
            }else{
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('photo'),
                ]);
            }
        }
        $file = request()->file('photo')->store("category");
        $user = new User;
        $user->titles = Str::title($request->titles);
        $user->slug = Str::slug($request->titles);
        $user->photo = $file;
        $user->save();
        return response()->json([
            'alert' => 'success',
            'message' => 'Category '. $request->titles . ' Saved',
        ]);
    }
    public function show()
    {
        // 
    }
    public function edit(User $user)
    {
        return view('page.office.customer.input', compact('user'));
    }
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'titles' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('titles')) {
                return response()->json([
                    'alert' => 'error',
                    'message' => $errors->first('titles'),
                ]);
            }
        }
        if(request()->file('photo')){
            Storage::delete($user->photo);
            $file = request()->file('photo')->store("user");
            $user->photo = $file;
        }
        $user->name = Str::title($request->name);
        $user->update();
        return response()->json([
            'alert' => 'success',
            'message' => 'Customer '. $request->name . ' Updated',
        ]);
    }
    public function destroy(User $user)
    {
        Storage::delete($user->photo);
        $user->delete();
        return response()->json([
            'alert' => 'success',
            'message' => 'Customer '. $user->titles . ' Deleted',
        ]);
    }
    public function pdf()
    {
        $pdf = PDF::loadview('page.office.customer.list')->setPaper('A4','potrait');
        return $pdf->stream();
    }
}
