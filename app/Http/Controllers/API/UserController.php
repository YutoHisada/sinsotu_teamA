<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserForList as UserForListResource;
use App\Http\Resources\EmployeeForList as EmployeeForListResource;
use App\Http\Resources\EmployeeForShow as EmployeeForShowResource;
use App\Http\Resources\EmployeeSelector as EmployeeSelectorResource;
use App\Http\Resources\EmployeeForSelector as EmployeeForSelectorResource;
use App\Employee;
use App\User;

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
        logger($users);
        return UserForListResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $count = User::where([
            ['name', $request->user['name']]
        ])->count();
        if ($count) {
            return response()->json([
                'result' => false,
                'errorMessage' => 'ユーザIDは既に登録されています。'
            ]);
        }
        DB::transaction(function () use ($request) {
            // $position = Employee::max('position');
            // $position++;

            $user = new User;
            $user->name =  $request->user['name'];
            // $user->email = $request->employee['email'] ?: '';
            $user->password = bcrypt($request->user['password']);
            $user->is_admin = $request->user['is_admin'];
            // $user->is_leader = $request->employee['is_leader'];
            $user->save();
        });

        return response()->json([
            'result' => true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserForListResource($user);
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
        $count = User::where([
            ['id', '<>', $user->id],
            ['name', $request->user['name']]
        ])->count();
        if ($count) {
            return response()->json([
                'result' => false,
                'errorMessage' => 'ユーザIDは既に登録されています。'
            ]);
        }

        DB::transaction(function () use ($request, $user) {
            $user = $this->user;
            if (!isset($user)) $user = new User;
            $user->name =  $request->user['name'];
            // $user->email = $request->employee['email'] ?: '';
            if (array_key_exists('password', $request->user) && $request->user['password']) {
                $user->password = bcrypt($request->user['password']);
            }
            $user->is_admin = $request->user['is_admin'];
            $user->is_leader = $request->user['is_leader'];
            $user->save();
        });

        return response()->json([
            'result' => true,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $user)
    {
        DB::transaction(function () use ($user) {
            if (isset($user->id)) {
                $user->delete();
            }
            $user->delete();
        });
    }

    /**
     * セレクトボックス用
     *
     * @return \Illuminate\Http\Response
     */
    // public function selector()
    // {
    //     $employees = Employee::all();
    //     return EmployeeSelectorResource::collection($employees);
    // }

    /**
     * フルネーム＠検索用
     *
     * @return \Illuminate\Http\Response
     */
    // public function fullname()
    // {
    //     $employees = Employee::all();
    //     return EmployeeForSelectorResource::collection($employees);
    // }
}
