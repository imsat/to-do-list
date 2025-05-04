<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Handles user registration by validating the request data, creating or updating the user,
     * and generating an API token for the user upon successful registration.
     *
     * @param Request $request The incoming HTTP request containing user data.
     * @return mixed A response indicating the success or failure of the registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->response(false, 'Please provide valid data!', null, 400, $validator->errors());
        }

        try {
            $data = $request->only(['name', 'email', 'password']);
            $user = $this->createOrUpdate($data);

            if (!empty($user)) {
                $token = $user->createToken('api-token')->plainTextToken;
                $userData = [
                    'token' => $token,
                    'user' => $user->only(['id', 'name', 'email']),
                ];
                return $this->response(true, 'User created successfully.', $userData);
            }

            return $this->response(false, 'Something went wrong!', null, 400);

        } catch (\Exception $e) {
            return $this->response(false, $e->getMessage() ?? 'Something went wrong!', null, 400);
        }
    }

    /**
     * Creates or updates a user record in the database.
     *
     * @param array $data The data to fill the user with.
     * @param \App\Models\User|null $user An existing user instance, or null to create a new user.
     * @return \App\Models\User The created or updated user instance.
     */
    public function createOrUpdate($data = [], $user = null)
    {
        if (blank($user)) {
            $user = new User();
        }

        $user->fill($data);
        $user->save();
        return $user;
    }
}
