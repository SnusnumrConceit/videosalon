<?php

namespace App\Http\Controllers;

use App\Model\Product;
use App\Model\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $video = new Video();
            $video->fill([
                'name' => $request->input('name'),
                'url' => $request->input('url'),
                'product_id' => $request->input('product_id')
            ]);
            $video->save();
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
        $videos = Video::paginate(10);
        return response()->json([
            'videos' => $videos
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $result = Video::where('name', 'LIKE', '%'.$request->input('keyword').'%')
            ->paginate(10);
        return response()->json([
            'videos' => $result
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function form(int $id)
    {
        $video = Video::findOrFail($id);
        $movies = Product::whereDoesntHave('video')->get();
        return response()->json([
            'video' => $video,
            'movies' => $movies
        ], 200);
    }

    public function form_info()
    {
        $movies = Product::whereDoesntHave('video')->get();
        return response()->json([
            'movies' => $movies
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        try {
            $video = Video::findOrFail($id);
            $video->fill([
                'name' => $request->input('name'),
                'url' => $request->input('url'),
                'product_id' => $request->input('product_id')
            ]);
            $video->save();
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
     * @param  \App\Model\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try {
            $video = Video::findOrFail($id)->delete();
            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }
}
