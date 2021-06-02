<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class,'welcome']);

Auth::routes();

Route::get('/verify/{token}/{id}', [App\Http\Controllers\Auth\RegisterController::class,'verify']);
Route::post('/register/penjual', [App\Http\Controllers\Auth\RegisterController::class,'registerPenjual']);

Route::prefix('account')->group(function () {
  Route::middleware(['auth'])->group(function () {
    Route::put('/set-review/{id}', [App\Http\Controllers\AccountController::class,'setReview'])->where('id', '[0-9]+');
    Route::put('/set-resi/{order_id}', [App\Http\Controllers\AccountController::class,'setResi'])->where('order_id', '[0-9]+');
    Route::get('/drawn-my-balance', [App\Http\Controllers\AccountController::class,'drawnMyBalance']);
    Route::get('/my-withdrawals', [App\Http\Controllers\AccountController::class, 'myWithdrawals']);
    Route::get('/my-balance', [App\Http\Controllers\AccountController::class,'myBalance']);
    Route::get('/my-review', [App\Http\Controllers\AccountController::class,'myReview']);
    Route::get('/waiting-review', [App\Http\Controllers\AccountController::class,'waitingReview']);
    Route::get('/get-order-user', [App\Http\Controllers\AccountController::class,'getOrderUser']);
    Route::get('/get-order-penjual',[App\Http\Controllers\AccountController::class,'getOrderPenjual']);
    Route::put('/update-profile', [App\Http\Controllers\AccountController::class,'updateProfile']);
    Route::post('/update-avatar', [App\Http\Controllers\AccountController::class,'updateAvatar']); 
    Route::put('/update-password', [App\Http\Controllers\AccountController::class,'updatePassword']);
    Route::put('/update-rekening', [App\Http\Controllers\AccountController::class,'updateRekening']);
    Route::get('/profile', [App\Http\Controllers\AccountController::class,'profile'])->name('account.profile');
    Route::get('/password', [App\Http\Controllers\AccountController::class,'password'])->name('account.password');
    Route::get('/orders', [App\Http\Controllers\AccountController::class,'orders'])->name('account.orders');
    Route::get('/orders-incoming', [App\Http\Controllers\AccountController::class,'ordersIncoming'])->name('account.orders_incoming');
    Route::get('/review', [App\Http\Controllers\AccountController::class,'review'])->name('account.review');
  });
    Route::get('/get-city-district', [App\Http\Controllers\AccountController::class,'getCityDistrict']);
    Route::get('/get-city-district/{id}', [App\Http\Controllers\AccountController::class,'getCityDistrictById'])->where('id', '[0-9]+');
});

Route::prefix('products')->group(function () {
  Route::middleware(['auth'])->group(function () {
    Route::get('/add-product', [App\Http\Controllers\ProductController::class,'add_product'])->name('product.add_product');
    Route::post('/create-product', [App\Http\Controllers\ProductController::class,'createProduct']);
    Route::get('/my-product', [App\Http\Controllers\ProductController::class,'my_product'])->name('product.my_product');
    Route::get('/get-my-product', [App\Http\Controllers\ProductController::class,'getMyProduct']);
    Route::get('/change-my-product/{id}', [App\Http\Controllers\ProductController::class,'changeMyProduct'])->where('id','[0-9]+');
    Route::post('/update-product', [App\Http\Controllers\ProductController::class,'updateProduct']);
    Route::delete('/delete-product/{id}', [App\Http\Controllers\ProductController::class,'deleteProduct'])->where('id','[0-9]+');
  });

    Route::get('/detail-product/{slug}/{user_id}', [App\Http\Controllers\ProductController::class,'detail_product'])->where('user_id','[0-9]+');
    Route::get('/review-product/{product_id}', [App\Http\Controllers\ProductController::class,'getReviewProduct'])->where('user_id','[0-9]+');
    Route::get('/all-products', [App\Http\Controllers\ProductController::class,'allProducts']);
    Route::post('/get-cost', [App\Http\Controllers\ProductController::class,'getCost']);
});

Route::prefix('carts')->group(function () {
  Route::get('/my-cart', [App\Http\Controllers\CartController::class,'my_cart']);
  Route::post('/store-cart', [App\Http\Controllers\CartController::class,'storeCart']);
  Route::put('/increase-cart/{id}', [App\Http\Controllers\CartController::class,'increaseCart']);
  Route::put('/decrease-cart/{id}', [App\Http\Controllers\CartController::class,'decreaseCart']);
  Route::put('/input-cart/{id}', [App\Http\Controllers\CartController::class,'inputCart']);
  Route::delete('/delete-cart/{id}', [App\Http\Controllers\CartController::class,'deleteCart']);
  Route::delete('/delete-all-cart', [App\Http\Controllers\CartController::class,'deleteAllCart']);
  Route::get('/get-my-cart', function() { return Cart::instance('cart')->content();});
  Route::get('/get-total-cart', function() { return Cart::instance('cart')->total();});
  Route::middleware(['auth'])->group(function () {
    Route::post('/pay-now', [App\Http\Controllers\CartController::class,'storeCart']);
  });
});

Route::prefix('payments')->group(function () {
  Route::middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\PaymentController::class,'index']);
    Route::post('/get-snap-token', [App\Http\Controllers\PaymentController::class,'getSnapToken']);
    Route::post('/save-order', [App\Http\Controllers\PaymentController::class,'saveOrder']);
    Route::delete('/cancel-order/{order_id}', [App\Http\Controllers\PaymentController::class,'cancelOrder']);
  });
  /* MIDTRANS */
  Route::post('/notification-handler', [App\Http\Controllers\PaymentController::class,'notificationHandler']);
  Route::post('/finish', function(){
      return redirect()->route('welcome');
  });

});

Route::prefix('admin')->group(function () {
  Route::middleware(['admin'])->group(function () {
    Route::get('/get-transaction-penjual', [App\Http\Controllers\AdminController::class,'getTransactionPenjual']);
    Route::put('/set-withdrawals-success/{id}', [App\Http\Controllers\AdminController::class,'setWithdrawalsSuccess'])->where('id','[0-9]+');
    Route::get('/transaction', [App\Http\Controllers\AdminController::class,'transaction']);
  });
});

Route::middleware(['auth'])->group(function () {
  Route::get('/user', function () {
      return Auth::user();
  });
});
