<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;



 //////////////////////  isme customer ha ////////////////////////////////////
class AuthController extends Controller
{
    public function register(Request $req, $userType)
    {
        try {
            if ($userType === 'admin') {
                // Registering an admin
                $name = $req->input('name');
                $email = $req->input('email');
                $password = bcrypt($req->input('password'));

                DB::insert("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)", [
                    $name, $email, $password
                ]);

                return response()->json(['message' => 'Admin registered successfully'], 201);
            } else {
                // Registering a customer
                $firstName = $req->input('first_name');
                $lastName = $req->input('last_name');
                $email = $req->input('email');
                $password = bcrypt($req->input('password'));

                // Insert into customers table
                DB::insert("INSERT INTO customers (first_name, last_name, email, password) VALUES (?, ?, ?, ?)", [
                    $firstName, $lastName, $email, $password
                ]);

                // Get the last inserted customer ID
                $customerId = DB::getPdo()->lastInsertId();

                // Insert into phone_numbers table
                $phoneNumber = $req->input('phone_number');
                if ($phoneNumber) {
                    DB::insert("INSERT INTO phone_numbers (customer_id, phone_number) VALUES (?, ?)", [
                        $customerId, $phoneNumber
                    ]);
                }

                // Insert into addresses table
                $address = $req->input('address');
                $city = $req->input('city');
                $country = $req->input('country');
                if ($address && $city && $country) {
                    DB::insert("INSERT INTO addresses (customer_id, address, city, country) VALUES (?, ?, ?, ?)", [
                        $customerId, $address, $city, $country
                    ]);
                }

                // Prepare the email data
                $emailData = [
                    'companyName' => 'Your Rental Service',
                    'name' => "$firstName $lastName",
                    'greeting' => "Welcome, $firstName $lastName!",
                    'message' => "Thank you for signing up with us. We are thrilled to have you onboard.",
                ];

                // Send the welcome email
                Mail::send('emails.greetings', $emailData, function ($message) use ($email) {
                    $message->to($email)
                            ->subject('Welcome to Our Rental Service');
                });

                return response()->json(['message' => 'Customer registered successfully and email sent.'], 201);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }


 //////////////////////  isme customer ha ////////////////////////////////////
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
