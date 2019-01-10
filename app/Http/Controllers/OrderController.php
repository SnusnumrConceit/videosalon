<?php

namespace App\Http\Controllers;

use App\Model\Order;
use App\Model\Product;
use App\User;
use Illuminate\Http\Request;
use App\Model\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class OrderController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $order = new Order();
            $order->fill([
                'user_id' => $request->input('user_id'),
                'product_id' => $request->input('product_id')
            ]);
            $order->save();

            $user = User::findOrFail($request->input('user_id'));
            $product = Product::findOrFail($request->input('product_id'));

            $log = new Log();
            $log->fill([
                'operation' => 'добавление заказа',
                'object' => 'Пользователь: '.$user->last_name.' '.$user->first_name.' Заказ: '.$product->name.' '.$product->activity
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $orders = Order::with(['product', 'user'])->paginate(10);
        return response()->json([
            'orders' => $orders
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $orders = Order::whereHas('user', function ($q) use ($request) {
            $q->where('last_name', 'LIKE', '%'.$request->input('keyword').'%');
        })->with(['user', 'product'])->paginate(10);
        return response()->json([
            'orders' => $orders
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function form(int $id)
    {
        $order = Order::with(['product', 'user'])->findOrFail($id);
        return response()->json([
            'order' => $order
        ], 200);
    }

    public function form_info()
    {
        $users = User::where('role_id', 1)->get();
        $products = Product::all();
        return response()->json([
            'users' => $users,
            'movies' => $products
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->fill([
                'user_id' => $request->input('user_id'),
                'product_id' => $request->input('product_id')
            ]);
            $order->save();

            $user = User::findOrFail($request->input('user_id'));
            $product = Product::findOrFail($request->input('product_id'));

            $log = new Log();
            $log->fill([
                'operation' => 'обновление сведений о заказе',
                'object' => 'Пользователь: '.$user->last_name.' '.$user->first_name.' Заказ: '.$product->name.' '.$product->activity
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
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try {
            $order = Order::findOrFail($id);
            $data = $order;
            $order->delete();

            $user = User::findOrFail($data->user_id);
            $product = Product::findOrFail($data->product_id);

            $log = new Log();
            $log->fill([
                'operation' => 'удаление заказа',
                'object' => 'Пользователь: '.$user->last_name.' '.$user->first_name.' Заказ: '.$product->name.' '.$product->activity
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

    public function buy(Request $request)
    {
        if (! $user = JWTAuth::authenticate($request->token)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Вы не авторизовались'
            ], 500);
        }
        Order::where('user_id', $user->id)->delete();
        return response()->json([], 200);
    }
}
