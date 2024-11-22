<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

class CustomerController extends Controller
{


// public function signup(Request $req)
// {
//     try {
//         $firstName = $req->input('first_name');
//         $lastName = $req->input('last_name');
//         $email = $req->input('email');
//         $phoneNumber = $req->input('phone_number');
//         $address = $req->input('address');
//         $city = $req->input('city');
//         $country = $req->input('country');
//         $password = $req->input('password');

//         DB::insert("INSERT INTO customers (first_name, last_name, email, phone_number, address, city, country, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [
//             $firstName, $lastName, $email, $phoneNumber, $address, $city, $country, $password
//         ]);

//         // Prepare email data
//         $emailData = [
//             'name' => $firstName . ' ' . $lastName,
//             'companyName' => 'Your Company Name',
//         ];

//         // Send welcome email
//         Mail::to($email)->send(new WelcomeEmail($emailData));

//         return response()->json(['message' => 'Customer registered successfully and welcome email sent.'], 201);
//     } catch (\Exception $e) {
//         return response()->json(['error' => $e->getMessage()], 500);
//     }
// }



    public function login(Request $req)
    {
        $user=DB::select("SELECT * FROM customers where email=? AND password=?",[$req->email,$req->password]);
        if(!empty($user[0])){
            return $user;
        }
        return response()->json(["message"=>"error"]);
    }

    public function rentACar(Request $req)
    {
        $id=getCurrentUserId();
        try {
        $vehId = $req->input('veh_id');
$cusId = $id;
//$status = $req->input('status');
//$approvedAt = $req->input('approved_at');
$startDate = $req->input('start_date');
$endDate = $req->input('end_date');
$totalPrice = $req->input('total_price');

DB::insert("INSERT INTO rental (veh_id, cus_id, start_date, end_date, total_price ) VALUES (?, ?, ?, ?,?)", [
    $vehId, $cusId, $startDate, $endDate, $totalPrice
]);

return successResponse("Rental Request Sent succuessfully");
} catch (\Exception $e) {

    return errorResponse($e->getMessage(),404);
}



    }
};
