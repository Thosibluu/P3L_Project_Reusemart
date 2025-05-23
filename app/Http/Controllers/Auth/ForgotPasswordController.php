<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\Penitip;
use App\Models\Pembeli;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('reset-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'registered_email_or_id' => 'required',
            'send_to_email' => 'required|email',
        ]);

        $user = Penitip::where('id_penitip', $request->registered_email_or_id)->first();
        if (!$user) {
            $user = Pembeli::where('alamat_email', $request->registered_email_or_id)->first();
        }

        if (!$user) {
            return back()->withErrors(['registered_email_or_id' => 'Email atau ID tidak ditemukan.']);
        }

        $broker = $user instanceof Penitip ? 'penitip' : 'pembeli';
        $status = Password::broker($broker)->sendResetLink(
            ['email' => $request->send_to_email]
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['send_to_email' => __($status)]);
    }
}