<?php

namespace App\Http\Controllers\Clients\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Clients\Account\UpdateProfileRequest;

class ProfileController extends Controller
{
    public function index()
    {
        return view('clints.account.profile', ['user' => auth()->user()]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = auth()->user();
        $user->update($request->validated());

        return back()->with('success', 'تم تحديث الملف الشخصي بنجاح.');
    }
}
