<?php

namespace App\Http\Controllers\Clients\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UserAddress;
use App\Http\Requests\Clients\Account\AddressRequest;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = auth()->user()->addresses()->latest()->get();
        return view('clints.account.addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('clints.account.addresses.create');
    }

    public function store(AddressRequest $request)
    {
        $user = auth()->user();
        
        if ($request->is_default) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create($request->validated());

        return redirect()->route('account.addresses.index')->with('success', 'تم إضافة العنوان بنجاح.');
    }

    public function edit(UserAddress $address)
    {
        $this->authorizeUser($address);
        return view('clints.account.addresses.edit', compact('address'));
    }

    public function update(AddressRequest $request, UserAddress $address)
    {
        $this->authorizeUser($address);

        if ($request->is_default) {
            auth()->user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($request->validated());

        return redirect()->route('account.addresses.index')->with('success', 'تم تحديث العنوان بنجاح.');
    }

    public function destroy(UserAddress $address)
    {
        $this->authorizeUser($address);
        $address->delete();

        return back()->with('success', 'تم حذف العنوان بنجاح.');
    }

    public function setDefault(UserAddress $address)
    {
        $this->authorizeUser($address);
        
        auth()->user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'تم تعيين العنوان كافتراضي.');
    }

    protected function authorizeUser(UserAddress $address)
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
