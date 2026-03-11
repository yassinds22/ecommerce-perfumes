<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * CustomerController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $customers = $this->userService->getPaginatedCustomers(10);
        $customersCount = $this->userService->getCustomersCount();

        if ($request->ajax()) {
            return view('admin.sections.customers', compact('customers', 'customersCount'))->render();
        }
        
        return view('admin.customers.index', compact('customers', 'customersCount'));
    }

    public function show($id)
    {
        $customer = $this->userService->getCustomerDetails($id);
        return response()->json($customer);
    }

    public function destroy($id)
    {
        $this->userService->deleteUser($id);
        return response()->json(['success' => true, 'message' => 'تم حذف العميل بنجاح']);
    }
}
