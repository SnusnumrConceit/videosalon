<?php

namespace App\Http\Controllers;

use App\Model\Product;
use App\Model\Screen;
use Illuminate\Http\Request;

class ScreenController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $screen = new Screen();
            $screen->fill([
                'url' => $request->input('url'),
                'product_id' => $request->input('product_id'),
            ]);
            $screen->save();
            return response()->json([
                'status' => 'success'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $screens = Screen::paginate(10);
        return response()->json([
            'screens' => $screens
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Screen  $screen
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $result = Screen::where('product_id', $request->input('product_id'))
            ->paginate(10);
        return response()->json([
            'screens' => $result
        ], 200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Screen  $screen
     * @return \Illuminate\Http\Response
     */
    public function form($id)
    {
        $screen = Screen::findOrFail($id);
        $movies = Product::all();
        return response()->json([
            'screen' => $screen,
            'movies' => $movies
        ], 200);
    }

    public function form_info() {
        $movies = Product::all();
        return response()->json([
            'movies' => $movies
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Screen  $screen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        try {
            $screen = Screen::findOrFail($id);
            $screen->fill([
                'url' => $request->input('url'),
                'product_id' => $request->input('product_id'),
            ]);
            $screen->save();
            return response()->json([
                'status' => 'success'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Screen  $screen
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try {
            $screen = Screen::findOrFail($id)->delete();
            return response()->json([
                'status' => 'success'
            ], 200);
        } catch(\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ], 500);
        }


    }
}
