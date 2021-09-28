<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;
use App\Hobbiy;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response([ 'users' => UserResource::collection($users), 'message' => 'Users Retrieved successfully'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'fname' => 'required|max:35',
            'lname' => 'required|max:35',
            'email' => 'required|max:255',
            'password' => 'required',
            'mobile' => 'required'
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $user = User::create($data);

        // insert hobbies data
        if(isset($data['hobbies']) && count($data['hobbies']) ){
            foreach ($data['hobbies'] as $key => $value) {
                Hobbiy::create(['user_id'=>$user->id,'name'=>$value['name']]);
            }
        }

        $user = $user->load('hobbies');

        return response([ 'user' => new UserResource($user), 'message' => 'User Created successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response([ 'user' => new UserResource($user), 'message' => 'User Retrieved successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'fname' => 'required|max:35',
            'lname' => 'required|max:35',
            'email' => 'required|max:255',
            'mobile' => 'required'
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        // user update
        $user->update($request->all());

        // update hobbies  
        if(isset($data['hobbies']) && count($data['hobbies']) ){
            Hobbiy::where('user_id','=',$user->id)->delete();
            foreach ($data['hobbies'] as $key => $value) {
                Hobbiy::create(['user_id'=>$user->id,'name'=>$value['name']]);
            }
        }
        $user = $user->load('hobbies');

        return response([ 'user' => new UserResource($user), 'message' => 'User updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response(['message' => 'User has been Deleted']);
    }


    // get user by hobbies
    public function getUserByHobbies(Request $request)
    {
        $data = $request->all();
        $search =$data['search'];

        $users= User::select('users.*')
                    ->with(['hobbies'])
                    ->join('hobbies','hobbies.user_id','=','users.id')
                    ->orWhere('hobbies.name', 'like', '%' .$search . '%')
                    ->orderBy('users.fname')
                    ->get();

        return response([ 'users' => new UserResource($users), 'message' => 'Users data list'], 200);
    }

}
