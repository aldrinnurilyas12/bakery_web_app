<?php

use App\Http\Controllers\Api\BusinessIntelligence;
use App\Http\Controllers\Api\CentralStockProductsController;
use App\Http\Controllers\Api\CustomerForgotPasswordRequest;
use App\Http\Controllers\Api\GetPointMemberTransaction;
use App\Http\Controllers\Api\DailyProducts;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\DistributionProducts;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\HomepageController;
use App\Http\Controllers\Api\ItemsCategoryController;
use App\Http\Controllers\Api\ItemsController;
use App\Http\Controllers\Api\MainApp\CustomerController;
use App\Http\Controllers\Api\MainApp\HomePageController as MainAppHomePageController;
use App\Http\Controllers\Api\MainApp\RegisteredCustomer;
use App\Http\Controllers\Api\ProductsController;
use App\Http\Controllers\Api\PromoBundling;
use App\Http\Controllers\Api\PromoCampaignController;
use App\Http\Controllers\Api\RawMaterialController;
use App\Http\Controllers\Api\ShoppingCartController;
use App\Http\Controllers\Api\RewardsController;
use App\Http\Controllers\Api\Voucher;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\ProductionProductController;
use App\Http\Controllers\Api\StoreOutletController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Api\MasterMainMenu;
use App\Http\Controllers\Api\PurchaseOrderController;
use App\Http\Controllers\Api\RequestForgotPassword;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\VariantCategory;
use App\Http\Controllers\GoogleOauthController;
use App\http\Controllers\Api\ModulesDocumentation;
use App\Http\Controllers\Api\FraudAnalysis;
use App\Http\Controllers\Api\CustomerSegmentCategories;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Cors;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProductsController as ControllersProductsController;
use App\Http\Controllers\ShowProducts;
use App\Http\Controllers\OmdbApiServices;
use App\Models\ModuleDocumentation;
use Illuminate\Support\Facades\View;

