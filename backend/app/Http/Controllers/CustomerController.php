<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\CustomerService;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function list(Request $request){
        return $this->customerService->listCustomers($request);
    }

    public function add(Request $request){
        return $this->customerService->addCustomer($request);
    }

    public function addCustomerAccount(Request $request){
        return $this->customerService->addCustomerAccount($request);
    }
}
