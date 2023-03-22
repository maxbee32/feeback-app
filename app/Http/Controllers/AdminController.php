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
        $this->middleware('auth:api', ['except'=>['adminSignUp', 'branchSignUp','adminLogin','getAllComplains','getComplainToday','getAllBranch']]);
    }
    //admin account created
    public function adminSignUp(Request $request){
        $validator = Validator::make($request->all(), [

            'email' => ['required','email','unique:admins'],
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

            'email' => ['required','email','unique:users'],
            'password' => ['required'],
            'branch'=> ['required']

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



                if(!$token = auth()->attempt($validator->validated())){
                    return $this->sendResponse([
                        'success' => false,
                        'data'=> $validator->errors(),
                        'message' => 'Invalid login credentials'
                    ], 400);


                }


                return response()->json([
                    'access_token' => $token,
                    'token_type' => 'bearer',
                     'expires_in' => auth()->factory()->getTTL()* 60,
                     'user'=>auth()->user(),
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
   $result = DB::table('complains')
       ->join('users', 'complains.user_id', '=' ,'users.id')
       ->get(array(
            'users.id',
            'branch',
            'phone_number',
            'comment'
       ));

       return $this ->sendResponse([
        'success' => true,
         'message' => $result,

       ],200);


}

public function getComplainToday(Request $request){
    $validator = Validator::make($request->all(), [
        'start_date' => ['required','date'],
        'end_date' => ['required','date'],

    ]);


    if($validator-> fails()){
        return $this->sendResponse([
            'success' => false,
            'data'=> $validator->errors(),
            'message' => 'Validation Error'
        ], 400);

    }

    $startDate =carbon::parse($request->start_date);
    $endDate = carbon::parse($request->end_date);

    $results=  DB::table('complains')
    ->join('users', 'complains.user_id', '=' ,'users.id')
     -> whereBetween(DB::raw('DATE(complains.created_at)'),  [$startDate, $endDate])
    ->get(array(
          'users.id',
         'branch',
         'phone_number',
         'comment'
    ));

    return $this ->sendResponse([
        'success' => true,
         'message' => $results,

       ],200);



 }

 public function getAllBranch(){
    $res= DB::table('users')
    ->get(array(
        'email',
        'branch'
    ));
    return $this ->sendResponse([
        'success' => true,
         'message' => $res,

       ],200);


 }

}