Route::get('dashboard', function () {
    return view('layouts.main_pages.home_page');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('products', [ShowProducts::class, 'show_products']);
Route::get('promos', [ShowProducts::class, 'show_promos']);
Route::get('product_detail/{product_code}', [ShowProducts::class, 'product_detail']);

// ROUTE FOR MAIN APP:
Route::get('login_app', [RegisteredCustomer::class, 'login_layouts'])->name('login_app');
Route::get('register_account', [RegisteredCustomer::class, 'register_layouts'])->name('register_account');
Route::get('account_verification/{customer_code}', [RegisteredCustomer::class, 'account_verification'])->name('account_verification');
Route::get('home', [MainAppHomePageController::class, 'homepage'])->name('home');
Route::get('product/{slug}', [MainAppHomePageController::class, 'product_detail'])->name('product');
Route::get('promo/{bundling_code}', [MainAppHomePageController::class, 'promo_detail'])->name('promo');
Route::get('rewards-catalogue', [CustomerController::class, 'rewards_catalogue'])->name('rewards-catalogue');
Route::post('register_member_account', [RegisteredCustomer::class, 'store'])->name('register_member_account');
Route::post('login_execute', [AuthenticatedSessionController::class, 'request_sign_in'])->name('login_execute');
Route::get('product-search/', [MainAppHomePageController::class, 'product_search'])->name('product-search');
Route::get('promo-campaign', [MainAppHomePageController::class, 'promo_campaign'])->name('promo-campaign')->middleware('route_access');
Route::get('promo-detail/{promo_code}', [MainAppHomePageController::class, 'promo_campaign_detail'])->name('promo-detail');
Route::get('reward-detail/{rewards_code}', [CustomerController::class, 'reward_detail'])->name('reward-detail');
Route::get('fstore/', [MainAppHomePageController::class, 'filter_store'])->name('fstore');
Route::apiResource('customer-forgot-password', App\Http\Controllers\Api\CustomerForgotPasswordRequest::class);
Route::get('forgot-password-help', [CustomerForgotPasswordRequest::class, 'create'])->name('forgot-password-help');
Route::post('check-email-otp', [CustomerForgotPasswordRequest::class, 'send_otp'])->name('check-email-otp');
Route::get('otp-confirmation-request', [CustomerForgotPasswordRequest::class, 'otp_confirmation'])->name('otp-confirmation-request');
Route::post('otp-proccess-request', [CustomerForgotPasswordRequest::class, 'otp_auth_confirmation'])->name('otp-proccess-request');
Route::get('change-password-req', [CustomerForgotPasswordRequest::class, 'change_password_layouts'])->name('change-password-req');
Route::put('change-password-proccessing/{email}', [CustomerForgotPasswordRequest::class, 'change_password_proccess'])->name('change-password-proccessing');




// for testing get API Eksternal
// Route::get('omdb_services', [OmdbApiServices::class, 'index']);
// Route::get('search-movies', [OmdbApiServices::class, 'search_movies'])->name('search-movies');

// OAuth Google:
Route::get('/auth/google', [GoogleOauthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleOauthController::class, 'callback']);


Route::middleware(['customer', 'nocache'])->group(function () {
    Route::get('profile', [CustomerController::class, 'profile'])->name('profile')->middleware('route_access');
    Route::get('profile-menu', [CustomerController::class, 'menu'])->name('profile-menu');
    Route::get('history-transactions', [CustomerController::class, 'history_transaction'])->name('history-transaction')->middleware('route_access');
    Route::get('invoice/{transaction_code}', [CustomerController::class, 'invoice'])->name('invoice');
    Route::get('your-voucher', [CustomerController::class, 'customer_voucher'])->name('your-voucher')->middleware('route_access');
    Route::get('change-password-help', [CustomerController::class, 'change_password_layout'])->name('change-password-help')->middleware('route_access');
    Route::put('nonactive_account/{customer_code}', [CustomerController::class, 'nonactive_account'])->name('nonactive_account');
    Route::put('update_password', [CustomerController::class, 'update_customer_password'])->name('update_password');
    Route::post('add_favorite', [CustomerController::class, 'favorite_product'])->name('add_favorite');
    Route::get('favorite', [CustomerController::class, 'favorite_list'])->name('favorite');
    Route::post('logout_account', [AuthenticatedSessionController::class, 'logout_session_account'])->name('logout_account');
    Route::get('notification', [CustomerController::class, 'notification'])->name('notification')->middleware('route_access');
    Route::put('update_customer/{customer_code}', [CustomerController::class, 'update_customer'])->name('update_customer');
    Route::delete('remove-favorite/{product_code}', [CustomerController::class, 'remove_favorite'])->name('remove-favorite');
    Route::post('redeem-reward', [CustomerController::class, 'redeem_reward'])->name('redeem-reward');
    Route::get('rewards-history', [CustomerController::class,'rewards_customer_history'])->name('rewards-history')->middleware('route_access');
    Route::get('get_stock/{rewards_code}', [CustomerController::class,'get_stock'])->name('get_stock');
    Route::get('/invoice_cust/{transaction_code}/print', [CustomerController::class, 'download_pdf_cust']);
    Route::get('invoice_pdf/{transaction_code}', [CustomerController::class, 'download_invoice_pdf'])->name('invoice_pdf');
    Route::put('generate_qr_code', [CustomerController::class, 'generate_qr_code'])->name('generate_qr_code');
    Route::post('product_review_save', [CustomerController::class, 'product_review_save'])->name('product_review_save');
});




// ROUTE FOR WEB ADMIN:
Route::get('/admin_kencana_bakery', function () {
    return view('layouts.main_pages.welcome_page');
});

Route::get('login_kencana_bakery',[AuthenticatedSessionController::class,'create'])->name('login_kencana_bakery');
Route::post('login_exe', [AuthenticatedSessionController::class, 'store'])->name('login_exe');
Route::apiResource('forgot-password', App\Http\Controllers\Api\RequestForgotPassword::class);
Route::get('request-forgot-password', [RequestForgotPassword::class, 'create'])->name('request-forgot-password');
Route::post('password-auth-proccess', [RequestForgotPassword::class, 'password_proccess_auth'])->name('password-auth-proccess');
Route::get('otp-confirmation', [RequestForgotPassword::class, 'otp_confirmation'])->name('otp-confirmation');
Route::post('otp-proccess', [RequestForgotPassword::class, 'otp_auth_confirmation'])->name('otp-proccess');
Route::get('change-password', [RequestForgotPassword::class, 'change_password_layouts'])->name('change-password')->middleware('route_access');
Route::put('change-password-proccess/{email}', [RequestForgotPassword::class, 'change_password_proccess'])->name('change-password-proccess');
Route::get('user_account_verification/{nik}', [RegisteredUserController::class, 'account_verification'])->name('user_account_verification');



Route::middleware(['auth', 'nocache'])->group(function () {
    Route::get('dashboard_main', [HomepageController::class, 'index'])->name('dashboard_main');
    Route::get('profile_information', [ProfileController::class, 'user_profile'])->name('profile_information');
    Route::put('profile_update/{id}', [ProfileController::class, 'update'])->name('profile_update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('welcome', [HomePageController::class, 'welcome_page'])->name('welcome');

    // USERS Route
    Route::apiResource('user_register', App\Http\Controllers\Auth\RegisteredUserController::class);
    Route::get('users_register_account', [RegisteredUserController::class, 'show_users_register'])->name('users_register_account')->middleware('route_access');
    Route::get('users_data', [RegisteredUserController::class, 'master_main_users'])->name('users_data')->middleware('route_access');
    Route::post('signout', [AuthenticatedSessionController::class, 'destroy'])->name('signout');
    Route::get('users_edit/{nik}' , [RegisteredUserController::class, 'edit_users_layout'])->name('users_edit')->middleware('route_access');
    Route::put('users_update/{nik}' , [RegisteredUserController::class, 'update'])->name('users_update');
    Route::delete('users_delete/{id}' , [RegisteredUserController::class, 'destroy'])->name('users_delete');
    Route::put('user_profile_update/{id}', [EmployeeController::class, 'update_user_profile'])->name('user_profile_update');
    Route::put('user_active_update/{nik}', [RegisteredUserController::class, 'update_user_active'])->name('user_active_update');
    Route::get('get_email/{emp_nik}', [RegisteredUserController::class,'get_email']);
    Route::delete('delete_role/{user_role_id}', [RegisteredUserController::class, 'delete_role'])->name('delete_role');

    Route::apiResource('point_member_setting', App\Http\Controllers\Api\GetPointMemberTransaction::class)->middleware('route_access');
    Route::get('point_create', [GetPointMemberTransaction::class, 'create'])->name('point_create')->middleware('route_access');
    Route::put('point_update_status/{id}', [GetPointMemberTransaction::class, 'update'])->name('point_update_status');
    
    //  Customer API
    Route::apiResource('master_customers', App\Http\Controllers\Api\CustomerController::class)->middleware('route_access');
    Route::apiResource('customer_segment', App\Http\Controllers\Api\CustomerSegmentCategories::class)->middleware('route_access');
    Route::get('customer_segment_create', [CustomerSegmentCategories::class, 'create' ] )->name('customer_segment_create');


    // Employee API
    Route::apiResource('master_employee', App\Http\Controllers\Api\EmployeeController::class)->middleware('route_access');
    Route::get('employee_create', [EmployeeController::class, 'create'])->name('employee_create');
    Route::get('employee_edit/{nik}', [EmployeeController::class, 'employee_edit_layout'])->name('employee_edit')->middleware('route_access');
    Route::put('update_employee/{id}', [EmployeeController::class, 'update'])->name('update_employee');
    Route::put('employee_update_status/{nik}', [EmployeeController::class, 'employee_nonactive'])->name('employee_update_status');
    Route::put('change_password_employee/{nik}', [EmployeeController::class, 'update_password_employee'])->name('change_password_employee');


    // Products Route
    Route::apiResource('master_products', App\Http\Controllers\Api\ProductsController::class);
    Route::get('product_create', [ProductsController::class, 'create'])->name('product_create')->middleware('route_access');
    Route::get('product_update/{product_code}', [ProductsController::class, 'product_update_layout'])->name('product_update')->middleware('route_access');
    Route::put('edit_product/{product_code}', [ProductsController::class, 'update'])->name('edit_product');
    Route::delete('product_delete/', [ProductsController::class, 'destroy'])->name('product_delete');
    Route::delete('delete_image/{id}', [ProductsController::class, 'delete_images'])->name('delete_image');
    Route::get('/products_data', function () {
        return view('pages.product-data');
    })->name('products_data')->middleware('route_access');
    Route::put('update_status_product/{product_code}', [ProductsController::class, 'update_status_product'])->name('update_status_product');
    Route::get('product_price/{product_code}', [ProductsController::class, 'add_product_price_layout'])->name('product_price')->middleware('route_access');
    Route::put('product_price_save/{product_code}', [ProductsController::class, 'add_product_price'])->name('product_price_save');
    Route::get('product_price_update/{product_code}', [ProductsController::class, 'update_product_price_layout'])->name('product_price_update');
    Route::put('product_price_edit/{product_code}', [ProductsController::class, 'update_product_price'])->name('product_price_edit');
    Route::get('add_ingredients/{product_code}', [ProductsController::class, 'add_ingredients_layouts'])->name('add_ingredients')->middleware('route_access');
    Route::post('save_ingredients', [ProductsController::class, 'save_ingredients'])->name('save_ingredients');
    Route::get('product-price-history/{product_code}/{variant?}', [ProductsController::class, 'product_price_history'])->name('product-price-history');
    Route::get('bill-of-material/{product}', [ProductsController::class, 'bill_of_material'])->name('bill-of-material');
    Route::get('bill-of-material-detail/{ingredients_code}', [ProductsController::class, 'bill_of_material_detail'])->name('bill-of-material-detail');
    Route::put('update_status_bom/{ingredients_code}', [ProductsController::class, 'update_status_bom'])->name('update_status_bom');

    Route::get('add_product_variant/{product_code}', [ProductsController::class, 'add_product_variant_layout'])->name('add_product_variant');
    Route::get('update_variant/{variant_code}', [ProductsController::class, 'update_variant_layout'])->name('update_variant')->middleware('route_access');
    Route::post('save_product_variant', [ProductsController::class, 'save_product_variant'])->name('save_product_variant');
    Route::put('edit_variant/{variant_code}', [ProductsController::class, 'edit_variant'])->name('edit_variant');
    Route::delete('delete_variant/{variant_code}', [ProductsController::class, 'delete_variant'])->name('delete_variant');
    Route::put('delete_variant_product/{product_code}', [ProductsController::class, 'update_product_variant'])->name('delete_variant_product');
    Route::get('product-review-detail/{product_code}', [ProductsController::class, 'product_review'])->name('product-review-detail');

    // Products Bundling 
    Route::apiResource('promo_bundling', App\Http\Controllers\Api\PromoBundling::class);
    Route::get('promo_bundling_create', [PromoBundling::class, 'create'])->name('promo_bundling_create');
    Route::put('bundling_nonactive/{bundling_code}', [PromoBundling::class,'bundling_nonactive'])->name('bundling_nonactive');






    // DailyProducts Route
    Route::apiResource('master_daily_products', App\Http\Controllers\Api\DailyProducts::class);
    Route::put('daily_product_edit/{daily_code}', [DailyProducts::class, 'update'])->name('daily_product_edit');
    Route::put('nonactive_daily_product/{product}', [DailyProducts::class, 'nonactive_daily_product'])->name('nonactive_daily_product');
    Route::get('dailyproduct_create', [DailyProducts::class, 'create'])->name('dailyproduct_create')->middleware('route_access');
    Route::get('dailyproduct_update/{daily_code}', [DailyProducts::class, 'edit'])->name('dailyproduct_update')->middleware('route_access');
    Route::delete('dailyproduct_delete/{product_code}', [DailyProducts::class, 'destroy'])->name('dailyproduct_delete');
    Route::get('/dailyproducts_data', function () {
        return view('pages.dailyproduct-data');
    })->name('dailyproducts_data')->middleware('route_access');;
    Route::get('/filter_data', [DailyProducts::class, 'filter'])->name('filter_data');
    Route::delete('dailyproduct_delete_variant/{variant_code}', [DailyProducts::class, 'delete_variant'])->name('dailyproduct_delete_variant');
    Route::get('get_stock_product/{distribution_store}', [DailyProducts::class, 'get_stock']);
    Route::get('daily-product-detail/{product}/{variant?}', [DailyProducts::class, 'daily_product_detail'])->name('daily-product-detail');
    Route::put('update_expired_status_distribution/{distribution_store_code}', [DailyProducts::class, 'update_expired_status_distribution'])->name('update_expired_status_distribution');
    // Route Promo Campaign
    Route::apiResource('master_promo_campaign', App\Http\Controllers\Api\PromoCampaignController::class);
    Route::get('promo_create', [PromoCampaignController::class, 'create'])->name('promo_create')->middleware('route_access');
    Route::get('promo_update/{promo_code}', [PromoCampaignController::class, 'edit'])->name('promo_update')->middleware('route_access');
    Route::put('promo_edit/{promo_code}', [PromoCampaignController::class, 'update'])->name('promo_edit');
    Route::delete('promo_delete/{promo_code}', [PromoCampaignController::class, 'destroy'])->name('promo_delete');
    Route::get('/promo_campaign', function () {
        return view('pages.promo-campaign');
    })->name('promo_campaign')->middleware('route_access');

    // Rewards Routes
    Route::apiResource('master_rewards', App\Http\Controllers\Api\RewardsController::class);
    Route::get('rewards_create', [RewardsController::class, 'create'])->name('rewards_create')->middleware('route_access');
    Route::get('rewards_update/{reward_store_code}', [RewardsController::class, 'edit'])->name('rewards_update')->middleware('route_access');
    Route::get('rewards_master_update/{rewards_code}', [RewardsController::class, 'edit_master_reward'])->name('rewards_master_update')->middleware('route_access');
    Route::put('rewards_edit/{rewards_code}', [RewardsController::class, 'update'])->name('rewards_edit');
    Route::put('rewards_update_store/{reward_store_code}', [RewardsController::class, 'update_reward_store'])->name('rewards_update_store');
    Route::put('rewards_nonactive/{reward}', [RewardsController::class, 'update_nonactive_rewards'])->name('rewards_nonactive');
    Route::delete('rewards_delete/{rewards_code}', [RewardsController::class, 'destroy'])->name('rewards_delete');
    Route::get('/rewards', function () {
        return view('pages.rewards');
    })->name('rewards')->middleware('route_access');
    Route::get('claim-reward', [RewardsController::class, 'claim_reward_layouts'])->name('claim-reward')->middleware('route_access');
    Route::put('claimed-reward/{redeem_code}', [RewardsController::class, 'claimed_reward'])->name('claimed-reward');
    Route::get('/filter_rewards', [RewardsController::class, 'filter_rewards'])->name('filter_rewards');

    // Route Voucher
    Route::apiResource('master_voucher', App\Http\Controllers\Api\Voucher::class);
    Route::get('voucher_create', [Voucher::class, 'create'])->name('voucher_create')->middleware('route_access');
    Route::get('voucher_update/{voucher_code}', [Voucher::class, 'edit'])->name('voucher_update')->middleware('route_access');
    Route::put('voucher_edit/{voucher_code}', [Voucher::class, 'update'])->name('voucher_edit');
    Route::put('nonactive_voucher/{voucher_code}', [Voucher::class, 'update_nonactive_voucher'])->name('nonactive_voucher');
    Route::delete('voucher_delete/{voucher_code}', [Voucher::class, 'destroy'])->name('voucher_delete');
    Route::get('/voucher_data', function () {
        return view('pages.voucher');
    })->name('voucher_data')->middleware('route_access');
    Route::get('redeem_vouchers', [Voucher::class, 'redeem_voucher_data'])->name('redeem_vouchers')->middleware('route_access');

    // Raw Material Route
    Route::apiResource('master_material', App\Http\Controllers\Api\RawMaterialController::class);
    Route::get('material_create', [RawMaterialController::class, 'create'])->name('material_create')->middleware('route_access');
    Route::get('material_update/{material_code}', [RawMaterialController::class, 'edit'])->name('material_update')->middleware('route_access');
    Route::put('material_edit/{material_code}', [RawMaterialController::class, 'update'])->name('material_edit');
    Route::delete('material_delete/{material_code}', [RawMaterialController::class, 'destroy'])->name('material_delete');
    Route::get('/raw_material', function () {
        return view('pages.raw_material');
    })->name('raw_material')->middleware('route_access');
    Route::get('history_raw_material/{material_code}', [RawMaterialController::class, 'history_raw_material'])->name('history_raw_material')->middleware('route_access');
    Route::get('raw_material_usages/{material_code}', [RawMaterialController::class, 'raw_material_usages'])->name('raw_material_usages')->middleware('route_access');
    Route::post('unit_material_save', [RawMaterialController::class, 'unit_material_save'])->name('unit_material_save');
    Route::get('unit_material', [RawMaterialController::class, 'unit_material'])->name('unit_material');
    Route::get('unit_material_create', [RawMaterialController::class, 'unit_material_create'])->name('unit_material_create');
    Route::get('unit_material_edit/{id}', [RawMaterialController::class, 'unit_material_edit'])->name('unit_material_edit');
    Route::put('unit_material_update/{id}', [RawMaterialController::class, 'unit_material_update'])->name('unit_material_update');
    Route::delete('unit_material_delete/{id}', [RawMaterialController::class, 'unit_material_delete'])->name('unit_material_delete');

    // Items
    Route::apiResource('master_items', App\Http\Controllers\Api\ItemsController::class)->middleware('route_access');
    Route::get('item_create', [ItemsController::class, 'create'])->name('item_create')->middleware('route_access');
    Route::get('item_update/{item_code}', [ItemsController::class, 'update_layout'])->name('item_update')->middleware('route_access');
    Route::put('edit_item/{item_code}', [ItemsController::class, 'edit_item'])->name('edit_item');

    // Purchase Order Routes
    Route::apiResource('purchase_order', App\Http\Controllers\Api\PurchaseOrderController::class)->middleware('route_access');
    Route::get('po_create', [PurchaseOrderController::class, 'create'])->name('po_create')->middleware('route_access');
    Route::get('get_category/{supplier}', [PurchaseOrderController::class, 'get_category']);
    Route::get('purchase_detail/{purchase_code}', [PurchaseOrderController::class, 'get_detail'])->name('purchase_detail')->middleware('route_access');

    // Production Product Route
    Route::apiResource('master_production_product', App\Http\Controllers\Api\ProductionProductController::class);
    Route::get('production_create', [ProductionProductController::class, 'create'])->name('production_create');
    Route::get('production_update/{production_code}', [ProductionProductController::class, 'edit'])->name('production_update');
    Route::put('production_edit/{production_code}', [ProductionProductController::class, 'update'])->name('production_edit');
    Route::delete('production_delete/{production_code}', [ProductionProductController::class, 'destroy'])->name('production_delete');
    Route::put('update_target_production/{production_code}', [ProductionProductController::class, 'update_target_production'])->name('update_target_production');
    Route::put('update_production_status/{production_code}', [ProductionProductController::class, 'update_production_status'])->name('update_production_status');
    Route::put('production_reason_cancelled/{production_code}', [ProductionProductController::class, 'update_production_reason'])->name('production_reason_cancelled');
    Route::get('/production_products', function () {
        return view('pages.production_product');
    })->name('production_products')->middleware('route_access');
    Route::get('production-detail/{production_code}', [ProductionProductController::class,'production_detail'])->name('production-detail')->middleware('route_access');
    Route::put('production_detail_update/{id}', [ProductionProductController::class,'production_detail_update'])->name('production_detail_update');
    Route::get('/filter_production_product', [ProductionProductController::class, 'filter_production'])->name('filter_production_product');
    Route::get('get_variant/{product}', [ProductionProductController::class,'get_variant']);
    Route::get('get_variant_code/{product}/{variant}', [ProductionProductController::class, 'get_variant_code']);
    Route::get('get_ingredients/{product}', [ProductionProductController::class, 'get_ingredients']);
    Route::get('product-waste-production/{production_id}', [ProductionProductController::class, 'production_waste_create'])->name('product-waste-production')->middleware('route_access');
    Route::put('production_waste_save/{production_detail_id}', [ProductionProductController::class, 'production_waste_save'])->name('production_waste_save');

    // Central Stock Products
    Route::apiResource('central_stock_products', App\Http\Controllers\Api\CentralStockProductsController::class)->middleware('route_access');
    Route::get('detail-info-product/{product}/{variant?}', [CentralStockProductsController::class, 'product_info'])->name('detail-info-product');
    Route::get('detail-info-distribution/{product}/{variant?}', [CentralStockProductsController::class, 'product_info_distribution'])->name('detail-info-distribution');

    // Distribution Stock to Stores
    Route::apiResource('distribution_products', App\Http\Controllers\Api\DistributionProducts::class)->middleware('route_access');
    Route::get('distribution_create', [ DistributionProducts::class, 'distribution_create'])->name('distribution_create')->middleware('route_access');
    Route::get('distribution_detail/{distribution_code}', [ DistributionProducts::class, 'distribution_detail_layouts'])->name('distribution_detail')->middleware('route_access');
    Route::put('distribution_store_update/{distribution_store_code}', [ DistributionProducts::class, 'edit'])->name('distribution_store_update');


    // Products Waste
    Route::post('product_waste_save', [ProductionProductController::class, 'product_waste_save'])->name('product_waste_save');
    Route::get('product-wastes', [ProductionProductController::class, 'product_waste'])->name('product-wastes')->middleware('route_access');;
    Route::get('product-waste-create', [ProductionProductController::class, 'waste_create'])->name('product-waste-create')->middleware('route_access');
    Route::get('product-waste-distribution/{distribution_store_code}', [ProductionProductController::class, 'waste_distribution_create'])->name('product-waste-distribution')->middleware('route_access');
    Route::delete('waste-delete/{waste_code}', [ProductionProductController::class, 'waste_delete'])->name('waste-delete');
    Route::get('waste-update/{waste_code}', [ProductionProductController::class,'product_waste_update'])->name('waste-update');
    Route::put('product_waste_edit', [ProductionProductController::class, 'product_waste_edit'])->name('product_waste_edit');
    Route::put('waste_distribution_save/{distribution_code}', [DistributionProducts::class, 'product_waste_distribution_save'])->name('waste_distribution_save');
    Route::get('/filter_wastes', [ProductionProductController::class, 'filter_wastes'])->name('filter_wastes');
    Route::get('get_qty_product/{daily_code}', [DailyProducts::class, 'get_qty']);
    Route::get('product-waste-data', [ProductionProductController::class, 'product_waste_data'])->name('product-waste-data')->middleware('route_access');
    Route::get('product-waste-detail/{waste_code}', [ProductionProductController::class, 'product_waste_detail'])->name('product-waste-detail')->middleware('route_access');


    // Routes Category
    Route::apiResource('master_category', App\Http\Controllers\Api\ItemsCategoryController::class)->middleware('route_access');
    Route::get('category_create', [ItemsCategoryController::class, 'category_create'])->name('category_create')->middleware('route_access');
    Route::get('category_update/{id}', [ItemsCategoryController::class, 'category_update'])->name('category_update')->middleware('route_access');
    Route::put('category_edit/{id}', [ItemsCategoryController::class, 'update'])->name('category_edit');

    Route::apiResource('variant_category', App\Http\Controllers\Api\VariantCategory::class)->middleware('route_access');
    Route::get('variant_category_create', [VariantCategory::class,'create'])->name('variant_category_create');
    Route::get('variant_category_update/{id}', [VariantCategory::class,'edit'])->name('variant_category_update');
    Route::put('variant_category_edit/{id}', [VariantCategory::class,'update'])->name('variant_category_edit');
    Route::delete('delete_variant_category/{id}', [VariantCategory::class,'delete_variant_category'])->name('delete_variant_category');

    // Route for Transactions
    Route::apiResource('transaction', App\Http\Controllers\Api\TransactionController::class)->middleware('route_access');
    Route::get('transaction_create', [TransactionController::class, 'transaction_create_layout'])->name('transaction_create');
    Route::get('invoice_detail/{transaction_code}', [TransactionController::class, 'invoice'])->name('invoice_detail');
    Route::get('/invoice/{transaction_code}/print', [TransactionController::class, 'download_pdf']);
    Route::get('/show_promo_code', [TransactionController::class, 'show_promo_code'])->name('show_promo_code');
    Route::get('/search_customer', [TransactionController::class,'show_customer'])->name('search_customer');
    Route::get('/filter_transaction', [TransactionController::class, 'filter_transaction'])->name('filter_transaction');
    Route::post('export_transaction_excel', [TransactionController::class, 'download_excel'])->name('export_transaction_excel');
    Route::get('invoice_pdf_download/{transaction_code}', [TransactionController::class, 'download_invoice_pdf'])->name('invoice_pdf_download');
    // Route Fraud Transactions
    Route::apiResource('fraud-analysis', App\Http\Controllers\Api\FraudAnalysis::class);
    Route::put('update_fraud_transaction/{fraud_code}', [FraudAnalysis::class, 'update_status_fraud'])->name('update_fraud_transaction');

    // Route Shopping Cart 
    Route::post('/cart/add', [ShoppingCartController::class, 'add'])->name('cart_add');
    Route::post('clear_cart', [ShoppingCartController::class, 'clear_cart_session'])->name('clear_cart');
    Route::delete('delete_item_cart/{product_code}', [ShoppingCartController::class, 'delete_cart_product'])->name('delete_item_cart');

    // Route Discount
    Route::apiResource('discount', App\Http\Controllers\Api\DiscountController::class);
    Route::get('discount_create', [DiscountController::class, 'discount_create_layout'])->name('discount_create')->middleware('route_access');
    Route::get('update_discount/{id}', [DiscountController::class, 'edit_layout'])->name('update_discount')->middleware('route_access');
    Route::put('edit_discount/{id}', [DiscountController::class, 'update'])->name('edit_discount');

    // ROUTE STORE
    Route::apiResource('store', App\Http\Controllers\Api\StoreOutletController::class)->middleware('route_access');
    Route::get('store_create', [StoreOutletController::class, 'store_create_layout'])->name('store_create');
    Route::get('store_update/{store_code}', [StoreOutletController::class, 'edit_layout'])->name('store_update');
    Route::put('edit_store/{store_code}', [StoreOutletController::class, 'update'])->name('edit_store');
    Route::put('delete_head_store/{store_code}', [StoreOutletController::class, 'delete_head_store'])->name('delete_head_store');
    Route::put('update_status_store/{store_code}', [StoreOutletController::class, 'update_status_store'])->name('update_status_store');

    Route::apiResource('master_main_menu', App\Http\Controllers\Api\MasterMainMenu::class)->middleware('route_access');
    Route::get('main_menu_create', [MasterMainMenu::class, 'create'])->name('main_menu_create')->middleware('route_access');
    Route::get('main_menu_update/{id}', [MasterMainMenu::class, 'update'])->name('main_menu_update')->middleware('route_access');
    Route::put('main_menu_edit/{id}', [MasterMainMenu::class, 'main_menu_edit'])->name('main_menu_edit');

    // Submenu:
    Route::get('submenu_create/{id}', [MasterMainMenu::class, 'submenu_create'])->name('submenu_create')->middleware('route_access');
    Route::get('submenu_update/{submenu_id}', [MasterMainMenu::class, 'submenu_update'])->name('submenu_update')->middleware('route_access');
    Route::post('submenu_save', [MasterMainMenu::class, 'submenu_save'])->name('submenu_save');
    Route::get('submenu_list/{id}', [MasterMainMenu::class, 'submenu_list'])->name('submenu_list');
    Route::put('submenu_edit/{id}', [MasterMainMenu::class, 'submenu_edit'])->name('submenu_edit');
    Route::put('submenu_change_status/{id}', [MasterMainMenu::class, 'submenu_change_status'])->name('submenu_change_status');
    Route::put('submenu_update_status', [MasterMainMenu::class, 'submenu_update_status'])->name('submenu_update_status');
    Route::delete('submenu_delete/{id}', [MasterMainMenu::class, 'submenu_delete'])->name('submenu_delete');

    // ROUTE Business Intelligence
    Route::apiResource('business_intelligence', App\Http\Controllers\Api\BusinessIntelligence::class)->middleware('route_access');
    Route::get('data_analytics', [BusinessIntelligence::class, 'data_analytics_layouts'])->name('data_analytics')->middleware('route_access');
     Route::get('data_analytics_customer', [BusinessIntelligence::class, 'data_analytics_customer'])->name('data_analytics_customer')->middleware('route_access');
    Route::get('sales_performance', [BusinessIntelligence::class, 'sales_performance'])->name('sales_performance')->middleware('route_access');
    Route::get('business_reports', [BusinessIntelligence::class, 'main_reports_layouts'])->name('business_reports')->middleware('route_access');
    Route::get('generate_reports', [BusinessIntelligence::class, 'generate_reports'])->name('generate_reports');
    Route::get('download_transaction_report', [BusinessIntelligence::class, 'exportPdfTransaction'])->name('download_transaction_report');
    Route::get('download_products_daily_report', [BusinessIntelligence::class, 'exportPdfProductDaily'])->name('download_products_daily_report');
    Route::get('download_production_product_report', [BusinessIntelligence::class, 'exportPdfProductionProduct'])->name('download_production_product_report');
    Route::get('download_distribution_report', [BusinessIntelligence::class, 'exportPdfDistributionProduct'])->name('download_distribution_report');
    Route::get('filter_dashboard', [BusinessIntelligence::class, 'filter_dashboard'])->name('filter_dashboard');

    // Route for Supplier
    Route::apiResource('supplier', App\Http\Controllers\Api\SupplierController::class)->middleware('route_access');
    Route::get('supplier_create', [SupplierController::class, 'create'])->name('supplier_create')->middleware('route_access')->middleware('route_access');
    Route::get('supplier_update/{supplier_code}', [SupplierController::class, 'update_layouts'])->name('supplier_update')->middleware('route_access');
    Route::put('supplier_edit/{supplier_code}', [SupplierController::class, 'edit'])->name('supplier_edit');
    Route::get('supplier_category', [SupplierController::class, 'supplier_category'])->name('supplier_category')->middleware('route_access');
    Route::get('category_supplier_create', [SupplierController::class, 'category_supplier_layouts'])->name('category_supplier_create')->middleware('route_access');
    Route::post('category_supplier_save', [SupplierController::class, 'category_supplier_save'])->name('category_supplier_save');
    Route::get('category_supplier_update/{id}', [SupplierController::class, 'category_supplier_update'])->name('category_supplier_update')->middleware('route_access');
    Route::put('category_supplier_edit/{id}', [SupplierController::class, 'category_supplier_edit'])->name('category_supplier_edit');

    // Route for modules documentation:
    Route::apiResource('modules_documentation', App\Http\Controllers\Api\ModulesDocumentation::class)->middleware('route_access');
    Route::get('module_create', [ModulesDocumentation::class, 'module_create'] )->name('module_create')->middleware('route_access');
    Route::get('module_update/{url_path}', [ModulesDocumentation::class, 'edit'] )->name('module_update')->middleware('route_access');
    Route::put('module_edit/{url_path}', [ModulesDocumentation::class, 'update'] )->name('module_edit');
    Route::delete('delete_module/{url_path}', [ModulesDocumentation::class, 'destroy'])->name('delete_module');
    Route::get('show_module_documentation/{url_path}', [ModulesDocumentation::class, 'show_module'])->name('show_module_documentation');

    Route::get('log_user_activities', [RegisteredUserController::class, 'log_users_activities'])->name('log_user_activities');
}); 

require __DIR__ . '/auth.php';