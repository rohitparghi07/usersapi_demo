<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\HobbyResource;
use App\User;
use App\Hobbiy;

class HobbyController extends Controller
{
    public function updatehobbies(Request $request) {

        $data= $request->all();
        $user = User::find($data['user_id']);

         // update hobbies  
         if(isset($data['hobbies']) && count($data['hobbies']) ){
            Hobbiy::where('user_id','=',$data['user_id'])->delete();
            foreach ($data['hobbies'] as $key => $value) {
                Hobbiy::create(['user_id'=>$data['user_id'],'name'=>$value['name']]);
            }
        }
        $user = $user->load('hobbies');

        return response([ 'user' => new HobbyResource($user), 'message' => 'Hobbies updated successfully'], 200);
    }
}
