<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Customer;
use App\Http\Controllers\CustomerController;
use App\Models\Vehicle;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;


Route::get('/list', [VehicleController::class, 'listVehicles']);
Route::post('/auth/customer/login',[AuthController::class,'loginCustomer']);
Route::post('/auth/customer/register', function(Request $req) {
    return app(AuthController::class)->register($req, 'customer');
});


Route::middleware('auth:customer')->group(function(){
Route::post('/rent-car', [CustomerController::class, 'rentACar']);
Route::get('/auth/customer/logout', [AuthController::class,'CustomerLogout']);
});


Route::post('/auth/admin/login',[AuthController::class,'loginAdmin']);
Route::post('/auth/admin/register', function(Request $req) {
return app(AuthController::class)->register($req, 'admin');
});


Route::middleware('auth:admin')->group(function(){
//Route::get('/admin/pending-requests', [AdminController::class, 'viewPendingRequests']);
//Route::post('/admin/accept-request/{id}', [AdminController::class, 'acceptRentalRequest']);
//Route::post('/admin/decline-request/{id}', [AdminController::class, 'declineRentalRequest']);

// View all pending rental requests
Route::get('/admin/rentals/pending', [AdminController::class, 'viewPendingRequests']);

// Accept a rental request
Route::put('/admin/rentals/accept/{id}', [AdminController::class, 'acceptRentalRequest']);

// Decline a rental request
Route::put('/admin/rentals/decline/{id}', [AdminController::class, 'declineRentalRequest']);

Route::delete('/deleted/{id}', [VehicleController::class, 'deleteVehicle']);
Route::put('/vehicles/{id}', [VehicleController::class, 'updateVehicle']);
Route::get('/auth/admin/logouts', [AuthController::class,'AdminLogout']);
Route::post('/add-vehicles', [VehicleController::class, 'add_vehicles']);


///////Route to list all maintenance records///////////////////////////////////////
Route::get('/admin/maintenance', [AdminController::class, 'indexMaintenance']);
Route::post('/admin/maintenance', [AdminController::class, 'storeMaintenance']);
Route::put('/admin/maintenance/{id}', [AdminController::class, 'updateMaintenance']);
Route::delete('/admin/maintenance/{id}', [AdminController::class, 'deleteMaintenance']);

////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////// Route to list all incidents/////////////////////////////////////
Route::get('/admin/incidents', [AdminController::class, 'indexIncidents']);
Route::post('/admin/incidents', [AdminController::class, 'storeIncident']);
Route::put('/admin/incidents/{incidentId}', [AdminController::class, 'updateIncident']);
Route::delete('/admin/incidents/{incidentId}', [AdminController::class, 'deleteIncident']);

/////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////PAYMENT ROUTES///////////////////////////////////////////////

Route::get('/admin/payment', [AdminController::class, 'indexPayments']);
Route::post('/admin/payment', [AdminController::class, 'storePayment']);
Route::put('/admin/payment/{id}', [AdminController::class, 'updatePayment']);
Route::delete('/admin/payment/{id}', [AdminController::class, 'deletePayment']);

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////// INSURANCE ROUTES ////////////////////////////////////////////

Route::get('/admin/insurances', [AdminController::class, 'listInsurance']);
Route::post('/admin/insurances', [AdminController::class, 'addInsurance']);
Route::put('/admin/insurances/{id}', [AdminController::class, 'updateInsurance']);
Route::delete('/admin/insurances/{id}', [AdminController::class, 'deleteInsurance']);



});


///////////////////////////////////////////////////////////////////////////////////









































