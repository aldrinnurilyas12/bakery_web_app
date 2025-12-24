<?php

use App\Http\Controllers\Api\DailyProducts;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\HomepageController;
use App\Http\Controllers\Api\ItemsCategoryController;
use App\Http\Controllers\Api\ProductsController;
use App\Http\Controllers\Api\PromoCampaignController;
use App\Http\Controllers\Api\RawMaterialController;
use App\Http\Controllers\Api\ShoppingCartController;
use App\Http\Controllers\Api\RewardsController;
use App\Http\Controllers\Api\Voucher;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\ProductionProductController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.main_pages.welcome_page');
});

Route::get('dashboard', function () {
    return view('layouts.main_pages.home_page');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('dashboard_main', [HomepageController::class, 'index'])->name('dashboard_main');
    Route::get('profile_information', [ProfileController::class, 'user_profile'])->name('profile_information');
    Route::put('profile_update/{id}', [ProfileController::class, 'update'])->name('profile_update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    
    
    // USERS Route
    Route::apiResource('user_register', App\Http\Controllers\Auth\RegisteredUserController::class);
    Route::get('users_register_account', [RegisteredUserController::class, 'show_users_register'])->name('users_register_account');
     Route::get('users_data', [RegisteredUserController::class, 'master_main_users'])->name('users_data');
    Route::post('signout', [AuthenticatedSessionController::class, 'destroy'])->name('signout');
    Route::get('users_edit/{nik}' , [RegisteredUserController::class, 'edit_users_layout'])->name('users_edit');
    Route::put('users_update/{nik}' , [RegisteredUserController::class, 'update'])->name('users_update');
    Route::delete('users_delete/{id}' , [RegisteredUserController::class, 'destroy'])->name('users_delete');
    Route::put('user_profile_update/{nik}', [EmployeeController::class, 'update_user_profile'])->name('user_profile_update');
    Route::put('user_active_update/{nik}', [RegisteredUserController::class, 'update_user_active'])->name('user_active_update');

    //  Customer API
    Route::apiResource('master_customers', App\Http\Controllers\Api\CustomerController::class);

    // Employee API
    Route::apiResource('master_employee', App\Http\Controllers\Api\EmployeeController::class);
    Route::get('employee_create', [EmployeeController::class, 'create'])->name('employee_create');
    Route::get('employee_edit/{nik}', [EmployeeController::class, 'employee_edit_layout'])->name('employee_edit');
    Route::put('update_employee/{nik}', [EmployeeController::class, 'update'])->name('update_employee');

    // Products Route
    Route::apiResource('master_products', App\Http\Controllers\Api\ProductsController::class);
    Route::get('product_create', [ProductsController::class, 'create'])->name('product_create');
    Route::get('product_update/{product_code}', [ProductsController::class, 'product_update_layout'])->name('product_update');
    Route::put('edit_product/{product_code}', [ProductsController::class, 'update'])->name('edit_product');
    Route::delete('product_delete/', [ProductsController::class, 'destroy'])->name('product_delete');
    Route::delete('delete_image/{id}', [ProductsController::class, 'delete_images'])->name('delete_image');
    Route::get('/products_data', function () {
        return view('pages.product-data');
    })->name('products_data');

    // DailyProducts Route
    Route::apiResource('master_daily_products', App\Http\Controllers\Api\DailyProducts::class);
    Route::put('daily_product_edit/{product_code}', [DailyProducts::class, 'update'])->name('daily_product_edit');
    Route::get('dailyproduct_create', [DailyProducts::class, 'create'])->name('daily_product_create');
    Route::get('dailyproduct_update/{product_code}', [DailyProducts::class, 'edit'])->name('dailyproduct_update');
    Route::delete('dailyproduct_delete/{product_code}', [DailyProducts::class, 'destroy'])->name('dailyproduct_delete');
    Route::get('/dailyproducts_data', function () {
        return view('pages.dailyproduct-data');
    })->name('dailyproducts_data');

    // Route Promo Campign
    Route::apiResource('master_promo_campaign', App\Http\Controllers\Api\PromoCampaignController::class);
    Route::get('promo_create', [PromoCampaignController::class, 'create'])->name('promo_create');
    Route::get('promo_update/{promo_code}', [PromoCampaignController::class, 'edit'])->name('promo_update');
    Route::put('promo_edit/{promo_code}', [PromoCampaignController::class, 'update'])->name('promo_edit');
    Route::delete('promo_delete/{promo_code}', [PromoCampaignController::class, 'destroy'])->name('promo_delete');
    Route::get('/promo_campaign', function () {
        return view('pages.promo-campaign');
    })->name('promo_campaign');

    // Rewards Routes
    Route::apiResource('master_rewards', App\Http\Controllers\Api\RewardsController::class);
    Route::get('rewards_create', [RewardsController::class, 'create'])->name('rewards_create');
    Route::get('rewards_update/{rewards_code}', [RewardsController::class, 'edit'])->name('rewards_update');
    Route::put('rewards_edit/{rewards_code}', [RewardsController::class, 'update'])->name('rewards_edit');
    Route::delete('rewards_delete/{rewards_code}', [RewardsController::class, 'destroy'])->name('rewards_delete');
    Route::get('/rewards', function () {
        return view('pages.rewards');
    })->name('rewards');

    // Route Voucher
    Route::apiResource('master_voucher', App\Http\Controllers\Api\Voucher::class);
    Route::get('voucher_create', [Voucher::class, 'create'])->name('voucher_create');
    Route::get('voucher_update/{voucher_code}', [Voucher::class, 'edit'])->name('voucher_update');
    Route::put('voucher_edit/{voucher_code}', [Voucher::class, 'update'])->name('voucher_edit');
    Route::delete('voucher_delete/{voucher_code}', [Voucher::class, 'destroy'])->name('voucher_delete');
    Route::get('/voucher_data', function () {
        return view('pages.voucher');
    })->name('voucher');

    // Raw Material Route
    Route::apiResource('master_material', App\Http\Controllers\Api\RawMaterialController::class);
    Route::get('material_create', [RawMaterialController::class, 'create'])->name('material_create');
    Route::get('material_update/{material_code}', [RawMaterialController::class, 'edit'])->name('material_update');
    Route::put('material_edit/{material_code}', [RawMaterialController::class, 'update'])->name('material_edit');
    Route::delete('material_delete/{material_code}', [RawMaterialController::class, 'destroy'])->name('material_delete');
    Route::get('/raw_material', function () {
        return view('pages.raw_material');
    })->name('raw_material');

    // Production Product Route
    Route::apiResource('master_production_product', App\Http\Controllers\Api\ProductionProductController::class);
    Route::get('production_create', [ProductionProductController::class, 'create'])->name('production_create');
    Route::get('production_update/{production_code}', [ProductionProductController::class, 'edit'])->name('production_update');
    Route::put('production_edit/{production_code}', [ProductionProductController::class, 'update'])->name('production_edit');
    Route::delete('production_delete/{production_code}', [ProductionProductController::class, 'destroy'])->name('production_delete');
    Route::put('update_target_production/{production_code}', [ProductionProductController::class, 'update_target_production'])->name('update_target_production');
    Route::put('update_production_status/{production_code}', [ProductionProductController::class, 'update_production_status'])->name('update_production_status');
    Route::get('/production_products', function () {
        return view('pages.production_product');
    })->name('production_products');


    // Routes Category
    Route::apiResource('master_category', App\Http\Controllers\Api\ItemsCategoryController::class);
    Route::get('category_create', [ItemsCategoryController::class, 'category_create'])->name('category_create');
    Route::get('category_update/{id}', [ItemsCategoryController::class, 'category_update'])->name('category_update');
    Route::put('category_edit/{id}', [ItemsCategoryController::class, 'update'])->name('category_edit');

    // Route for Transactions
    Route::apiResource('transaction', App\Http\Controllers\Api\TransactionController::class);
    Route::get('transaction_create', [TransactionController::class, 'transaction_create_layout'])->name('transaction_create');
    Route::get('invoice_detail/{transaction_code}', [TransactionController::class, 'invoice'])->name('invoice_detail');
    Route::get('/show_promo_code', [TransactionController::class, 'show_promo_code'])->name('show_promo_code');
    Route::get('/search_customer', [TransactionController::class,'show_customer'])->name('search_customer');

    // Route Shopping Cart 
    Route::post('/cart/add', [ShoppingCartController::class, 'add'])->name('cart_add');
    Route::post('clear_cart', [ShoppingCartController::class, 'clear_cart_session'])->name('clear_cart');
    Route::delete('delete_item_cart/{product_code}', [ShoppingCartController::class, 'delete_cart_product'])->name('delete_item_cart');

    // Route Discount
    Route::apiResource('discount', App\Http\Controllers\Api\DiscountController::class);
    Route::get('discount_create', [DiscountController::class, 'discount_create_layout'])->name('discount_create');
    Route::get('update_discount/{id}', [DiscountController::class, 'edit_layout'])->name('update_discount');
    Route::put('edit_discount/{id}', [DiscountController::class, 'update'])->name('edit_discount');
});

require __DIR__ . '/auth.php';