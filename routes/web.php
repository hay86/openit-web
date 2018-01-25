<?php

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

/*
 * Guest
 */
Route::get('/', 'HomeController@index')->name('index');
Route::get('tag/{id}', 'BlogController@tag')->name('blog.tag');
Route::get('question', 'QuestionController@index')->name('question.index');
Route::get('about', 'AboutController@index')->name('about.index');
Route::resource('blog', 'BlogController', ['only' => ['index', 'show']]);

/*
 * Wechat media platform
 */
Route::any('wechat/fwh', 'WechatController@serve_fwh')->name('wechat.fwh.serve');
Route::any('wechat/dyh', 'WechatController@serve_dyh')->name('wechat.dyh.serve');
Route::any('wechat/txh', 'WechatController@serve_txh')->name('wechat.txh.serve');
Route::post('wechat/notify', 'PaymentController@notifyWechat')->name('wechat.notify');

/*
 * Wechat user login
 */
Route::get('/login/wechat', 'Auth\LoginWechatController@showLoginQRCode')->
    middleware('guest')->name('login.wechat');
Route::post('/login/wechat/check', 'Auth\LoginWechatController@authCheck')->
    middleware('guest')->name('login.wechat.check');
Route::get('/login/wechat/{id}', 'Auth\LoginWechatController@openQRCode')->
    middleware(['wechat', 'auth.wechat', 'auth'])->name('login.wechat.auth');

/*
 * Login user
 */
Auth::routes();
Route::group(['middleware' => ['wechat', 'auth.wechat', 'auth']], function() {
    Route::get('account', 'UserController@edit')->name('account.edit');
    Route::get('referer', 'UserController@referer')->name('account.referer');
    Route::get('coupons', 'UserController@coupons')->name('account.coupons');
    Route::get('order/express/{id}', 'OrderController@showExpress')->name('order.express');
    Route::get('proxy/ip', 'ProxyController@ip')->name('proxy.ip');
    Route::put('account', 'UserController@update')->name('account.update');
    Route::post('address', 'AddressController@store')->name('address.store');
    Route::post('payment/{order}/prepare', 'PaymentController@prepareWechat')->name('payment.prepare');
    Route::post('payment/{order}/check', 'PaymentController@checkWechat')->name('payment.check');
    Route::post('payment/{order}', 'PaymentController@showWechat')->name('payment.show');
    Route::resource('order', 'OrderController', ['except' => ['destroy']]);
    Route::resource('review', 'ReviewController', ['only' => ['show', 'update']]);
});

/*
 * Admin wechat config, i.e. menus
 */
Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'admin', 'as' => 'admin.'], function() {
    Route::get('wechat/fwh', 'WechatController@edit_fwh')->name('wechat.fwh.edit');
    Route::get('wechat/dyh', 'WechatController@edit_dyh')->name('wechat.dyh.edit');
    Route::get('wechat/txh', 'WechatController@edit_txh')->name('wechat.txh.edit');
    Route::put('wechat/fwh', 'WechatController@update_fwh')->name('wechat.fwh.update');
    Route::put('wechat/dyh', 'WechatController@update_dyh')->name('wechat.dyh.update');
    Route::put('wechat/txh', 'WechatController@update_txh')->name('wechat.txh.update');
});

/*
 * Admin openit system, i.e. orders, products, articles and tags
 */
Route::group(['middleware' => ['auth', 'admin'], 'namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'], function() {
    Route::get('/', 'HomeController@index')->name('index');
    Route::get('statuses', 'StatusController@index')->name('statuses.index');
    Route::get('orders/{order}/print', 'OrderController@print_sheet')->name('orders.print');
    Route::get('orders/{order}/coupon', 'OrderController@print_coupon')->name('orders.coupon');
    Route::put('boxes/{box}/{product}', 'BoxController@update_product')->name('boxes.update.product');
    Route::put('orders/{order}/notes', 'OrderController@update_notes')->name('orders.update.notes');
    Route::put('orders/{order}/notification', 'OrderController@notification')->name('orders.notification');
    Route::post('images/store', 'ImageController@store')->name('images.store');
    Route::post('articles/{article}/restore', 'ArticleController@restore')->name('articles.restore');
    Route::post('products/{product}/restore', 'ProductController@restore')->name('products.restore');
    Route::resource('orders', 'OrderController', ['only' => ['index', 'update', 'show']]);
    Route::resource('coupons', 'CouponController', ['except' => ['edit', 'update']]);
    Route::resource('tags', 'TagController', ['except' => ['create', 'edit']]);
    Route::resource('boxes', 'BoxController', ['except' => ['destroy']]);
    Route::resource('articles', 'ArticleController');
    Route::resource('products', 'ProductController');
});

