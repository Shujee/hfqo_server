<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Users = User::where('id', '!=', 1)->get(); //return all users except admin
        return UserResource::collection($Users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'email' => 'bail|required|regex:/.+@.+\..+/|unique:users',
                'password' => 'required|min:6|max:15'
            ],
            [
                'email.required' => 'Email address is required.',
                'email.regex' => 'Email address is not valid.',
                'email.unique' => 'Another user will the same e-mail address is already registered.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 6 characters long.',
                'password.max' => 'Password cannot be longer than 15 characters.',
            ]
        );

        $user = new User();
        $user->fill($request->all());
        $user->password = Hash::make($request['password']);
        
        $user->save();

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate(
            [
                'name' => 'required',
            ],
            [
                'name.required' => 'Name is required.',
            ]
        );    

        $user->name = $request['name'];

        if($request['password'] !== null && $request['password'] !== "") {

            $request->validate(
                [
                    'password' => 'required|min:6|max:15'
                ],
                [
                    'password.required' => 'Password is required.',
                    'password.min' => 'Password must be at least 6 characters long.',
                    'password.max' => 'Password cannot be longer than 15 characters.',
                ]
            );    
    
            
            $user->password  = Hash::make($request['password']);  
        }

        $user->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if($user->id != 1)
            $user->delete();
    }
}
