<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = User::where('role', 'Customer')
            ->withCount('orders')
            ->withSum('orders', 'total')
            ->latest()
            ->paginate(10);

        $customersCount = User::where('role', 'Customer')->count();

        if ($request->ajax()) {
            return view('admin.sections.customers', compact('customers', 'customersCount'))->render();
        }
        
        return view('admin.customers.index', compact('customers', 'customersCount'));
    }

    public function show($id)
    {
        $customer = User::with(['orders.products'])->findOrFail($id);
        return response()->json($customer);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => true, 'message' => 'تم حذف العميل بنجاح']);
    }
}
