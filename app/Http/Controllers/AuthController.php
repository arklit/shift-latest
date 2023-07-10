<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @var CustomerService
     */
    private CustomerService $customerService;

    /**
     * @param CustomerService $customerService
     */
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * @throws \Exception
     */
    public function login(Request $request)
    {
        if (auth()->guard(Customer::GUARD)->user()) {
            throw new \Exception('Вы уже авторизованы.');
        }

        $validator = AuthRequest::login($request->all());
        $this->customerService->authAttempt($validator->validated());

        $user = auth()->guard(Customer::GUARD)->user();

        $token = $user->createToken('customer_access')->plainTextToken;

        return response()->json(['token' => $token, 'userData' => $user]);
    }

    public function register(Request $request)
    {
        $validator = AuthRequest::register($request->all());

        return response()->json($this->customerService->register($validator->validated()));
    }

    public function logout(Request $request)
    {
        auth()->guard(Customer::GUARD)->user()->currentAccessToken()->delete();

        return true;
    }

    public function getCurrentUser(Request $request)
    {
        return response()->json(\auth()->guard(Customer::GUARD)->user());
    }
}
