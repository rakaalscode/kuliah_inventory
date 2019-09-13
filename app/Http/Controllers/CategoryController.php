<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        return view('category.index');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:100'
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        Category::create($request->all());
        return response()->json(['status' => 'success'], 200);
    }

    public function edit($id)
    {
        $category = Category::where('id',$id)->first();
        return $category;
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:100'
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        $category = Category::where('id', $id)->first();
        $category->update($request->except('id'));
        return response()->json(['status' => 'success'], 200);
    }

    public function destroy(Request $request,$id)
    {
        $category = Category::find($id);
        $category->delete();
        return response()->json(['status' => 'success'], 200);
    }

    public function data()
    {
        $categories = Category::all();
        return Datatables::of($categories)
        ->editColumn('actions', function ($categories) {
            return "<div class='text-center'>
                        <a onclick='editModal($categories->id)' class='btn btn-warning btn-sm'><i class='fa fa-edit fa-sm'></i></a>
                        <a onclick='deleteCategory($categories->id)' class='btn btn-danger btn-sm'><i class='fa fa-trash fa-sm'></i></a>
                   </div>";
        })
        ->rawColumns(['actions'])
        ->make(true);
    }
}
