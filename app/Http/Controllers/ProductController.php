<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Product, Category};
use DataTables;
use File;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('product.index',compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name'      => 'required|string|max:191',
            'category'  => 'required',
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price'     => 'required|numeric|max:1000000000|min:0',
            'quantity'       => 'required|numeric|max:1000|min:0',
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        $image = '';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image = rand() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images', $image);
        }

        $data = array(
            'name'          =>   $request->name,
            'category_id'   =>   $request->category,
            'price'         =>   $request->price,
            'image'         =>   $image,
            'qty'           =>   $request->quantity,
        );

        Product::create($data);
        return response()->json(['status' => 'success'], 200);
    }

    public function edit($id)
    {
        $product = Product::where('id',$id)->first();
        return $product;
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'name'      => 'required|string|max:191',
            'category'  => 'required',
            'image'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price'     => 'required|numeric|max:1000000000|min:0',
            'quantity'  => 'required|numeric|max:1000|min:0',
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        
        $product = Product::where('id', $id)->first();

        $image = $product->image;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            File::delete(storage_path('app/public/images/' . $image));
            $image = rand() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images', $image);
        }

        $data = array(
            'name'          =>   $request->name,
            'category_id'   =>   $request->category,
            'price'         =>   $request->price,
            'image'         =>   $image,
            'qty'           =>   $request->quantity,
        );

        $product->update($data);
        return response()->json(['status' => 'success'], 200);
    }

    public function destroy(Request $request,$id)
    {
        $product = Product::find($id);
        File::delete(storage_path('app/public/images/' . $product->image)); //MENGHAPUS FILE FOTO
        $product->delete();
        return response()->json(['status' => 'success'], 200);
    }

    public function data()
    {
        $products = Product::with('category')->select('products.*');
        return Datatables::of($products)
        ->editColumn('actions', function ($products) {
            return "<div class='text-center'>
                        <a onclick='editModal($products->id)' class='btn btn-warning btn-sm'><i class='fa fa-edit fa-sm'></i></a>
                        <a onclick='deleteProduct($products->id)' class='btn btn-danger btn-sm'><i class='fa fa-trash fa-sm'></i></a>
                   </div>";
        })
        ->addColumn('image', function ($products) {
            if($products->image == ''){
                $url=asset("dist/img/no-image.jpg"); 
            }else{
                $url=asset("storage/images/$products->image"); 
            }
            return '<img src='.$url.' class="img-thumbnail" align="center" width="75" />'; })
        ->rawColumns(['image','actions'])
        ->make(true);
    }
}
