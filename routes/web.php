<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Backend\{DashboardController, SettingController};
use App\Http\Controllers\Backend\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Backend\users\UserController;
use App\Http\Controllers\Backend\customers\CustomerController;
use App\Http\Controllers\Backend\suppliers\SupplierController;
use App\Http\Controllers\Backend\products\ProductController;
use App\Http\Controllers\Backend\products\ProductVariantController;
use App\Http\Controllers\Backend\rents\{RentController, RentReturnController, RentPaymentController};
use App\Http\Controllers\Backend\quotation\QuotationController;
use App\Http\Controllers\Backend\staffs\StaffController;
use App\Http\Controllers\Backend\sales\SaleController;
use App\Http\Controllers\Backend\customInvoice\CustomInvoiceController;
use App\Http\Controllers\Backend\expenses\ExpenseController;
use App\Http\Controllers\Backend\purchases\PurchaseController;
use App\Http\Controllers\CustomerRentController;
use App\Http\Controllers\Frontend\{HomeController};
use App\Http\Controllers\Frontend\auth\CustomerAuthenticationController;
use App\Http\Controllers\Frontend\products\ProductController as ProductsProductController;
use App\Http\Controllers\Frontend\QuotationController as FrontendQuotationController;
use App\Http\Controllers\Frontend\CustomerDashboardController;

// dashboard pages
Route::get('/', function () {
    return redirect()->route('frontend.home');
});

// calender pages
Route::get('/calendar', function () {
    return view('pages.calender', ['title' => 'Calendar']);
})->name('calendar');

// error pages
Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');


