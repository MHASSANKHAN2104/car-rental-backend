<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

            $filePath = null;
            if ($req->hasFile('file')) {
                $filePath = $req->file('file')->store('vehicle_images', 'public');
            }

            DB::insert("INSERT INTO vehicles (model, brand, year, reg_number, status, daily_rate, mileage, filePath, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())", [
                $model, $brand, $year, $reg_number, $status, $daily_rate, $mileage, $filePath
            ]);

            return response()->json(["message" => "Vehicle registered successfully"], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function deleteVehicle($id)
    {
        try {
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
        $vehicles = DB::select("SELECT * FROM vehicles");

        foreach ($vehicles as &$vehicle) {
            $vehicle->image_url = $vehicle->filePath
                ? asset('storage/' . $vehicle->filePath)
                : null;
        }

        return response()->json($vehicles, 200);
    }

    public function updateVehicle(Request $request, $id)
    {
        try {
            $model = $request->input('model');
            $brand = $request->input('brand');
            $year = $request->input('year');
            $regNumber = $request->input('reg_number');
            $status = $request->input('status');
            $dailyRate = $request->input('daily_rate');
            $mileage = $request->input('mileage');

            $filePath = $request->input('filePath');

            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('vehicle_images', 'public');
            }

            $affected = DB::update('UPDATE vehicles SET model = ?, brand = ?, year = ?, reg_number = ?, status = ?, daily_rate = ?, mileage = ?, filePath = ?, updated_at = NOW() WHERE id = ?', [
                $model, $brand, $year, $regNumber, $status, $dailyRate, $mileage, $filePath, $id
            ]);

            if ($affected) {
                return response()->json(['message' => 'Vehicle updated successfully.'], 200);
            }

            return response()->json(['message' => 'Vehicle not found or update failed.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
