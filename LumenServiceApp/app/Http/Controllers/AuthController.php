<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function register(Request $request) 
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users|string',
            'password' => 'required|confirmed'
        ]);

        $input = $request->all();

        $validationRules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users|string',
            'password' => 'required|confirmed'
        ];

        $validator = Validator::make($input, $validationRules);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = app('hash')->make($request->input('password'));
       $user->save();

        return response()->json($user, 200);
    }
}
