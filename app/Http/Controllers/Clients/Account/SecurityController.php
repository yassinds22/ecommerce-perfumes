<?php

namespace App\Http\Controllers\Clients\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Clients\Account\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;

class SecurityController extends Controller
{
    public function index()
    {
        return view('clints.account.security', ['user' => auth()->user()]);
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'تم تحديث كلمة المرور بنجاح.');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
        ]);

        $user = auth()->user();
        $user->update(['email' => $request->email]);

        return back()->with('success', 'تم تحديث البريد الإلكتروني بنجاح.');
    }
}
