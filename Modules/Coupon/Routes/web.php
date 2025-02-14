<?php

Route::middleware([
    'web',
    'auth:admin'
])->prefix('admin-home')->name('admin.')->group(function () {
    /*-----------------------------------
                COUPON ROUTES
    ------------------------------------*/
    Route::group(['prefix' => 'coupons', 'as' => 'product.coupon.'], function () {
        Route::get('/', 'AdminCouponController@index')->name('all');
        Route::post('new', 'AdminCouponController@store')->name('new');
        Route::post('update', 'AdminCouponController@update')->name('update');
        Route::post('delete/{item}', 'AdminCouponController@destroy')->name('delete');
        Route::post('bulk-action', 'AdminCouponController@bulk_action')->name('bulk.action');
        Route::get('check', 'AdminCouponController@check')->name('check');
        Route::get('get-products', 'AdminCouponController@allProductsAjax')->name('products');
    });
});