# Backend Auth Routes
Route::group(['prefix' => 'admin',  'middleware' => ['guest']], function () {

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::group(['prefix' => 'admin',  'middleware' => ['auth']], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])
        ->name('admin.dashboard.chart-data');

    // Custom route to allow /admin/users/index to call the index method
    Route::get('/users/index', [UserController::class, 'index'])->name('users.index.custom');

    Route::resource('/users', UserController::class)->names([
        'index'   => 'users.index',
        'create'  => 'users.create',
        'store'   => 'users.store',
        'show'    => 'users.show',
        'edit'    => 'users.edit',
        'update'  => 'users.update',
        'destroy' => 'users.destroy',
    ]);
    Route::post('/users/toggle-status/{user}', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    Route::resource('/customers', CustomerController::class)->names([
        'index'   => 'customers.index',
        'create'  => 'customers.create',
        'store'   => 'customers.store',
        'show'    => 'customers.show',
        'edit'    => 'customers.edit',
        'update'  => 'customers.update',
        'destroy' => 'customers.destroy',
    ]);
    Route::post('/customers/toggle-status/{customer}', [CustomerController::class, 'toggleStatus'])->name('customers.toggle-status');
    Route::get('customers/{customerId}/rents', [CustomerRentController::class, 'index'])->name('customers.rents');

    Route::resource('/suppliers', SupplierController::class)->names([
        'index'   => 'suppliers.index',
        'create'  => 'suppliers.create',
        'store'   => 'suppliers.store',
        'show'    => 'suppliers.show',
        'edit'    => 'suppliers.edit',
        'update'  => 'suppliers.update',
        'destroy' => 'suppliers.destroy',
    ]);
    Route::post('/suppliers/toggle-status/{suppliers}', [SupplierController::class, 'toggleStatus'])->name('suppliers.toggle-status');

    Route::resource('/products', ProductController::class)->names([
        'index'   => 'products.index',
        'create'  => 'products.create',
        'store'   => 'products.store',
        'show'    => 'products.show',
        'edit'    => 'products.edit',
        'update'  => 'products.update',
        'destroy' => 'products.destroy',
    ]);

    // Product Variant Routes
    Route::prefix('products/{product}/variants')->name('products.variants.')->group(function () {
        Route::get('/manage', [ProductVariantController::class, 'manage'])->name('manage');
        Route::post('/', [ProductVariantController::class, 'store'])->name('store');
        Route::put('/{variant}', [ProductVariantController::class, 'update'])->name('update');
        Route::delete('/{variant}', [ProductVariantController::class, 'destroy'])->name('destroy');
        Route::post('/{variant}/stock', [ProductVariantController::class, 'updateStock'])->name('update-stock');
        Route::post('/{variant}/prices', [ProductVariantController::class, 'storePrice'])->name('store-price');
        Route::get('/{variant}/details', [ProductVariantController::class, 'getVariantDetails'])->name('details');
    });

    // Price Routes
    Route::delete('/prices/{price}', [ProductVariantController::class, 'destroyPrice'])->name('prices.destroy');

    // Rent Routes
    Route::prefix('rents')->name('rents.')->group(function () {
        Route::get('/', [RentController::class, 'index'])->name('index');
        Route::get('/create', [RentController::class, 'create'])->name('create');
        Route::post('/', [RentController::class, 'store'])->name('store');
        Route::get('/items-list', [RentController::class, 'itemList'])->name('items-list');
        Route::get('/available-variants', [RentController::class, 'getAvailableVariants'])->name('available-variants');

        Route::get('/overdue-report', [RentController::class, 'overdueReport'])->name('overdue-report');

        Route::get('/{rent}', [RentController::class, 'show'])->name('show');
        Route::post('/{rent}/mark-as-delivered', [RentController::class, 'markAsDelivered'])->name('mark-as-delivered');
        Route::get('/{rent}/edit', [RentController::class, 'edit'])->name('edit');
        Route::put('/{rent}', [RentController::class, 'update'])->name('update');
        Route::delete('/{rent}', [RentController::class, 'destroy'])->name('destroy');
        Route::get('/{rent}/print', [RentController::class, 'print'])->name('print');
        Route::post('/{rent}/send-mail', [RentController::class, 'sendInvoiceEmail'])->name('send-mail');

        // Nested Return Routes
        Route::prefix('{rent}/returns')->name('returns.')->group(function () {
            Route::get('/create', [RentReturnController::class, 'create'])->name('create');
            Route::post('/', [RentReturnController::class, 'store'])->name('store');
            Route::post('/{return}/send-mail', [RentReturnController::class, 'sendReturnEmail'])->name('send-mail');
            Route::get('/{return}', [RentReturnController::class, 'show'])->name('show');
            Route::get('/{return}/print', [RentReturnController::class, 'print'])->name('print');
        });

        // Nested Payment Routes
        Route::prefix('{rent}/payments')->name('payments.')->group(function () {
            Route::get('/create', [RentPaymentController::class, 'create'])->name('create');
            Route::post('/', [RentPaymentController::class, 'store'])->name('store');
            Route::get('/{payment}', [RentPaymentController::class, 'show'])->name('show');
        });
    });

    Route::prefix('rent-returns')->name('rent-returns.')->group(function () {
        Route::get('/', [RentReturnController::class, 'index'])->name('index');
        Route::get('/items-list', [RentReturnController::class, 'itemList'])->name('items-list');
    });

    Route::prefix('rent-payments')->name('rent-payments.')->group(function () {
        Route::get('/', [RentPaymentController::class, 'index'])->name('index');
    });

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // Quotation Routes
    Route::prefix('quotation')->group(function () {
        Route::get('/', [QuotationController::class, 'index'])->name('quotation.index');
        Route::post('/preview', [QuotationController::class, 'preview'])->name('quotation.preview');
        Route::post('/download', [QuotationController::class, 'download'])->name('quotation.download');
        Route::post('/email', [QuotationController::class, 'sendEmail'])->name('quotation.email');
    });

    Route::resource('/staffs', StaffController::class);
    Route::post('/staffs/toggle-status/{staff}', [StaffController::class, 'toggleStatus'])->name('staffs.toggle-status');

    Route::prefix('sales')->name('sales.')->group(function () {

        Route::get('/available-variants', [SaleController::class, 'getAvailableVariants'])->name('available-variants');

        Route::get('/items-list', [SaleController::class, 'itemList'])->name('items-list');
        Route::get('/{sale}/print', [SaleController::class, 'print'])->whereNumber('sale')->name('print');
        Route::post('/{sale}/complete', [SaleController::class, 'markAsCompleted'])->whereNumber('sale')->name('complete');
        Route::post('/{sale}/send-mail', [SaleController::class, 'sendInvoiceEmail'])->name('send-mail');
    });

    Route::resource('/sales', SaleController::class);

    // Quotation Routes
    Route::prefix('custom-invoice')->group(function () {
        Route::get('/', [CustomInvoiceController::class, 'index'])->name('custom-invoice.index');
        Route::post('/preview', [CustomInvoiceController::class, 'preview'])->name('custom-invoice.preview');
        // Route::post('/download', [CustomInvoiceController::class, 'download'])->name('custom-invoice.download');
        // Route::post('/email', [CustomInvoiceController::class, 'sendEmail'])->name('custom-invoice.email');
    });

    Route::resource('/expenses', ExpenseController::class)->names([
        'index'   => 'expenses.index',
        'create'  => 'expenses.create',
        'store'   => 'expenses.store',
        'show'    => 'expenses.show',
        'edit'    => 'expenses.edit',
        'update'  => 'expenses.update',
        'destroy' => 'expenses.destroy',
    ]);
    Route::post('/expenses/toggle-status/{expense}', [ExpenseController::class, 'toggleStatus'])->name('expenses.toggle-status');

    // Purchase Routes
    Route::prefix('purchases')->name('purchases.')->group(function () {
        Route::get('/', [PurchaseController::class, 'index'])->name('index');
        Route::get('/create', [PurchaseController::class, 'create'])->name('create');
        Route::post('/', [PurchaseController::class, 'store'])->name('store');
        Route::get('/available-variants', [PurchaseController::class, 'getAvailableVariants'])->name('available-variants');
        Route::get('/{id}', [PurchaseController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PurchaseController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PurchaseController::class, 'update'])->name('update');
        Route::delete('/{id}', [PurchaseController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/mark-as-delivered', [PurchaseController::class, 'markAsDelivered'])->name('mark-as-delivered');
        Route::post('/{id}/update-payment', [PurchaseController::class, 'updatePaymentStatus'])->name('update-payment');
    });
    Route::get('/products/{productId}/variants', [PurchaseController::class, 'getProductVariants'])->name('products.variants');

    // Settings Routes
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    //clear cache route
    Route::post('/system/clear', function () {

        Artisan::call('optimize:clear');

        return back()->with('success', 'System cache cleared!');
    })->name('system.clear');
});

# Frontend Routes
Route::group(['prefix' => 'home', 'middleware' => ['guest']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('frontend.home');
    Route::get('/services', [HomeController::class, 'services'])->name('frontend.services');
    Route::get('/contact', [HomeController::class, 'contact'])->name('frontend.contact');
    Route::get('/products', [ProductsProductController::class, 'index'])->name('frontend.products.index');
    Route::get('/products/{id}', [ProductsProductController::class, 'show'])->name('frontend.products.show');

    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerAuthenticationController::class, 'showLoginFrom'])->name('showLoginFrom');
        Route::post('/login', [CustomerAuthenticationController::class, 'login'])->name('login');
        Route::get('/register', [CustomerAuthenticationController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [CustomerAuthenticationController::class, 'register'])->name('register.submit');
    });
});

