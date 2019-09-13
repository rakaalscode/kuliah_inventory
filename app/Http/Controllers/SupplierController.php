<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Supplier;
use DataTables;

class SupplierController extends Controller
{
    public function index()
    {
        return view('supplier.index');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name'  => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:suppliers,email',
            'phone' => 'required|max:13|min:8',
            'address' => 'required'
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        Supplier::create($request->all());
        return response()->json(['status' => 'success'], 200);
    }

    public function edit($id)
    {
        $supplier = Supplier::where('id',$id)->first();
        return $supplier;
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::where('id', $id)->first();

        $validator = \Validator::make($request->all(), [
            'name'  => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:suppliers,email,'. $supplier->id . ',id',
            'phone' => 'required|max:13|min:8',
            'address' => 'required'
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        $supplier->update($request->except('id'));
        return response()->json(['status' => 'success'], 200);
    }

    public function destroy(Request $request,$id)
    {
        $supplier = Supplier::find($id);
        $supplier->delete();
        return response()->json(['status' => 'success'], 200);
    }

    public function data()
    {
        $suppliers = Supplier::all();
        return Datatables::of($suppliers)
        ->editColumn('actions', function ($suppliers) {
            return "<div class='text-center'>
                        <a onclick='editModal($suppliers->id)' class='btn btn-warning btn-sm'><i class='fa fa-edit fa-sm'></i></a>
                        <a onclick='deleteSupplier($suppliers->id)' class='btn btn-danger btn-sm'><i class='fa fa-trash fa-sm'></i></a>
                   </div>";
        })
        ->editColumn('address', function ($suppliers) {
            return Str::limit($suppliers->address, 30, '...');
        })
        ->rawColumns(['actions'])
        ->make(true);
    }
}
