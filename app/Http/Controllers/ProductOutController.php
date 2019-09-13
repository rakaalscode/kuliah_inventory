<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{ProductOut, Customer, Product};
use DataTables;
use DB;

class ProductOutController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('productout.index',compact('customers','products'));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'product'      => 'required',
            'customer'     => 'required',
            'quantity'     => 'required|numeric|max:1000|min:0',
            'date'         => 'required|date',
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        DB::beginTransaction();
        try {

            $product = Product::where('id',$request->product)->first();
            $oldProduct = $product->qty;
            $newProduct = ($oldProduct - $request->quantity);
            if($newProduct < 0)
            {
                $response = [
                    'errors'   => [$product->name.' stock available '.$product->qty]
                ];
                return response()->json($response); 
            }

            $product->update(array('qty' => $newProduct));

            $data = array(
                'product_id'    =>  $request->product,
                'customer_id'   =>  $request->customer,
                'qty'           =>  $request->quantity,
                'date'          =>  $request->date
            );
            ProductOut::create($data);

            DB::commit();
            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $productout = ProductOut::with(['product','customer'])->where('id',$id)->first();
        return $productout;
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'product'      => 'required',
            'customer'     => 'required',
            'quantity'     => 'required|numeric|max:1000|min:0',
            'date'         => 'required|date',
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        DB::beginTransaction();
        try {

            $productOut = ProductOut::where('id', $id)->first();
            $product = Product::where('id',$request->product)->first();

            $oldProduct = $product->qty + $productOut->qty;
            $newProduct = $oldProduct - $request->quantity;
            if($newProduct < 0)
            {
                $response = [
                    'errors'   => [$product->name.' stock available '.$oldProduct]
                ];
                return response()->json($response); 
            }

            $product->update(array('qty' => $newProduct));

            $data = array(
                'product_id'    =>  $request->product,
                'customer_id'   =>  $request->customer,
                'qty'           =>  $request->quantity,
                'date'          =>  $request->date
            );

            $productOut->update($data);

            DB::commit();
            return response()->json(['status' => 'success'], 200);
        
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request,$id)
    {
        $productOut = ProductOut::find($id);
        $product = Product::where('id',$productOut->product_id)->first();

        $stock = $product->qty + $productOut->qty;

        $product->update(array('qty' => $stock));
        $productOut->delete();
        return response()->json(['status' => 'success'], 200);
    }

    public function data()
    {
        $products = ProductOut::with(['product','customer'])->select('product_outs.*');
        return Datatables::of($products)
        ->editColumn('actions', function ($products) {
            return "<div class='text-center'>
                        <a onclick='editModal($products->id)' class='btn btn-warning btn-sm'><i class='fa fa-edit fa-sm'></i></a>
                        <a onclick='deleteProductOut($products->id)' class='btn btn-danger btn-sm'><i class='fa fa-trash fa-sm'></i></a>
                   </div>";
        })
        ->rawColumns(['actions'])
        ->make(true);
    }
}
