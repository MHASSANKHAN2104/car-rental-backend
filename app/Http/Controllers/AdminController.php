<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Method to view all pending requests
    public function viewPendingRequests()
    {
        $pendingRequests = DB::select('SELECT * FROM rental WHERE status = ?', ['PENDING']);

        return response()->json($pendingRequests);
    }

    // Method to accept a rental request
    public function acceptRentalRequest($id)
    {
        $affected = DB::update('UPDATE rental SET status = ? WHERE rental_id = ? AND status = ?', ['APPROVED', $id, 'PENDING']);

        if ($affected) {
            return response()->json(['message' => 'Rental request accepted.']);
        }

        return response()->json(['message' => 'Request not found or already processed.'], 404);
    }

    // Method to decline a rental request
    public function declineRentalRequest($id)
    {
        $affected = DB::update('UPDATE rental SET status = ? WHERE rental_id = ? AND status = ?', ['REJECTED', $id, 'PENDING']);

        if ($affected) {
            return response()->json(['message' => 'Rental request declined.']);
        }

        return response()->json(['message' => 'Request not found or already processed.'], 404);
    }

    public function storeMaintenance(Request $request)
    {
        $vehId = $request->input('veh_id');
        $maintenanceDate = $request->input('maintenance_date');
        $description = $request->input('description');
        $cost = $request->input('cost');

        // Using raw SQL insert
        $affected = DB::insert("INSERT INTO maintenance (veh_id, maintenance_date, description, cost, status, created_at, updated_at) VALUES (?, ?, ?, ?, 'pending', NOW(), NOW())", [
            $vehId, $maintenanceDate, $description, $cost
        ]);

        if ($affected) {
            return response()->json(['message' => 'Maintenance record added successfully.']);
        }

        return response()->json(['message' => 'Failed to add maintenance record.'], 500);
    }
    public function updateMaintenance(Request $request, $id)
    {
        $vehId = $request->input('veh_id');
        $maintenanceDate = $request->input('maintenance_date');
        $description = $request->input('description');
        $cost = $request->input('cost');
        $status = $request->input('status');

        // Using raw SQL update
        $affected=DB::update('UPDATE maintenance SET veh_id = ?, maintenance_date = ?, description = ?, cost = ?, status = ?, updated_at = NOW() WHERE main_id = ?', [
            $vehId, $maintenanceDate, $description, $cost, $status, $id
        ]);


        if ($affected) {
            return response()->json(['message' => 'Maintenance record updated successfully.']);
        }

        return response()->json(['message' => 'Maintenance record not found or update failed.'], 404);
    }
    public function deleteMaintenance($id)
    {
        // Using raw SQL delete
        $affected = DB::delete("DELETE FROM maintenance WHERE main_id = ?", [$id]);

        if ($affected) {
            return response()->json(['message' => 'Maintenance record deleted successfully.']);
        }

        return response()->json(['message' => 'Maintenance record not found.'], 404);
    }
    public function indexMaintenance()
    {
        // Raw SQL query to fetch maintenance records along with detailed vehicle information
        $maintenanceRecords = DB::select("
            SELECT maintenance.*, vehicles.model, vehicles.brand, vehicles.year, vehicles.reg_number
            FROM maintenance
            JOIN vehicles ON maintenance.veh_id = vehicles.id
        ");

        // Return JSON response for API-style handling
        return response()->json(['maintenanceRecords' => $maintenanceRecords]);
    }
///////////////////////////////////////////////////////////////////////////////////////////////
    // Store new incident
    public function storeIncident(Request $request)
    {
        $vehId = $request->input('veh_id');
        $incidentDate = $request->input('incident_date');
        $description = $request->input('description');

        DB::insert("INSERT INTO incident_reporting (veh_id, incident_date, description, created_at, updated_at)
                    VALUES (?, ?, ?, NOW(), NOW())", [
            $vehId, $incidentDate, $description
        ]);

        return response()->json(['message' => 'Incident reported successfully.']);
    }

    // Update an existing incident
    public function updateIncident(Request $request, $incidentId)
    {
        $vehId = $request->input('veh_id');
        $incidentDate = $request->input('incident_date');
        $description = $request->input('description');

        DB::update("UPDATE incident_reporting
                    SET veh_id = ?, incident_date = ?, description = ?, updated_at = NOW()
                    WHERE incident_id = ?", [
            $vehId, $incidentDate, $description, $incidentId
        ]);

        return response()->json(['message' => 'Incident updated successfully.']);
    }

    // Delete an incident
    public function deleteIncident($incidentId)
    {
        DB::delete("DELETE FROM incident_reporting WHERE incident_id = ?", [$incidentId]);
        return response()->json(['message' => 'Incident deleted successfully.']);
    }

    // List all incidents
    public function indexIncidents()
    {
        $incidents = DB::select("SELECT incident_reporting.*, vehicles.model, vehicles.brand
                                 FROM incident_reporting
                                 JOIN vehicles ON incident_reporting.veh_id = vehicles.id");

        return response()->json($incidents);
    }


    public function indexPayments()
    {
        $payments = DB::select("
            SELECT payment.*, rental.cus_id, rental.veh_id
            FROM payment
            JOIN rental ON payment.rental_id = rental.rental_id
        ");

        return response()->json($payments, 200);
    }

    public function storePayment(Request $req)
{
    $rentalId = $req->input('rental_id');
    $amount = $req->input('amount');
    $paymentDate = $req->input('payment_date');
    $paymentMethod = $req->input('payment_method');
    $status = $req->input('status', 'PENDING'); // Default to 'PENDING'

    DB::insert("
        INSERT INTO payment (rental_id, amount, payment_date, payment_method, status, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, NOW(), NOW())
    ", [
        $rentalId, $amount, $paymentDate, $paymentMethod, $status
    ]);

    return response()->json(['message' => 'Payment added successfully.']);
}

public function updatePayment(Request $req, $id)
{
    $amount = $req->input('amount');
    $paymentDate = $req->input('payment_date');
    $paymentMethod = $req->input('payment_method');
    $status = $req->input('status');

    $updated = DB::update("
        UPDATE payment
        SET amount = ?, payment_date = ?, payment_method = ?, status = ?, updated_at = NOW()
        WHERE payment_id = ?
    ", [
        $amount, $paymentDate, $paymentMethod, $status, $id
    ]);

    if ($updated) {
        return response()->json(['message' => 'Payment updated successfully.']);
    }

    return response()->json(['message' => 'Payment not found or not updated.'], 404);
}

public function deletePayment($id)
{
    $deleted = DB::delete("DELETE FROM payment WHERE payment_id = ?", [$id]);

    if ($deleted) {
        return response()->json(['message' => 'Payment deleted successfully.']);
    }

    return response()->json(['message' => 'Payment not found.'], 404);
}




}

