<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

class VehicleController extends Controller
{
    public function add_vehicles(Request $req)
    {
        try {

            $model = $req->input("model");
            $brand = $req->input("brand");
            $year = $req->input("year");
            $reg_number = $req->input("reg_number");
            $status = $req->input("status");
            $daily_rate = $req->input("daily_rate");
            $mileage = $req->input("mileage");


            if ($req->hasFile('file')) {

                $filePath = $req->file('file')->store('public', 'products');
            } else {
                $filePath = null;
            }


            DB::insert("INSERT INTO vehicles (model, brand, year, reg_number, status, daily_rate, mileage, filePath) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [
                $model, $brand, $year, $reg_number, $status, $daily_rate, $mileage, $filePath
            ]);

            return response()->json(["message" => "Vehicle registered successfully"], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 404);
        }
    }

    public function deleteVehicle($id)
    {
        try {
            // Use raw SQL to delete the vehicle
            $deleted = DB::delete("DELETE FROM vehicles WHERE id = ?", [$id]);

            if ($deleted) {
                return response()->json(['message' => 'Vehicle deleted successfully'], 200);
            } else {
                return response()->json(['message' => 'Vehicle not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function listVehicles()
    {
        return Vehicle::all();
    }
}
