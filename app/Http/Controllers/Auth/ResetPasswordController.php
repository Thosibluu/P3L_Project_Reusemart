<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\Penitip;
use App\Models\Pembeli;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        return view('reset-password-form')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Penitip::where('id_penitip', $request->email)->first();
        if (!$user) {
            $user = Pembeli::where('alamat_email', $request->email)->first();
        }

        if (!$user) {
            return back()->withErrors(['email' => 'Email atau ID tidak ditemukan.']);
        }

        $broker = $user instanceof Penitip ? 'penitip' : 'pembeli';
        $status = Password::broker($broker)->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect('/login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}