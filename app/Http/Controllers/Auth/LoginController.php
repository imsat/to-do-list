<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Login valid user and return token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return $this->response(false, 'Invalid data!', null, 400, $validator->errors());
        }

        $user = User::select(['id', 'name', 'email', 'password'])->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->response(false, 'Wrong email or password!!', null, 403);
        }

        try {
            $token = $user->createToken('api-token')->plainTextToken;
            $userData = $user->only(['id', 'name', 'email']);

            $data = [
                'token' => $token,
                'user' => $userData,
                'message' => 'Login successfully',
            ];
            return $this->response(true, 'Login successfully.', $data);
        } catch (\Exception $e) {
            return $this->response(false, $e->getMessage() ?? 'Something went wrong!', null, 400);
        }

    }

    /**
     * Log out the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->response(true, 'Logout successfully.');
        } catch (\Exception $e) {
            return $this->response(false, $e->getMessage() ?? 'Something went wrong!', null, 400);
        }
    }

}
