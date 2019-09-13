<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{ProductIn, Supplier, Product};
use DataTables;
use DB;

class ProductInController extends Controller
{
    public function index()
    {
        $suppliers  = Supplier::all();
        $products   = Product::all();
        return view('productin.index',compact('suppliers','products'));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'product'      => 'required',
            'supplier'     => 'required',
            'quantity'     => 'required|numeric|max:1000',
            'date'         => 'required|date',
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        DB::beginTransaction();
        try {

            $product    = Product::where('id',$request->product)->first();
            $oldStock   = $product->qty;
            $newStock   = ($oldStock + $request->quantity);

            $product->update(array('qty' => $newStock));

            $data = array(
                'product_id'    =>  $request->product,
                'supplier_id'   =>  $request->supplier,
                'qty'           =>  $request->quantity,
                'date'          =>  $request->date
            );
            ProductIn::create($data);

            DB::commit();
            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $productin = ProductIn::with(['product','supplier'])->where('id',$id)->first();
        return $productin;
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'product'      => 'required',
            'supplier'     => 'required',
            'quantity'     => 'required|numeric|max:1000',
            'date'         => 'required|date',
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        DB::beginTransaction();
        try {

            $productIn = ProductIn::where('id', $id)->first();
            $product = Product::where('id',$request->product)->first();

            $oldStock = $product->qty - $productIn->qty;
            $newStock = $oldStock + $request->quantity;
            if($newStock < 0)
            {
                $response = [
                    'errors'   => [$product->name.' stock available '.$oldStock]
                ];
                return response()->json($response); 
            }

            $product->update(array('qty' => $newStock));

            $data = array(
                'product_id'    =>  $request->product,
                'supplier_id'   =>  $request->supplier,
                'qty'           =>  $request->quantity,
                'date'          =>  $request->date
            );

            $productIn->update($data);

            DB::commit();
            return response()->json(['status' => 'success'], 200);
        
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request,$id)
    {
        $productIn = ProductIn::find($id);
        $product = Product::where('id',$productIn->product_id)->first();

        $stock = $product->qty - $productIn->qty;

        $product->update(array('qty' => $stock));
        $productIn->delete();
        return response()->json(['status' => 'success'], 200);
    }

    public function data()
    {
        $products = ProductIn::with(['product','supplier'])->select('product_ins.*');
        return Datatables::of($products)
        ->editColumn('actions', function ($products) {
            return "<div class='text-center'>
                        <a onclick='editModal($products->id)' class='btn btn-warning btn-sm'><i class='fa fa-edit fa-sm'></i></a>
                        <a onclick='deleteProductIn($products->id)' class='btn btn-danger btn-sm'><i class='fa fa-trash fa-sm'></i></a>
                   </div>";
        })
        ->rawColumns(['actions'])
        ->make(true);
    }
}
