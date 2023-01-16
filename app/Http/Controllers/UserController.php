<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $req)
    {
        $fields=$req->validate([
            'name'=>'required|string',
            'email'=>'required|string|unique:users,email',
            'password'=>'required|string|confirmed',
        ]);


        $user=User::Create([
            'name'=>$fields['name'],
            'email'=>$fields['email'],
            'password'=>bcrypt($fields['password']),
        ]);
        return response(['success'=>$user]);//201:: Done & Something was created

        // $user = new User();
        // $user->name = $req->name;
        // $user->email = $req->email;
        // // $user->name = $req->input('name');
        // //  $user->email = $req->input('email');
        // $user->password = Hash::make($req->password);
        // $bool = $user->save();
        // //event(new Registered($user));
        // //decode-encode password
        // if ($bool) {
        //     return (["success" => "Signed up successfully"]);
        // }
        // return (["error" => "Error occured while finishing the process"]);
    }

    public function login(Request $req)
    {
        // $user_email = User::where("email", $req->name)->first();
        // $user_name = User::where("name", $req->name)->first();
        // $user = $user_name;
        // if ($user_email) {
        //     $user = $user_email;
        // }
        // if (!($user) || !hash::check($req->password, $user->password)) {
        //     return (['error' => 'Email or password is not matched', "status" => 403]);
        // }
        // return (['success' => $user, "status" => 201]);
        $fields=$req->validate([
            'email'=>'required|string',
            'password'=>'required|string'
        ]);
        $user=User::where('email', $fields['email'])->first();
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response(['error'=>'Bad creds']);//unauthorized
        }

        return response(["success"=>$user]);//201:: Done & Something was created
    }
    public function test()
    {
        // return {"succes","Api working just fine"};
        //return response()->json(array('succes' => 'Api working just fine'));
        return User::All();
    }
}
