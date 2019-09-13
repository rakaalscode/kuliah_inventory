<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Sales;
use DataTables;

class SalesController extends Controller
{
    public function index()
    {
        return view('sales.index');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name'  => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:sales,email',
            'phone' => 'required|max:13|min:8',
            'address' => 'required'
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        Sales::create($request->all());
        return response()->json(['status' => 'success'], 200);
    }

    public function edit($id)
    {
        $sales = Sales::where('id',$id)->first();
        return $sales;
    }

    public function update(Request $request, $id)
    {
        $sales = Sales::where('id', $id)->first();

        $validator = \Validator::make($request->all(), [
            'name'  => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:sales,email,'. $sales->id . ',id',
            'phone' => 'required|max:13|min:8',
            'address' => 'required'
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        $sales->update($request->except('id'));
        return response()->json(['status' => 'success'], 200);
    }

    public function destroy(Request $request,$id)
    {
        $sales = Sales::find($id);
        $sales->delete();
        return response()->json(['status' => 'success'], 200);
    }

    public function data()
    {
        $sales = Sales::all();
        return Datatables::of($sales)
        ->editColumn('actions', function ($sales) {
            return "<div class='text-center'>
                        <a onclick='editModal($sales->id)' class='btn btn-warning btn-sm'><i class='fa fa-edit fa-sm'></i></a>
                        <a onclick='deleteSales($sales->id)' class='btn btn-danger btn-sm'><i class='fa fa-trash fa-sm'></i></a>
                   </div>";
        })
        ->editColumn('address', function ($sales) {
            return Str::limit($sales->address, 30, '...');
        })
        ->rawColumns(['actions'])
        ->make(true);
    }
}
