<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'resetPassword', 'forgotPasswordRequestToken']]);
    }

    /**
     * Authenticates the user and generates a JWT token.
     *
     * Validates the email and password from the request.
     * If the validation is successful, attempts to authenticate the user and generate a JWT token.
     * If the authentication fails, returns an error 401 response with an invalid credentials message.
     * If the authentication is successful, returns a successful 200 response with the user details and the JWT token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $jwtToken = Auth::attempt($request->only('email', 'password'));

        if (!$jwtToken) {
            return response([
                'message' => 'The login credentials provided are invalid.',
            ], 401);
        }

        return response([
            'user' => Auth::user(),
            'authorization' => [
                'token' => $jwtToken,
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * Registers a new user.
     *
     * Validates the name, email, and password from the request.
     * If the validation is successful, creates a new user with the provided details.
     * Returns a successful 200 response with a user creation message and the created user details.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response([
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    /**
     * Logs out the authenticated user.
     *
     * Logs out the authenticated user and returns a successful 200 response with a logout message.
     */
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'User logged out successfully.',
        ]);
    }

    /**
     * Refreshes the JWT token for the authenticated user.
     *
     * Returns a successful 200 response with the user details and a refreshed JWT token.
     */
    public function refresh()
    {
        return response([
            'user' => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * Sends a password reset request token to the provided email address.
     *
     * Validates the email address from the request.
     * If the validation is successful, sends a password reset link to the email address.
     * The `auth:clear-resets` command is scheduled to run every fifteen minutes to clear expired password reset tokens.
     * If the link is successfully sent, returns a successful 200 response with a status message.
     * If the link fails to be sent, returns an error 400 response with an email-related error message.
     */
    public function forgotPasswordRequestToken(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? response(['status' => __($status)])
                    : response(['email' => __($status)], 400);
    }

    /**
     * Resets the user's password based on the provided token and new password.
     *
     * Validates the token, email, and password from the request.
     * If the validation is successful, resets the user's password and updates the remember token.
     * If the password reset is successful, returns a successful 200 response with a status message.
     * If the password reset fails, returns an error 400 response with a password-related error message.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? response(['status' => __($status)])
                    : response(['email' => [__($status)]], 400);
    }


    /**
    * Retrieves details of the authenticated user.
    *
    * Checks if the user is authenticated.
    * If successful, retrieves the authenticated user and returns a successful 200 response containing the user details in the UserResource format.
    * If unsuccessful, returns an error 401 response indicating that the request is unauthorized.
    *
    */
    public function authUserDetails()
    {
        return Response(Auth::user(), 200);
    }

}
