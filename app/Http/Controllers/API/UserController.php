<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Users\CreateUserRequest;
use Illuminate\Http\Response;
use App\Http\Requests\Users\UpdateUserRequest;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users=User::paginate(5);
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {


        try{
                DB::beginTransaction();
                User::create([
                        'name'=>$request->name,
                        'email'=>$request->email,
                        'phone'=>$request->phone,
                        'address'=>$request->address,
                        'password'=>Hash::make($request->password),
                        'role'=>$request->role,
                ]);

                DB::commit();

        }
        catch( Exception $e){
                DB::rollback();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user=User::findOrFail($id);
        try{
                DB::beginTransaction();
                    $user->update([
                        'name'=>$request->name,
                        'email'=>$request->email,
                        'phone'=>$request->phone,
                        'address'=>$request->address,
                        'role'=>$request->role,
                    ]);
                DB::commit();
        }
        catch(Exception $e){
            DB::rollback();
        }
        return $request->all();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user=User::findOrFail($id);
        $user->delete();
        return ['Message'=>'userDelete'];
    }
}
