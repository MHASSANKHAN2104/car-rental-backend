<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Customer;
use App\Http\Controllers\CustomerController;
use App\Models\Vehicle;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get('/customer', function () {
    $customers=Customer::all();
    echo"<pre>";
    print_r($customers->toArray());
});

// Route::post('/signup', [CustomerController::class, 'signup']);
// Route::post('/login', [CustomerController::class, 'login']);
///////////////////////////////////////////////////////////////////////////////////

//**************************VEHICLE ROUTES****************************/

Route::post('/add-vehicles', [VehicleController::class, 'add_vehicles']);
Route::get('/list', [VehicleController::class, 'listVehicles']);
Route::delete('/deleted/{id}', [VehicleController::class, 'deleteVehicle']);

///////////////////////////// CUSTOMER ROUTES ///////////////////////////////////////

// Login Routes
Route::post('/auth/customer/register', function(Request $req) {
    return app(AuthController::class)->register($req, 'customer');
});
Route::post('/auth/customer/login',[AuthController::class,'loginCustomer']);
Route::middleware('auth:customer')->group(function(){
Route::post('/rent-car', [CustomerController::class, 'rentACar']);
Route::get('/auth/customer/logout', [AuthController::class,'CustomerLogout']);

});


////////////////////////////////////////////////////////////////////////////////
///////////////////////////// Admin routes  //////////////////////////////////////

Route::post('/auth/admin/login',[AuthController::class,'loginAdmin']);
Route::post('/auth/admin/register', function(Request $req) {
    return app(AuthController::class)->register($req, 'admin');
});

Route::middleware('auth:admin')->group(function(){
Route::get('/admin/pending-requests', [AdminController::class, 'viewPendingRequests']);
Route::post('/admin/accept-request/{id}', [AdminController::class, 'acceptRentalRequest']);
Route::post('/admin/decline-request/{id}', [AdminController::class, 'declineRentalRequest']);
Route::get('/auth/admin/logout', [AuthController::class,'AdminLogout']);
});

///////////////////////////////////////////////////////////////////////////////////

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












































Route::middleware(['authenticate'])->group(function(){
    Route::post('me', [AuthController::class,'me']);

    Route::post('refresh', [AuthController::class,'refresh']);

});
