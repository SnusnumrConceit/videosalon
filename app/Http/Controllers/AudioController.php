<?php

namespace App\Http\Controllers;

use App\Model\Audio;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Matcher\RedirectableUrlMatcher;

class AudioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $audio = new Audio();
            $audio->fill(['name' => $request->input('name')]);
            $audio->save();
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
    public function store(Request $request)
    {
        $audios = Audio::paginate(10);
        return response()->json([
            'audios' => $audios
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Audio  $audio
     * @return \Illuminate\Http\Response
     */
    public function form(int $id)
    {
        $audio = Audio::findOrFail($id);
        return response()->json([
            'audio' => $audio
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Audio  $audio
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $result = Audio::where('name', 'LIKE', '%'.$request->input('keyword').'%')
            ->paginate(10);
        return response()->json([
            'audios' => $result
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Audio  $audio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        try {
            $audio = Audio::findOrFail($id);
            $audio->fill(['name' => $request->input('name')]);
            $audio->save();
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
     * @param  \App\Model\Audio  $audio
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try {
            $audio = Audio::findOrFail($id)->delete();
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
