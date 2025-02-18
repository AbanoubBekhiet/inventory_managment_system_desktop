<?php

use App\Http\Controllers\BillsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mainController;
use App\Http\Controllers\customersController;


Route::get('/', [mainController::class, "login_view"])->name("login_view");
Route::post('/login', [mainController::class, "login_fun"])->name("login_fun");



Route::get('/categories', [mainController::class, "categories"])->name("categories");
Route::post('/categories/add_category', [mainController::class, "add_category"])->name("add_category");
Route::delete('/categories/delete_category/{category_id}', [mainController::class, "delete_category"])->name("delete_category");



Route::get('/products', [mainController::class, "products_view"])->name("products_view");
Route::post('/products/add_product', [mainController::class, "add_product"])->name("add_product");
Route::delete('/products/delete_product/{product_id}', [mainController::class, "delete_product"])->name("delete_product");
Route::put('/products/update_product/{product_id}', [mainController::class, "update_product"])->name("update_product");



Route::get('/customers', [customersController::class, "customers"])->name("customers");
Route::post('/customers/add_customer', [customersController::class, "add_customer"])->name("add_customer");
Route::put('/customers/update_customer/{customer_id}', [customersController::class, "update_customer"])->name("update_customer");
Route::delete('/customers/delete_customer/{customer_id}', [customersController::class, "delete_customer"])->name("delete_customer");
Route::post('/customers/customer_purchases/{customer_id}', [customersController::class, "customer_purchases"])->name("customer_purchases");



Route::get('/bills/make_bills', [BillsController::class, "make_bills"])->name("make_bills");
Route::post('/bills/bill_back', [BillsController::class, "bill_back"])->name("bill_back");
Route::get('/bills/show_bills', [BillsController::class, "show_bills"])->name("show_bills");
Route::get('/bills/show_bills/show_specific_bill/{bill_id}', [BillsController::class, "show_specific_bill"])->name("show_specific_bill");
Route::get('/statistics', [BillsController::class, "statistics"])->name("statistics");
