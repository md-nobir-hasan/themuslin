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

use Modules\Attributes\Http\Controllers\AttributesController;
use Modules\Attributes\Http\Controllers\BrandController;
use Modules\Attributes\Http\Controllers\CategoryController;
use Modules\Attributes\Http\Controllers\ChildCategoryController;
use Modules\Attributes\Http\Controllers\ColorController;
use Modules\Attributes\Http\Controllers\DeliveryOptionController;
use Modules\Attributes\Http\Controllers\SizeController;
use Modules\Attributes\Http\Controllers\SubCategoryController;
use Modules\Attributes\Http\Controllers\TagController;
use Modules\Attributes\Http\Controllers\UnitController;

/* --------------------------------------------------------
 *                  BACKEND ATTRIBUTES MODULE ROUTES
 *-------------------------------------------------------- */
Route::prefix('admin-home')->middleware(['setlang:backend', 'adminglobalVariable','auth:admin'])->as("admin.")->group(callback: function () {
    /*-----------------------------------
            PRODUCT CATEGORY  ROUTES
    ------------------------------------*/
    Route::group(['prefix' => 'categories', 'as' => 'category.'], function () {
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/', 'index')->name('all')->permission('categories');
            Route::post('/new', 'store')->name('new')->permission('categories-new');
            Route::post('/update', 'update')->name('update')->permission('categories-update');
            Route::post('/delete/{item}', 'destroy')->name('delete')->permission('categories-delete');
            Route::post('/bulk-action', 'bulk_action')->name('bulk.action')->permission('categories-bulk-action');
        });
    });

    /*-----------------------------------
        PRODUCT SUB-CATEGORY  ROUTES
    ------------------------------------*/
    Route::group(['prefix' => 'sub-categories', 'as' => 'subcategory.'], function () {
        Route::controller(SubCategoryController::class)->group(function (){
            Route::get('/', 'index')->name('all')->permission('sub-categories');
            Route::post('new', 'store')->name('new')->permission('sub-categories-new');
            Route::post('update', 'update')->name('update')->permission('sub-categories-update');
            Route::post('delete/{item}', 'destroy')->name('delete')->permission('sub-categories-delete');
            Route::post('bulk-action', 'bulk_action')->name('bulk.action')->permission('sub-categories-bulk-action');
            Route::get('of-category/{id}', 'getSubcategoriesOfCategory')->name('of.category')->permission('sub-categories-of-category');
            Route::get('of-category/select/{id}', 'getSubcategoriesForSelect')->name('of.category')->permission('sub-categories-of-category');
        });
    });

    /*-----------------------------------
        PRODUCT SUB-CATEGORY  ROUTES
    ------------------------------------*/
    Route::group(['prefix' => 'child-categories', 'as' => 'child-category.'], function () {
        Route::controller(ChildCategoryController::class)->group(function () {
            Route::get('/', 'index')->name('all')->permission('child-categories');
            Route::post('new', 'store')->name('new')->permission('child-categories-new');
            Route::post('update', 'update')->name('update')->permission('child-categories-update');
            Route::post('delete/{item}', 'destroy')->name('delete')->permission('child-categories-delete');
            Route::post('bulk-action', 'bulk_action')->name('bulk.action')->permission('child-categories-bulk-action');
            Route::get('of-category/{id}', 'getSubcategoriesOfCategory')->name('of.category')->permission('child-categories-of-category');
        });
    });
    /*-----------------------------------
        TAG ROUTES
    ------------------------------------*/
    Route::group(['prefix' => 'tags', 'as' => 'tag.'], function () {
        Route::controller(TagController::class)->group(function (){
            Route::get('/', 'index')->name('all')->permission('tags');
            Route::post('new', 'store')->name('new')->permission('tags-new');
            Route::post('update', 'update')->name('update')->permission('tags-update');
            Route::post('delete/{item}', 'destroy')->name('delete')->permission('tags-delete');
            Route::post('bulk-action', 'bulk_action')->name('bulk.action')->permission('tags-bulk-action');
            Route::get('check', 'check')->name('check')->permission('tags-check');
            Route::get('get-tags', 'getTagsAjax')->name('get.ajax')->permission('tags-get-tags');
        });
    });

    /*-----------------------------------
        PRODUCTS UNIT ROUTES
    ------------------------------------*/
    Route::group(['prefix' => 'units', 'as' => 'units.'], function () {
        Route::controller(UnitController::class)->group(function (){
            Route::get('/', 'index')->name('all')->permission('units');
            Route::post('new', 'store')->name('store')->permission('units-new');
            Route::post('update', 'update')->name('update')->permission('units-update');
            Route::post('delete/{item}', 'destroy')->name('delete')->permission('units-delete');
            Route::post('bulk-action', 'bulk_action')->name('bulk.action')->permission('units-bulk-action');
        });
    });

    /*-----------------------------------
        PRODUCTS Delivery Option ROUTES
    ------------------------------------*/
    Route::group(['prefix' => 'delivery-manage', 'as' => 'delivery.option.'], function () {
        Route::controller(DeliveryOptionController::class)->group(function (){
            Route::get('/', 'index')->name('all')->permission('delivery-manage');
            Route::post('new', 'store')->name('store')->permission('delivery-manage-new');
            Route::post('update', 'update')->name('update')->permission('delivery-manage-update');
            Route::post('delete/{item}', 'destroy')->name('delete')->permission('delivery-manage-delete');
            Route::post('bulk-action', 'bulk_action')->name('bulk.action')->permission('delivery-manage-bulk-action');
        });
    });

    /*-----------------------------------
        Brands ROUTES
    ------------------------------------*/
    Route::group(['prefix' => 'brand-manage', 'as' => 'brand.manage.'], function () {
        Route::controller(BrandController::class)->group(function (){
            Route::get('/', 'index')->name('all')->permission('brand-manage');
            Route::post('new', 'store')->name('store')->permission('brand-manage-new');
            Route::post('update', 'update')->name('update')->permission('brand-manage-update');
            Route::post('delete/{item}', 'destroy')->name('delete')->permission('brand-manage-delete');
            Route::post('bulk-action', 'bulk_action')->name('bulk.action')->permission('brand-manage-bulk-action');
        });
    });

    /*-----------------------------------
    PRODUCTS COLOR ROUTES
------------------------------------*/
    Route::group(['prefix' => 'colors', 'as' => 'product.colors.'], function () {
        Route::controller(ColorController::class)->group(function (){
            Route::get('/', 'index')->name('all')->permission('colors');
            Route::post('new', 'store')->name('new')->permission('colors-new');
            Route::post('update', 'update')->name('update')->permission('colors-update');
            Route::post('delete/{item}', 'destroy')->name('delete')->permission('colors-delete');
            Route::post('bulk-action', 'bulk_action')->name('bulk.action')->permission('colors-bulk-action');
        });
    });

    /*-----------------------------------
        PRODUCTS COLOR ROUTES
    ------------------------------------*/
    Route::group(['prefix' => 'sizes', 'as' => 'product.sizes.'], function () {
        Route::controller(SizeController::class)->group(function (){
            Route::get('/', 'index')->name('all')->permission('sizes');
            Route::post('new', 'store')->name('new')->permission('sizes-new');
            Route::post('update', 'update')->name('update')->permission('sizes-update');
            Route::post('delete/{item}', 'destroy')->name('delete')->permission('sizes-delete');
            Route::post('bulk-action', 'bulk_action')->name('bulk.action')->permission('sizes-bulk-action');
        });
    });

    /*-----------------------------------------------------------------------------------------
          Product inventory variant route
    -------------------------------------------------------------------------------------------*/
    Route::group(['prefix' => 'attributes', 'as' => 'products.attributes.'], function () {
        Route::controller(AttributesController::class)->group(function (){
            Route::get('/', 'index')->name('all')->permission('attributes');
            Route::get('/new', 'create')->name('store')->permission('attributes-new');
            Route::post('/new', 'store')->permission('attributes-new');
            Route::get('/edit/{item}', 'edit')->name('edit')->permission('attributes-edit');
            Route::post('/update', 'update')->name('update')->permission('attributes-update');
            Route::post('/delete/{item}', 'destroy')->name('delete')->permission('attributes-delete');
            Route::post('/bulk-action', 'bulk_action')->name('bulk.action')->permission('attributes-bulk-action');
            Route::post('/details', 'get_details')->name('details')->permission('attributes-details');
            Route::post('/by-lang', 'get_all_variant_by_lang')->name('admin.products.variant.by.lang')->permission('attributes-by-lang');
        });
    });
});