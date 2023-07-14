<?php

namespace App\Http\Controllers;

use App\Constants\UserConstants;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response(["data" => $users]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create(
            [
                "name" => $request['name'],
                "email" => $request['email'],
                "password" => $request['password'],
                'role' => UserConstants::ROLE_CUSTOMER
            ]
        );

        return response(["data" => $user]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user ->update(
            [
                "name" => $request['name'],
                "email" => $request['email'],
            ]
        );

        return response(["data" => $user]);
    }

    /**
     * Store a user as Admin in storage.
     */
    public function createAdmin(StoreUserRequest $request)
    {
        $user = User::create(
            [
                "name" => $request['name'],
                "email" => $request['email'],
                "password" => $request['password'],
                'role' => UserConstants::ROLE_ADMIN
            ]
        );

        return response(["data" => $user]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response(["data" => $user]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response(["data" => "Success"]);
    }
}
