<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

 //////////////////////  isme vehicle ha ////////////////////////////////////
class VehicleController extends Controller
{
    public function add_vehicles(Request $req)
{
    try {
        $model = $req->input("model");
        $brandName = $req->input("brand"); // Brand name from request
        $year = $req->input("year");
        $reg_number = $req->input("reg_number");
        $statusName = $req->input("status"); // Status from request
        $daily_rate = $req->input("daily_rate");
        $mileage = $req->input("mileage");

        // Check if the brand exists, if not, insert it
        $brandId = DB::table('brands')->where('name', $brandName)->value('id');
        if (!$brandId) {
            // Insert the new brand if it doesn't exist
            DB::insert("INSERT INTO brands (name) VALUES (?)", [$brandName]);
            // Get the newly inserted brand_id
            $brandId = DB::getPdo()->lastInsertId();
        }

        // Check if the status exists, if not, insert it
        $statusId = DB::table('statuses')->where('status', $statusName)->value('id');
        if (!$statusId) {
            // Insert the new status if it doesn't exist
            DB::insert("INSERT INTO statuses (status) VALUES (?)", [$statusName]);
            // Get the newly inserted status_id
            $statusId = DB::getPdo()->lastInsertId();
        }

        $filePath=null ;
        if ($req->hasFile('filePath')) {
            $filePath = $req->file('filePath')->store('vehicle_images', 'public');
        }

        // Insert the vehicle data into the vehicles table
        DB::insert("
            INSERT INTO vehicles (model, brand_id, year, reg_number, status_id, daily_rate, mileage, filePath, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ", [
            $model, $brandId, $year, $reg_number, $statusId, $daily_rate, $mileage, $filePath
        ]);

        return response()->json(["message" => "Vehicle registered successfully"], 200);
    } catch (\Exception $e) {
        return response()->json(["error" => $e->getMessage()], 500);
    }
}

     //////////////////////  isme vehicle ha ////////////////////////////////////

     public function deleteVehicle($id)
{
    try {
        // Check if the vehicle exists
        $vehicleExists = DB::table('vehicles')->where('id', $id)->exists();
        if (!$vehicleExists) {
            return response()->json(['message' => 'Vehicle not found'], 404);
        }

        // Deleting the vehicle from the vehicles table
        $deleted = DB::delete("DELETE FROM vehicles WHERE id = ?", [$id]);

        if ($deleted) {
            return response()->json(['message' => 'Vehicle deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Vehicle deletion failed'], 500);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


     //////////////////////  isme vehicle ha ////////////////////////////////////

     public function listVehicles()
     {
         try {
             // Fetch vehicles along with their brand name and availability status
             $vehicles = DB::select("
                 SELECT v.id, v.model, v.year, v.reg_number,
                        b.name as brand,s.status,
                        v.daily_rate, v.mileage, v.filePath,
                        CONCAT('http://127.0.0.1:8000/storage/', v.filePath) as image_url
                 FROM vehicles v
                 LEFT JOIN brands b ON v.brand_id = b.id
                 LEFT JOIN statuses s ON v.status_id = s.id
             ");

             return response()->json($vehicles, 200);
         } catch (\Exception $e) {
             return response()->json(['error' => $e->getMessage()], 500);
         }
     }



public function updateVehicle(Request $request, $id)
{
    try {
        // Fetch existing filePath in case no new file is provided
        $filePath = DB::table('vehicles')->where('id', $id)->value('filePath');

        // Handle file upload
        if ($request->hasFile('filePath')) {
            $request->validate([
                'filePath' => 'mimes:jpg,jpeg,png|max:2048', // Restrict file types and size
            ]);
            $filePath = $request->file('filePath')->store('vehicle_images', 'public');
        }

        // Get brand_id from the brands table
        $brandId = DB::table('brands')->where('name', $request->input('brand'))->value('id');

        // Get status_id from the statuses table
        $statusId = DB::table('statuses')->where('status', $request->input('status'))->value('id');

        // Check if vehicle exists
        $vehicleExists = DB::table('vehicles')->where('id', $id)->exists();
        if (!$vehicleExists) {
            return response()->json(['message' => 'Vehicle not found.'], 404);
        }

        // Update the vehicle record
        $affected = DB::update(
            'UPDATE vehicles SET model = ?, brand_id = ?, year = ?, reg_number = ?, status_id = ?, daily_rate = ?, mileage = ?, filePath = ?, updated_at = NOW() WHERE id = ?',
            [
                $request->input('model'),
                $brandId,
                $request->input('year'),
                $request->input('reg_number'),
                $statusId,
                $request->input('daily_rate'),
                $request->input('mileage'),
                $filePath,
                $id,
            ]
        );

        if ($affected) {
            return response()->json(['message' => 'Vehicle updated successfully.'], 200);
        }

        return response()->json(['message' => 'Update failed.'], 400);
    } catch (\Exception $e) {
        \Log::error('Error updating vehicle: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

}