Route::prefix('home')->middleware('auth:customer')->group(function () {

    Route::post('/customers/logout', [CustomerAuthenticationController::class, 'logout'])->name('customers.logout');
    Route::get('/get-available-variants', [ProductsProductController::class, 'getAvailableVariants'])->name('frontend.products.get-available-variants');
    
    //customer quotation route
    Route::get('/quotations', [FrontendQuotationController::class, 'index'])->name('frontend.quotations.index');
    Route::get('/quotation/create', [FrontendQuotationController::class, 'create'])->name('frontend.quotations.create');
    #Route::get('/quotation/show', [FrontendQuotationController::class, 'show'])->name('frontend.quotation.show');
    Route::post('/quotation', [FrontendQuotationController::class, 'store'])->name('frontend.quotation.store');
    Route::get('/quotation/track', [FrontendQuotationController::class, 'track'])->name('frontend.quotation.track');

    Route::get('/customer/dashboard', function () {
        return view('pages.frontend.customers/.dashboard', ['title' => 'Customer Dashboard']);
     })->name('frontend.customer.dashboard');

     Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])->name('frontend.customer.dashboard');
     Route::get('/customer/rents/{rent}/invoice', [CustomerDashboardController::class, 'invoice'])->name('frontend.customer.invoice');
});
// Add this at the very end of web.php, after all other routes
Route::fallback(function () {
    return response()->view('pages.errors.error-404', ['title' => 'Error 404'], 404);
});
