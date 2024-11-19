<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class CustomerController extends Controller
{
    public function signup(Request $req)
{
    try {

        $firstName = $req->input('first_name');
        $lastName = $req->input('last_name');
        $email = $req->input('email');
        $phoneNumber = $req->input('phone_number');
        $address = $req->input('address');
        $city = $req->input('city');
        $country = $req->input('country');
        $password = $req->input('password');

        DB::insert("INSERT INTO customers (first_name, last_name, email, phone_number, address, city, country, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [
            $firstName, $lastName, $email, $phoneNumber, $address, $city, $country, $password
        ]);


        return successResponse("Customer register succuessfully");
    } catch (\Exception $e) {

        return errorResponse($e->getMessage(),404);
    }
}


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
$status = $req->input('status');
$requestedAt = $req->input('requested_at');
$approvedAt = $req->input('approved_at');
$startDate = $req->input('start_date');
$endDate = $req->input('end_date');
$totalPrice = $req->input('total_price');
$createdAt = $req->input('created_at');
$updatedAt = $req->input('updated_at');

DB::insert("INSERT INTO rental (veh_id, cus_id, status, requested_at, approved_at, start_date, end_date, total_price, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
    $vehId, $cusId, $status, $requestedAt, $approvedAt, $startDate, $endDate, $totalPrice, $createdAt, $updatedAt
]);

return successResponse("Rental Request Sent succuessfully");
} catch (\Exception $e) {

    return errorResponse($e->getMessage(),404);
}



    }
};
