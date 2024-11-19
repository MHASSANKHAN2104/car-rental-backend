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

            // Insert the admin record into the admins table
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

// public function login(Request $req, $userType )
// {
//     // Get credentials from the request
//     $email = $req->input('email');
//     $password = $req->input('password');

//     // Set the guard based on the user type (admin or customer)
//     $guard = $userType === 'admin' ? 'admin' : 'customer';

//     // Check if the user type is 'admin' or 'customer' and validate accordingly
//     if ($userType === 'admin') {
//         // Fetch the admin from the database using raw SQL
//         $admin = DB::select('SELECT * FROM admins WHERE email = ?', [$email]);

//         // If no admin is found or the password doesn't match, return unauthorized
//         if (empty($admin) || !\Hash::check($password, $admin[0]->password)) {
//             return response()->json(['error' => 'Unauthorized'], 401);
//         }

//         // Create an instance of the Admin model
//         $admin = new Admin($admin[0]->id, $admin[0]->name, $admin[0]->email, $admin[0]->password);

//         // If valid, generate the token for the admin
//         $token = auth('admin')->login($admin);
//         $user = $admin;
//     } else {
//         // Fetch the customer from the database using raw SQL
//         $customer = DB::select('SELECT * FROM customers WHERE email = ?', [$email]);

//         // If no customer is found or the password doesn't match, return unauthorized
//         if (empty($customer) || !\Hash::check($password, $customer[0]->password)) {
//             return response()->json(['error' => 'Unauthorized'], 401);
//         }

//         // Create an instance of the Customer model
//         $customer = new \App\Models\Customer($customer[0]->id, $customer[0]->first_name, $customer[0]->last_name, $customer[0]->email, $customer[0]->password);

//         // If valid, generate the token for the customer
//         $token = auth('api')->login($customer);
//         $user = $customer;
//     }

//     // Return the token and user data in the response
//     $data = [
//         'token' => $token,
//         'user' => $user,
//     ];

//     return response()->json(['message' => 'Login successful', 'data' => $data], 200);
// }
public function loginCustomer(Request $req, $userType = 'customer')
    {
        $credentials = request(['email', 'password']);
        $guard = $userType === 'users' ? 'users' : 'api';

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
        auth('admin')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function CustomerLogout()
    {
        auth('customer')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    protected function getToken($credentials, $guard = 'api')
    {
        return Auth::guard($guard)->attempt($credentials);
    }
}
