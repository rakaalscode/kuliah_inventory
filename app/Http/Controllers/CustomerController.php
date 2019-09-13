<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Customer;
use DataTables;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customer.index');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name'  => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:customers,email',
            'phone' => 'required|max:13|min:8',
            'address' => 'required'
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        Customer::create($request->all());
        return response()->json(['status' => 'success'], 200);
    }

    public function edit($id)
    {
        $customer = Customer::where('id',$id)->first();
        return $customer;
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::where('id', $id)->first();

        $validator = \Validator::make($request->all(), [
            'name'  => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:customers,email,'. $customer->id . ',id',
            'phone' => 'required|max:13|min:8',
            'address' => 'required'
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        $customer->update($request->except('id'));
        return response()->json(['status' => 'success'], 200);
    }

    public function destroy(Request $request,$id)
    {
        $customer = Customer::find($id);
        $customer->delete();
        return response()->json(['status' => 'success'], 200);
    }

    public function data()
    {
        $customers = Customer::all();
        return Datatables::of($customers)
        ->editColumn('actions', function ($customers) {
            return "<div class='text-center'>
                        <a onclick='editModal($customers->id)' class='btn btn-warning btn-sm'><i class='fa fa-edit fa-sm'></i></a>
                        <a onclick='deleteCustomer($customers->id)' class='btn btn-danger btn-sm'><i class='fa fa-trash fa-sm'></i></a>
                   </div>";
        })
        ->editColumn('address', function ($customers) {
            return Str::limit($customers->address, 30, '...');
        })
        ->rawColumns(['actions'])
        ->make(true);
    }
}
