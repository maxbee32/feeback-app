<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin;
use App\Models\Complain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function sendResponse($data, $message, $status = 200){
        $response =[
            'data' => $data,
            'message' => $message
        ];
        return response()->json($response, $status);
     }






     public function __construct(){
        $this->middleware('auth:api', ['except'=>['adminSignUp', 'branchSignUp','adminLogin','getAllComplains','getComplainToday']]);
    }
    //admin account created
    public function adminSignUp(Request $request){
        $validator = Validator::make($request->all(), [

            'email' => ['required','email'],
            'password' => ['required']

        ]);

        if($validator->stopOnFirstFailure()-> fails()){
            return $this->sendResponse([
                'success' => false,
                'data'=> $validator->errors(),
                'message' => 'Validation Error'
            ], 400);

        }
           Admin::create(array_merge(
                    $validator-> validated(),
                    ['password'=>bcrypt($request->password)]
                ));


                return $this->sendResponse([
                    'success' => true,
                    'message' => "Admin account created successfully"
                ],200);
}


    //register branch users
    public function branchSignUp(Request $request){
        $validator = Validator::make($request->all(), [

            'email' => ['required','email'],
            'password' => ['required'],
            'branch'=>['required']

        ]);

        if($validator->stopOnFirstFailure()-> fails()){
            return $this->sendResponse([
                'success' => false,
                'data'=> $validator->errors(),
                'message' => 'Validation Error'
            ], 400);

        }
           User::create(array_merge(
                    $validator-> validated(),
                    ['password'=>bcrypt($request->password)]
                ));


                return $this->sendResponse([
                    'success' => true,
                    'message' => "branch account created successfully"
                ],200);
}

public function adminLogin(Request $request){
    $validator = Validator::make($request->all(), [
        'email'=>['required','email:rfc,filter,dns'],
        'password'=> ['required','string'],
        // 'branch'=>['required','string']
    ]);


    if($validator->stopOnFirstFailure()-> fails()){
        return $this->sendResponse([
            'success' => false,
            'data'=> $validator->errors(),
            'message' => 'Validation Error'
        ], 400);


    }
    return $this->sendResponse([
        'success' => true,
        'message' => "Logged in successfully"
    ],200);


}

public function getAllComplains(){

   return DB::table('complains')
       ->join('users', 'complains.user_id', '=' ,'users.id')
       ->get(array(
            'users.id',
            'branch',
            'phone_number',
            'comment'
       ));

}

public function getComplainToday(){

    return  DB::table('complains')
    ->join('users', 'complains.user_id', '=' ,'users.id')
     -> whereDate('complains.created_at',  Carbon::today())
    ->get(array(
          'users.id',
         'branch',
         'phone_number',
         'comment'
    ));


  //  Complain::whereDate('created_at', DB::raw('CURDATE()'))->get();

 }

}
