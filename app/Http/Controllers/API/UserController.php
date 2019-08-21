<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Image;
use Mockery\Exception;

class UserController extends Controller
{

    public function index()
    {
        $users = User::paginate(5);
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

        try {
            DB::beginTransaction();
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            DB::commit();

        } catch (Exception $e) {
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

    }
    public function profile()
    {
        return auth('api')->user();

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
        $user = User::findOrFail($id);
        try {
            DB::beginTransaction();
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'role' => $request->role,
            ]);
            DB::commit();
        } catch (Exception $e) {
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
        $this->authorize('isAdmin');

        $user = User::findOrFail($id);
        $user->delete();
        return ['Message' => 'userDelete'];
    }
    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();

        $currentImg = $user->img;
        if ($request->img != $currentImg) {
            $name = time() . '.' . explode('/', explode(':', substr($request->img, 0, strpos($request->img, ';')))[1])[1];
            Image::make($request->img)->save(public_path('images/profile/') . $name);
            $request->img = $name;
        }

        $userImg = public_path('images/profile/') . $currentImg;
        if (file_exists($userImg)) {
            unlink($userImg);
        }
        $status = $user->update([
            $user->img = $request->img,
            $user->name = $request->name,
            $user->email = $request->email,
            $user->role = $request->role,
            $user->address = $request->address,
            $user->phone = $request->phone,
        ]);

        return response()->json([
            'sataus' => $status,
        ]);

    }

}