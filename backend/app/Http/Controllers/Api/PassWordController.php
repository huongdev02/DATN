<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PassWordController extends Controller
{
    public function sendResetLink(Request $request)
    {
        // Validate the email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Send the reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Password reset link sent successfully. Please check your email.'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to send password reset link. Email not found.'
            ], 404);
        }
    }

    // Return the view for updating the password
    public function showResetForm($token)
    {
        $email = request()->query('email');
        return response()->json([
            'message' => 'Please enter a new password.',
            'token' => $token,
            'email' => $email
        ], 200);
    }

    // Reset the password
    public function resetPassword(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Perform password reset
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Password reset successful. Please log in.'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to reset password.',
                'errors' => $status
            ], 400);
        }
    }
}
