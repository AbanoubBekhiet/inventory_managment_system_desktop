<?php

use App\Http\Controllers\BillsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\mainController;
use App\Http\Controllers\customersController;


Route::get('/', [mainController::class, "login_view"])->name("login_view");
Route::post('/login', [mainController::class, "login_fun"])->name("login_fun");

Route::get('/register', [mainController::class, "register_view"])->name("register_view");
Route::post('/register_fun', [mainController::class, "register_fun"])->name("register_fun");



Route::get('/categories', [mainController::class, "categories"])->name("categories");
Route::post('/categories/add_category', [mainController::class, "add_category"])->name("add_category");
Route::delete('/categories/delete_category/{category_id}', [mainController::class, "delete_category"])->name("delete_category");



Route::get('/products', [mainController::class, "products_view"])->name("products_view");
Route::post('/products/add_product', [mainController::class, "add_product"])->name("add_product");
Route::delete('/products/delete_product/{product_id}', [mainController::class, "delete_product"])->name("delete_product");
Route::put('/products/update_product/{product_id}', [mainController::class, "update_product"])->name("update_product");
Route::get('/products/exportProducts', [mainController::class, "exportProducts"])->name("exportProducts");
Route::get('/products/imports_view', [mainController::class, "imports_view"])->name("imports_view");
Route::PUT('/products/add_quantity/{product_id}', [mainController::class, "add_quantity"])->name("add_quantity");


Route::get('/customers', [customersController::class, "customers"])->name("customers");
Route::post('/customers/add_customer', [customersController::class, "add_customer"])->name("add_customer");
Route::put('/customers/update_customer/{customer_id}', [customersController::class, "update_customer"])->name("update_customer");
Route::delete('/customers/delete_customer/{customer_id}', [customersController::class, "delete_customer"])->name("delete_customer");
Route::get('/customers/customer_purchases/{customer_id}', [customersController::class, "customer_purchases"])->name("customer_purchases");
Route::get('/customers/exporCustomers', [customersController::class, "exporCustomers"])->name("exporCustomers");



Route::get('/bills/make_bills', [BillsController::class, "make_bills"])->name("make_bills");
Route::post('/bills/bill_back', [BillsController::class, "bill_back"])->name("bill_back");
Route::get('/bills/show_bills', [BillsController::class, "show_bills"])->name("show_bills");
Route::get('/bills/show_bills/show_specific_bill/{bill_id}', [BillsController::class, "show_specific_bill"])->name("show_specific_bill");
Route::get('/bills/exportBills', [BillsController::class, "exportBills"])->name("exportBills");
Route::get('/bills/show_bills/bill_binefits/{bill_id}', [BillsController::class, "bill_binefits"])->name("bill_binefits");
Route::get('/statistics', [BillsController::class, "statistics"])->name("statistics");





Route::get('/backup-database', function () {
    Artisan::call('backup:database');
    return back()->with('message', 'تم حفظ نسخة من قاعدة البيانات في حسابك علي جوجل درايف بنجاح');
})->name('backup.database');
