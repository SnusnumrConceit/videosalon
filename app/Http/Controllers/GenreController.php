<?php

namespace App\Http\Controllers;

use App\Model\Genre;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Matcher\RedirectableUrlMatcher;
use App\Model\Log;

class GenreController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $genre = new Genre();
            $genre->fill(['name' => $request->input('name')]);
            $genre->save();

            $log = new Log();
            $log->fill([
                'operation' => 'добавление жанра',
                'object' => $genre->name
            ]);
            $log->save();

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

    public function search(Request $request)
    {
        $genres = Genre::where('name', 'LIKE', '%'.$request->input('keyword').'%')->paginate(10);
        return response()->json([
            'genres' => $genres
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $genres = Genre::paginate(10);
        return response()->json([
            'genres' => $genres
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function form(int $id)
    {
        $genre = Genre::findOrFail($id);
        return response()->json([
            'genre' => $genre
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        try {
            $genre = Genre::findOrFail($id);
            $genre->fill(['name' => $request->input('name')]);
            $genre->save();

            $log = new Log();
            $log->fill([
                'operation' => 'обновление информации о жанре',
                'object' => $genre->name
            ]);
            $log->save();

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
     * @param  \App\Model\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try {
            $genre = Genre::findOrFail($id);
            $data = $genre;
            $genre->delete();

            $log = new Log();
            $log->fill([
                'operation' => 'удаление жанра',
                'object' => $data->name
            ]);
            $log->save();

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
