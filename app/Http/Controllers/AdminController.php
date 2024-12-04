<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    // Method to view all pending requests
    // Method to view pending requests

    // Fetch customer details by ID
    public function getAllCustomers()
    {
        try {
            // Raw SQL query to fetch all customers
            $customers = DB::select('SELECT * FROM customers c join phone_numbers p on p.customer_id=c.id');

            // Return the data as JSON
            return response()->json($customers);
        } catch (\Exception $e) {
            // Error handling if the query fails
            return response()->json(['error' => 'Failed to fetch customers', 'message' => $e->getMessage()], 500);
        }
    }
public function getCustomerDetails($id)
{
    $customer = DB::table('customers')->where('id', $id)->first();
    return response()->json($customer, 200);
}

// Fetch vehicle details by ID
public function getVehicleDetails($id)
{
    $vehicle = DB::table('vehicles')->where('id', $id)->first();
    return response()->json($vehicle, 200);
}

    public function viewPendingRequests()
    {
        $pendingRequests = DB::select('SELECT * FROM rental WHERE status = ?', ['PENDING']);

        return response()->json($pendingRequests);
    }

    // Method to accept a rental request
    public function acceptRentalRequest($id)
{
    try {
        // Fetch the rental details
        $rental = DB::selectOne(
            'SELECT * FROM rental WHERE rental_id = ? AND status = ?',
            [$id, 'PENDING']
        );

        if (!$rental) {
            return response()->json(['message' => 'Request not found or already processed.'], 404);
        }

        // Update the rental status to "APPROVED"
        DB::update(
            'UPDATE rental
             SET status = ?, approved_at = NOW()
             WHERE rental_id = ? AND status = ?',
            ['APPROVED', $id, 'PENDING']
        );

        // Update the vehicle status to "unavailable" (status = 0 in statuses table)
        DB::update(
            'UPDATE vehicles v
             SET v.status_id = 2  -- Set the status to "UNAVAILABLE"
             WHERE v.id =?',
            [$rental->veh_id]
        );

        // Fetch customer details
        $customer = DB::selectOne(
            'SELECT c.first_name, c.last_name, c.email, p.phone_number, a.address
             FROM customers c
             JOIN phone_numbers p ON p.customer_id = c.id
             JOIN addresses a ON a.customer_id = c.id  -- Join addresses table to get the address
             WHERE c.id = ?',
            [$rental->cus_id]
        );

        // Fetch vehicle details
        $vehicle = DB::selectOne(
            'SELECT v.model, b.name AS brand, v.reg_number
             FROM vehicles v
             JOIN brands b ON v.brand_id = b.id  -- Join brands table to get the brand
             WHERE v.id = ?',
            [$rental->veh_id]
        );

        // Prepare email data
        $emailData = [
            'companyName' => 'RENT-A-CAR',
            'date' => now()->format('Y-m-d'),
            'day' => now()->format('l'),
            'customer' => $customer,
            'rental' => $rental,
            'vehicle' => $vehicle,
        ];

        // Send email to the customer
        Mail::send('emails.rental_status', $emailData, function ($message) use ($customer) {
            $message->to($customer->email)
                ->subject('Your Rental Request has been Approved');
        });

        return response()->json(['message' => 'Rental request accepted and email sent to the customer.'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function declineRentalRequest($id)
{
    try {
        // Update status in the rental table
        $affected = DB::update('UPDATE rental SET status = ?, approved_at = NOW() WHERE rental_id = ? AND status = ?', ['REJECTED', $id, 'PENDING']);

        if (!$affected) {
            return response()->json(['message' => 'Request not found or already processed.'], 404);
        }

        // Fetch rental details
        $rental = DB::selectOne('SELECT * FROM rental WHERE rental_id = ?', [$id]);

        // Fetch customer details including address
        $customer = DB::selectOne(
            'SELECT c.first_name, c.last_name, c.email, p.phone_number, a.address
             FROM customers c
             JOIN phone_numbers p ON p.customer_id = c.id
             JOIN addresses a ON a.customer_id = c.id  -- Join addresses table to get the address
             WHERE c.id = ?',
            [$rental->cus_id]
        );

        // Fetch vehicle details including brand
        $vehicle = DB::selectOne(
            'SELECT v.model, b.name AS brand, v.reg_number
             FROM vehicles v
             JOIN brands b ON v.brand_id = b.id  -- Join brands table to get the brand
             WHERE v.id = ?',
            [$rental->veh_id]
        );

        // Prepare email data
        $emailData = [
            'companyName' => 'RENT-A-CAR',  // Your company name
            'date' => now()->format('Y-m-d'),
            'day' => now()->format('l'),
            'customer' => $customer,
            'vehicle' => $vehicle,
            'declineMessage' => 'We regret to inform you that your rental request has been declined. Please feel free to contact us for further details.',
        ];

        // Send email to the customer
        Mail::send('emails.rental_declined', $emailData, function ($message) use ($customer) {
            $message->to($customer->email)
                ->subject('Your Rental Request has been Declined');
        });

        return response()->json(['message' => 'Rental request declined and email sent to the customer.'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
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
        $incidents = DB::select("
    SELECT incident_reporting.*,
           v.model,
           b.name AS brand_name
    FROM incident_reporting
    JOIN vehicles v ON incident_reporting.veh_id = v.id
    JOIN brands b ON v.brand_id = b.id
");

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

public function listInsurance() {
    $result = DB::select("SELECT * FROM insurance");
    return response()->json($result);
}

public function addInsurance(Request $request) {

    DB::insert("INSERT INTO insurance (veh_id, insurance_company, policy_number, start_date, end_date, coverage_details)
              VALUES (?, ?, ?, ?, ?, ?)", [
        $request->veh_id,
        $request->insurance_company,
        $request->policy_number,
        $request->start_date,
        $request->end_date,
        $request->coverage_details
    ]);
    return response()->json(['message' => 'Insurance added successfully.']);
}

public function updateInsurance(Request $request, $id) {
    DB::update("UPDATE insurance
              SET veh_id = ?, insurance_company = ?, policy_number = ?, start_date = ?, end_date = ?, coverage_details = ?
              WHERE id = ?", [
        $request->veh_id,
        $request->insurance_company,
        $request->policy_number,
        $request->start_date,
        $request->end_date,
        $request->coverage_details,
        $id
    ]);
    return response()->json(['message' => 'Insurance updated successfully.']);
}

public function deleteInsurance($id) {
    DB::delete("DELETE FROM insurance WHERE id = ?", [$id]);
    return response()->json(['message' => 'Insurance deleted successfully.']);
}






}

