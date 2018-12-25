<?php

namespace App\Http\Controllers;

use App\Model\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $product = new Product();
            $product->fill([
                'name' => $request->input('name'),
                'year' => $request->input('year'),
                'description' => $request->input('description'),
                'cover' => $request->input('cover'),
                'price' => $request->input('price'),
                'activity' => $request->input('activity'),
            ]);
            $product->save();
            return response()->json([
                'status' => 'success'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $products = Product::paginate(10);
        return response()->json([
            'products' => $products
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $products = Product::where('name', $request->input('keyword'))
            ->paginate(10);
        return response()->json([
            'products' => $products
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function form(int $id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            'product' => $product
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->fill([
                'name' => $request->input('name'),
                'year' => $request->input('year'),
                'description' => $request->input('description'),
                'cover' => $request->input('cover'),
                'price' => $request->input('price'),
                'activity' => $request->input('activity'),
            ]);
            $product->save();
            return response()->json([
                'status' => 'success'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try {
            $product = Product::findOrFail($id)->delete();
            return response()->json([
                'status' => 'success'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ], 500);
        }
    }
}
