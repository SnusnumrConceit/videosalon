<?php

namespace App\Http\Controllers;

use App\Model\Audio;
use App\Model\AudioProduct;
use App\Model\GenreProduct;
use App\Model\Product;
use App\Model\Genre;
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
            if ($request->input('audios') !== null) {
                $product_audios =  $request->input('audios');
                $pa = new AudioProduct();
                AudioProduct::where('product_id', $product->id)->delete();
                foreach ($product_audios as $audio) {
                    if (! isset($audio)) continue;
                    $pa->insert([
                        'audio_id' => $audio,
                        'product_id' => $product->id,
                    ]);
                }
            }
            if ($request->input('genres') !== null) {
                $product_genres =  $request->input('genres');
                $pg = new GenreProduct();
                GenreProduct::where('product_id', $product->id)->delete();
                foreach ($product_genres as $genre) {
                    if (! isset($genre)) continue;
                    $pg->insert([
                        'genre_id' => $genre,
                        'product_id' => $product->id,
                    ]);
                }
            }
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
        $products = Product::where('name', 'LIKE', '%'.$request->input('keyword').'%')
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
        $product = Product::with(['audios', 'genres'])->findOrFail($id);
        return response()->json([
            'product' => $product
        ], 200);
    }

    public function form_info()
    {
        $audios = Audio::all();
        $genres = Genre::all();
        return response()->json([
            'audios' => $audios,
            'genres' => $genres
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
            if ($request->input('audios') !== null) {
                $product_audios =  $request->input('audios');
                $pa = new AudioProduct();
                AudioProduct::where('product_id', $product->id)->delete();
                foreach ($product_audios as $audio) {
                    if (! isset($audio)) continue;
                    $pa->insert([
                        'audio_id' => $audio,
                        'product_id' => $product->id,
                    ]);
                }
            }
            if ($request->input('genres') !== null) {
                $product_genres =  $request->input('genres');
                $pg = new GenreProduct();
                GenreProduct::where('product_id', $product->id)->delete();
                foreach ($product_genres as $genre) {
                    if (! isset($genre)) continue;
                    $pg->insert([
                        'genre_id' => $genre,
                        'product_id' => $product->id,
                    ]);
                }
            }
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
            AudioProduct::where('product_id', $id)->delete();
            GenreProduct::where('product_id', $id)->delete();
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
