<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;

class AuthController extends Controller
{
    public function register(Request $req, $userType)
    {
        try {
            if ($userType === 'admin') {
                // Registering an admin
                $name = $req->input('name');
                $email = $req->input('email');
                $password = bcrypt($req->input('password')); // Hash the password

                DB::insert("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)", [
                    $name, $email, $password
                ]);
                return response()->json(['message' => 'Admin registered successfully'], 201);
            } else {
                // Registering a customer
                $firstName = $req->input('first_name');
                $lastName = $req->input('last_name');
                $email = $req->input('email');
                $password = bcrypt($req->input('password')); // Hash the password
                $phoneNumber = $req->input('phone_number');
                $address = $req->input('address');
                $city = $req->input('city');
                $country = $req->input('country');

                DB::insert("INSERT INTO customers (first_name, last_name, email, phone_number, address, city, country, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [
                    $firstName, $lastName, $email, $phoneNumber, $address, $city, $country, $password
                ]);
                return response()->json(['message' => 'Customer registered successfully'], 201);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function loginCustomer(Request $req, $userType = 'customer')
    {
        $credentials = request(['email', 'password']);

        if (!$token = Auth::guard('customer')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = [
            'token' => $token,
            'user' => Auth::guard('customer')->user(),
        ];

        return response()->json(['message' => 'Login successful', 'data' => $data], 200);
    }

    public function loginAdmin(Request $req)
    {
        $credentials = request(['email', 'password']);

        if (!$token = Auth::guard('admin')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = [
            'token' => $token,
            'user' => Auth::guard('admin')->user(),
        ];

        return response()->json(['message' => 'Login successful', 'data' => $data], 200);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function AdminLogout()
    {
        // Invalidate the admin's token
        Auth::guard('admin')->logout();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function CustomerLogout()
    {
        // Invalidate the customer's token
        Auth::guard('customer')->logout();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }



}
