<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Backend\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Backend\users\UserController;
use App\Http\Controllers\Backend\customers\CustomerController;
use App\Http\Controllers\Backend\products\ProductController;
use App\Http\Controllers\Backend\products\ProductVariantController;
use App\Http\Controllers\Backend\rents\{RentController, RentReturnController, RentPaymentController};
use App\Http\Controllers\Backend\quotation\QuotationController;
use App\Http\Controllers\Backend\staffs\StaffController;
use App\Models\Backend\Rent;

// dashboard pages
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// calender pages
Route::get('/calendar', function () {
    return view('pages.calender', ['title' => 'Calendar']);
})->name('calendar');

// profile pages
Route::get('/profile', function () {
    return view('pages.profile', ['title' => 'Profile']);
})->name('profile');

// form pages
Route::get('/form-elements', function () {
    return view('pages.form.form-elements', ['title' => 'Form Elements']);
})->name('form-elements');

// tables pages
Route::get('/basic-tables', function () {
    return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
})->name('basic-tables');

// pages

Route::get('/blank', function () {
    return view('pages.blank', ['title' => 'Blank']);
})->name('blank');

// error pages
Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');

// chart pages
Route::get('/line-chart', function () {
    return view('pages.chart.line-chart', ['title' => 'Line Chart']);
})->name('line-chart');

Route::get('/bar-chart', function () {
    return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
})->name('bar-chart');


// authentication pages
Route::get('/signin', function () {
    return view('pages.auth.signin', ['title' => 'Sign In']);
})->name('signin');



Route::get('/signup', function () {
    return view('pages.auth.signup', ['title' => 'Sign Up']);
})->name('signup');

// ui elements pages
Route::get('/alerts', function () {
    return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
})->name('alerts');

Route::get('/avatars', function () {
    return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
})->name('avatars');

Route::get('/badge', function () {
    return view('pages.ui-elements.badges', ['title' => 'Badges']);
})->name('badges');

Route::get('/buttons', function () {
    return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
})->name('buttons');

Route::get('/image', function () {
    return view('pages.ui-elements.images', ['title' => 'Images']);
})->name('images');

Route::get('/videos', function () {
    return view('pages.ui-elements.videos', ['title' => 'Videos']);
})->name('videos');



# Backend Auth Routes
Route::group(['prefix' => 'admin',  'middleware' => ['guest']], function () {

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::group(['prefix' => 'admin',  'middleware' => ['auth']], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

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
        Route::get('/{rent}', [RentController::class, 'show'])->name('show');
        Route::get('/{rent}/edit', [RentController::class, 'edit'])->name('edit');
        Route::put('/{rent}', [RentController::class, 'update'])->name('update');
        Route::delete('/{rent}', [RentController::class, 'destroy'])->name('destroy');
        Route::get('/{rent}/print', [RentController::class, 'print'])->name('print');

        // Nested Return Routes
        Route::prefix('{rent}/returns')->name('returns.')->group(function () {
            Route::get('/create', [RentReturnController::class, 'create'])->name('create');
            Route::post('/', [RentReturnController::class, 'store'])->name('store');
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
        Route::get('/generate-number', [QuotationController::class, 'generateNumber'])->name('quotation.generate-number');
    });

    Route::resource('/staffs', StaffController::class);
});

Route::get('/test-email/{rent}', function($id) {
    $rent = \App\Models\Backend\Rent::find($id);
    \Mail::to('labroom108@gmail.com')->send(new \App\Mail\RentInvoiceMail($rent));
    return 'Email sent!';
})->middleware('auth');