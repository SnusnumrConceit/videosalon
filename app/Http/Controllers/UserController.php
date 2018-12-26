<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Model\Log;

class UserController extends Controller
{
    public function login(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required'
            ]);
            $credentials = $request->only('email', 'password');
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Неверный логин или пароль'
                ]);
            }
        } catch (JWTException $error ) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Could\'t create token'
            ]);
        }
        $user = Auth::attempt($credentials);
        if (Auth::user()->role_id !== 2) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Неверный логин или пароль'
            ]);
        }
        return response()->json([
            'token' => $token,
            'user' => Auth::user()
        ], 200);
    }

    public function logout()
    {
        Auth::logout();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $user = new User();
            $user->fill([
                'email' => $request->input('email'),
                'last_name' => $request->input('last_name'),
                'first_name' => $request->input('first_name'),
                'password' => Hash::make($request->input('password')),
                'role_id' => $request->input('role_id')
            ]);
            $user->save();

            $log = new Log();
            $log->fill([
                'operation' => 'добавление пользователя',
                'object' => $user->email.' '.$user->last_name.' '.$user->first_name
            ]);
            $log->save();

            return response()->json([
                'status' => 'success'
            ]);
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
        $users = User::paginate(10);
        return response()->json([
            'users' => $users
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $users = User::where('last_name', 'LIKE', '%'.$request->input('keyword').'%')->paginate(10);
        return response()->json([
            'users' => $users
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function form(int $id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->fill([
                'email' => $request->input('email'),
                'last_name' => $request->input('last_name'),
                'first_name' => $request->input('first_name'),
                'password' => Hash::make($request->input('password')),
                'role_id' => $request->input('role_id')
            ]);
            $user->save();

            $log = new Log();
            $log->fill([
                'operation' => 'обновление пользователя',
                'object' => $user->email.' '.$user->last_name.' '.$user->first_name
            ]);
            $log->save();

            return response()->json([
                'status' => 'success'
            ]);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try {
            $user = User::findOrFail($id);
            $data = $user;
            $user->delete();

            $log = new Log();
            $log->fill([
                'operation' => 'удаление пользователя',
                'object' => $data->email.' '.$data->last_name.' '.$data->first_name
            ]);
            $log->save();

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

    public function getUser()
    {
        return response()->json([ 'user' => Auth::user(), 'status' => 'success'], 200);
    }
}
