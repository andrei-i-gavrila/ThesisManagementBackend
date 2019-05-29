<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\ResetPasswordToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /**
     * @param Request $request
     * @throws ValidationException
     */
    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

    }

    /**
     * @param Request $request
     * @throws ValidationException
     */
    public function sendEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'email|required'
        ]);

        $resetToken = ResetPasswordToken::create([
            'token' => Str::random(),
            'email' => $request->input('email')
        ]);

        Mail::to($resetToken->email)->send(new ResetPasswordMail($resetToken));

        return response()->json();
    }
}
