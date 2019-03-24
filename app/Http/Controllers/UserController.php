<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Validator;
use App\Product;
use App\RoleUser;

class UserController extends Controller
{
      public $successStatus = 200;
    /**
     * 
     */
    public function login(){
        if(Auth::attempt(['email'=>request('email'),'password'=>request('password')])){

            $user = Auth::user();
            
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['user']  = $user;
            
            return response()->json(['success'=> $success], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorized'], 401);
        }
    }

    /**
     * 
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required',
            'c_password'=>'required|same:password',
            'role' =>'required',
        ]);

        if($validator->fails()){
            return response()->json(['error'=>$validator->error()], 401);
        }

        // $userDetial = $request->input('email');

        // $user = User::findorFail($userDetial);

        // return response()->json($user);die;

        $input = $request->all();
        
        $input['password'] = bcrypt($input['password']);

        $id = $input['role'];
            
        $user = User::create($input);

        $user->roles()->attach($id);

        $success['token'] = $user->createToken('MyApp')->accessToken;

        return response()->json(['success'=>$success], $this->successStatus);
    }

        public function getDetails()
        {
            $user = Auth::user();
            // $sample = Sample::get();
            // $user = User::all()->toArray();
            return response()->json($user);
        }

        public function getAllUsers()
        {
            $user = User::all();

            if($user)
            {

            return response()->json($user);

            }
            else
            {
                return response()->json(['error'=>'Unauthorized'], 404);
            }

           
        }

        public function  singleUser ($id)
        {
            $user = User::findorFail($id);

            return response()->json($user);
        }

        public function updateUser(Request $request, $id)
        {
            $data = $request->all();

            $this->validate($request,[
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'role' =>'' 
            ]);

            $user = User::findorFail($id)
                ->update([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'role' => $data['role'],
                    'password' => $data['password']
                ]);

            return response()->json('updated user');
        }

        public function deleteUser($id)
        {

            $user = User::findorFail($id);

            $user->roles()->detach();

            $user->delete();

            if($user){
                return response()->json('success deleted!');
            }
            else{
                return response()->json('failed to delete user');
            }
        }
}
