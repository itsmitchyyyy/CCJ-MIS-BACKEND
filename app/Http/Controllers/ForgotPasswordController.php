<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendCodeResetPassword;
use App\Models\User;
use App\Models\ResetCodePassword;

class ForgotPasswordController extends Controller
{
    public function sendResetCodeEmail(Request $request)
    {
        $data = $request->validate([
            'email' => 'required',
        ]);

        ResetCodePassword::where('email', $data['email'])->delete();

        $data['code'] = mt_rand(10000, 99999);

        $codeData = ResetCodePassword::create($data);

        Mail::to($data['email'])->send(new SendCodeResetPassword($codeData->code));

        return response()->json(['message' => 'Code has been sent to your email'], 200);
    }

    public function checkCode(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|exists:reset_code_passwords,code',
        ]);

        $codeData = ResetCodePassword::where('code', $data['code'])->first();

        if ($codeData->isExpire()) {
            return response()->json(['message' => 'Code has expired'], 422);
        }

        return response()->json(['code' => $codeData->code, 'message' => 'Code is valid'], 200);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $data = $request->validated();
       
        $codeData = ResetCodePassword::where('code', $data['code'])->first();

        if ($codeData->isExpire()) {
            return response()->json(['message' => 'Code has expired'], 422);
        }

        $user = User::where('email', $codeData->email)->first();

        $user->update(['password' => Hash::make($data['password'])]);

        $codeData->delete();

        return response()->json(['message' => 'Password has been reset'], 200);
    }
}