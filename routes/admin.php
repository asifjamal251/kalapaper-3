<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AppSettingController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\BreadController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CommonController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FirmController;
use App\Http\Controllers\Admin\InwardController;
use App\Http\Controllers\Admin\JobCardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PartyController;
use App\Http\Controllers\Admin\PurchaseOrderController;
use App\Http\Controllers\Admin\QualityController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\VendorController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/run-migration', function () {
    Artisan::call('migrate', ['--force' => true]);
    return 'Migration completed!';
});
 
Route::get('storage/link', function () {
    try {
        Artisan::call('storage:link');
        return redirect()->back()->with('success', '✔ Storage linked Successfully!');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', '❌ Error: ' . $e->getMessage());
    }
})->name('storage.link');

Route::get('clear/all-cache', function () {
    try {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('optimize:clear');
        //Artisan::call('optimize');
        return redirect()->back()->with('success', '✔ All cache cleared successfully!');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', '❌ Error: ' . $e->getMessage());
    }
})->name('clear.cache');

Route::get('/', function() {
    return redirect()->route('admin.login.form');
});

Route::middleware('admin.guest')->group(function() {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login.form');
    Route::post('login', [LoginController::class, 'login'])->name('login.post');

    Route::get('password/reset', [LoginController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [LoginController::class, 'sendResetLinkEmail']);

    Route::get('password/reset/{token}', [LoginController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [LoginController::class, 'reset'])->name('password.request.sore');

    Route::get('new-password/{id}', [LoginController::class, 'newPasswordForm'])->name('password.newPassword');
    Route::post('password/set-password/{id}', [LoginController::class, 'sepPassword'])->name('password.setPassword');

    Route::get('2fa/verify', [LoginController::class, 'show2FAVerificationForm'])->name('2fa.verify');
    Route::post('2fa/verify', [LoginController::class, 'verify2FA'])->name('2fa.verify.post');

});

Route::middleware(['admin.auth', '2fa', 'check.admin.ip', 'login.time'])->group(function() {

    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index')->middleware('can:browse_dashboard');
    Route::post('dashboard', [DashboardController::class, 'filter'])->name('dashboard.filter')->middleware('can:browse_dashboard');

    //Common
     Route::controller(CommonController::class)->name('common.')->prefix('common')->group(function(){
       Route::get('ajax/pincode/{pincode}', 'apiPincode')->name('api.pincode');
       Route::get('client/list', 'clientList')->name('client.list');
       Route::get('purchase-order/sold-to/list', 'poSoldTolist')->name('po.sold-to.list');
       Route::get('purchase-order/item/list', 'poItemlist')->name('po.item.list');
       Route::get('purchase-order/item/single/{id}', 'poItemSingle')->name('po.item.single');

    });

     //Excell Download
     Route::controller(ExcelController::class)->prefix('download-excell')->name('excell-download.')->group(function(){
        
    });

    //PDF Download
    Route::controller(PDFController::class)->prefix('download-pdf')->name('pdf.')->group(function(){
       
    });

    Route::controller(BreadController::class)->group(function(){
        Route::get('bread', 'index')->name('bread.index')->middleware('can:browse_bread');
        Route::get('bread/create', 'create')->name('bread.create')->middleware('can:add_bread');
        Route::get('bread/{slug}/edit', 'edit')->name('bread.edit')->middleware('can:edit_bread');
        Route::put('bread/{bread}/update', 'update')->name('bread.update')->middleware('can:edit_bread');
        Route::delete('bread/{slug}/delete', 'destroy')->name('bread.destroy')->middleware('can:delete_bread');
        Route::post('bread', 'store')->name('bread.store')->middleware('can:add_bread');
    });

    Route::controller(RoleController::class)->group(function(){
        Route::get('role', 'index')->name('role.index')->middleware('can:browse_role');
        Route::get('role/create', 'create')->name('role.create')->middleware('can:add_role');
        Route::get('role/{role}/edit', 'edit')->name('role.edit')->middleware('can:edit_role');
        Route::post('role', 'store')->name('role.store')->middleware('can:add_role');
        Route::put('role/{role}', 'update')->name('role.update')->middleware('can:edit_role');
        Route::delete('role/{slug}/delete', 'destroy')->name('role.destroy')->middleware('can:delete_role');
    });

    Route::controller(MenuController::class)->group(function(){
        Route::get('menu', 'index')->name('menu.index')->middleware('can:browse_menu');
        Route::get('menu/create', 'create')->name('menu.create')->middleware('can:add_menu');
        Route::get('menu/{menu}/edit', 'edit')->name('menu.edit')->middleware('can:edit_menu');
        Route::post('menu', 'store')->name('menu.store')->middleware('can:add_menu');
        Route::put('menu/{menu}', 'update')->name('menu.update')->middleware('can:edit_menu');
        Route::delete('menu/{menu}', 'destroy')->name('menu.destroy')->middleware('can:delete_menu');
    });

     //Admin
    Route::controller(AdminController::class)->group(function(){
        Route::match(['get','patch'],'admin', 'index')->name('admin.index')->middleware('can:browse_admin');
        Route::get('admin/create', 'create')->name('admin.create')->middleware('can:add_admin');
        Route::get('admin/view/{admin}', 'show')->name('admin.show')->middleware('can:read_admin');
        Route::get('admin/{admin}/edit', 'edit')->name('admin.edit')->middleware('can:edit_admin');
        Route::post('admin', 'store')->name('admin.store')->middleware('can:add_admin');
        Route::put('admin/{admin}', 'update')->name('admin.update')->middleware('can:edit_admin');
        Route::delete('admin/{admin}/delete', 'destroy')->name('admin.destroy')->middleware('can:delete_admin');

        Route::get('profile', 'profile')->name('profile');
        Route::put('profile/update', 'profileUpdate')->name('profile.update');
        Route::put('profile/photo/update/{admin}', 'profilePhotoUpdate')->name('profile.photo.update');
        Route::put('profile/cover/photo/update/{admin}', 'profileCoverPhotoUpdate')->name('profile.cover.photo.update');

        Route::get('change-password/{admin}', 'changePassword')->name('change-password');
        Route::put('update-password/{admin}', 'updatePassword')->name('update-password');

        Route::get('admin/2fa/setup/{id}', 'setup2FA')->name('admin.2fa.setup');
        Route::post('admin/2fa/setup/{id}', 'enable2FA')->name('admin.2fa.enable');

    });

     //Site Setting
    Route::controller(AppSettingController::class)->group(function(){
        Route::get('get-all-country', 'getAllCountry')->name('app-setting.country')->middleware('can:browse_app_setting');
        Route::get('app-setting', 'index')->name('app-setting.index')->middleware('can:browse_app_setting');

        Route::post('app/basic-info', 'basicInfo')->name('app-setting.basic-info')->middleware('can:logo_app_setting');
        Route::post('app/contact-details', 'contactDetails')->name('app-setting.contact-details')->middleware('can:logo_app_setting');

        Route::post('app/logo', 'logo')->name('app-setting.logo')->middleware('can:logo_app_setting');
        Route::get('access-control', 'index')->name('access-control.index')->middleware('can:browse_access_control');
    });

    //media
    Route::controller(MediaController::class)->group(function(){
        Route::match(['get','patch'],'media', 'index')->name('media.index')->middleware('can:browse_media');
        Route::get('media/create', 'create')->name('media.create')->middleware('can:add_media');
        Route::get('media/{media}', 'show')->name('media.show')->middleware('can:read_media');
        Route::get('media/{media}/edit', 'edit')->name('media.edit')->middleware('can:edit_media');

        Route::post('media', 'store')->name('media.store')->middleware(['can:add_media', 'optimizeImages']);
        Route::put('media/update/{media}', 'update')->name('media.update')->middleware(['can:edit_media', 'optimizeImages']);

        Route::delete('media/{media}/delete', 'destroy')->name('media.destroy')->middleware('can:delete_media');
        Route::get('media/get/multiple', 'getAllMediaMultiple')->name('media.get.multiple');
        Route::get('media/get/single', 'getAllMediaSingle')->name('media.get.single');
    });

    //Firm
    Route::controller(FirmController::class)->group(function(){
        Route::match(['get','patch'],'firm', 'index')->name('firm.index')->middleware('can:browse_firm');
        Route::get('firm/create', 'create')->name('firm.create')->middleware('can:add_firm');
        Route::get('firm/{id}', 'show')->name('firm.show')->middleware('can:read_firm');
        Route::get('firm/{id}/edit', 'edit')->name('firm.edit')->middleware('can:edit_firm');
        Route::post('firm/store', 'store')->name('firm.store')->middleware('can:add_firm');
        Route::put('firm/{id}', 'update')->name('firm.update')->middleware('can:edit_firm');
        Route::delete('firm/{id}/delete', 'destroy')->name('firm.destroy')->middleware('can:delete_firm');
    });

    //Client
    Route::controller(ClientController::class)->group(function(){
        Route::match(['get','patch'],'client', 'index')->name('client.index')->middleware('can:browse_client');
        Route::get('client/create', 'create')->name('client.create')->middleware('can:add_client');
        Route::get('client/{id}', 'show')->name('client.show')->middleware('can:read_client');
        Route::get('client/{id}/edit', 'edit')->name('client.edit')->middleware('can:edit_client');
        Route::post('client/store', 'store')->name('client.store')->middleware('can:add_client');
        Route::put('client/{id}', 'update')->name('client.update')->middleware('can:edit_client');
        Route::put('client/change/status/{id}', 'changeStatus')->name('client.change.status')->middleware('can:delete_client');

        Route::get('client/import/create', 'importCreate')->name('client.import.create')->middleware('can:add_client');
        Route::post('client/import/store', 'importStore')->name('client.import.store')->middleware('can:add_client');
    });


    //Party
    Route::controller(PartyController::class)->group(function(){
        Route::match(['get','patch'],'parties', 'index')->name('parties.index')->middleware('can:browse_parties');
        Route::get('parties/create', 'create')->name('parties.create')->middleware('can:add_parties');
        Route::get('parties/{id}', 'show')->name('parties.show')->middleware('can:read_parties');
        Route::get('parties/{id}/edit', 'edit')->name('parties.edit')->middleware('can:edit_parties');
        Route::post('parties/store', 'store')->name('parties.store')->middleware('can:add_parties');
        Route::put('parties/{id}', 'update')->name('parties.update')->middleware('can:edit_parties');
        Route::put('parties/change/status/{id}', 'changeStatus')->name('parties.change.status')->middleware('can:delete_parties');

        Route::get('parties/import/create', 'importCreate')->name('parties.import.create')->middleware('can:add_parties');
        Route::post('parties/import/store', 'importStore')->name('parties.import.store')->middleware('can:add_parties');
    });

    //Vendor
    Route::controller(VendorController::class)->group(function(){
        Route::match(['get','patch'],'vendor', 'index')->name('vendor.index')->middleware('can:browse_vendor');
        Route::get('vendor/create', 'create')->name('vendor.create')->middleware('can:add_vendor');
        Route::get('vendor/{id}', 'show')->name('vendor.show')->middleware('can:read_vendor');
        Route::get('vendor/{id}/edit', 'edit')->name('vendor.edit')->middleware('can:edit_vendor');
        Route::post('vendor/store', 'store')->name('vendor.store')->middleware('can:add_vendor');
        Route::put('vendor/{id}', 'update')->name('vendor.update')->middleware('can:edit_vendor');
        Route::delete('vendor/{id}/delete', 'destroy')->name('vendor.destroy')->middleware('can:delete_vendor');

        Route::get('vendor/import/create', 'importCreate')->name('vendor.import.create')->middleware('can:add_vendor');
        Route::post('vendor/import/store', 'importStore')->name('vendor.import.store')->middleware('can:add_vendor');
    });

    //Status
    Route::controller(StatusController::class)->group(function(){
        Route::match(['get','patch'],'status', 'index')->name('status.index')->middleware('can:browse_status');
        Route::get('status/create', 'create')->name('status.create')->middleware('can:add_status');
        Route::get('status/{id}', 'show')->name('status.show')->middleware('can:read_status');
        Route::get('status/{id}/edit', 'edit')->name('status.edit')->middleware('can:edit_status');
        Route::post('status/store', 'store')->name('status.store')->middleware('can:add_status');
        Route::put('status/{id}', 'update')->name('status.update')->middleware('can:edit_status');
        Route::delete('status/{id}/delete', 'destroy')->name('status.destroy')->middleware('can:delete_status');
    });

    //Quality
    Route::controller(QualityController::class)->group(function(){
        Route::get('quality/create', 'create')->name('quality.create')->middleware('can:add_quality');
        Route::get('quality/{id}/edit', 'edit')->name('quality.edit')->middleware('can:edit_quality');
        Route::post('quality/store', 'store')->name('quality.store')->middleware('can:add_quality');
        Route::put('quality/update/{id}', 'update')->name('quality.update')->middleware('can:edit_quality');
        Route::delete('quality/{id}/delete', 'destroy')->name('quality.destroy')->middleware('can:delete_quality');
    });

    //Inward
    Route::controller(InwardController::class)->group(function(){
        Route::match(['get','patch'],'inward', 'index')->name('inward.index')->middleware('can:browse_inward');
        Route::get('inward/create', 'create')->name('inward.create')->middleware('can:add_inward');
        Route::get('inward/show/{id}', 'show')->name('inward.show')->middleware('can:read_inward');
        Route::get('inward/preview', 'preview')->name('inward.preview')->middleware('can:add_inward');
        Route::post('inward/confirm', 'confirm')->name('inward.confirm')->middleware('can:add_inward');
        Route::get('inward/{id}/edit', 'edit')->name('inward.edit')->middleware('can:edit_inward');
        Route::post('inward/store', 'store')->name('inward.store')->middleware('can:add_inward');
        Route::put('inward/update/{id}', 'update')->name('inward.update')->middleware('can:edit_inward');
        Route::delete('inward/{id}/delete', 'destroy')->name('inward.destroy')->middleware('can:delete_inward');
    });

    //Stock
    Route::controller(StockController::class)->group(function(){
        Route::match(['get','patch'],'stock', 'index')->name('stock.index')->middleware('can:browse_stock');
        Route::get('stock/create', 'create')->name('stock.create')->middleware('can:add_stock');
        Route::get('stock/show/{id}', 'show')->name('stock.show')->middleware('can:read_stock');
        Route::get('stock/{id}/edit', 'edit')->name('stock.edit')->middleware('can:edit_stock');
        Route::put('stock/split/cancel/{id}', 'splitCancel')->name('stock.split.cancel')->middleware('can:edit_stock');
        Route::post('stock/store', 'store')->name('stock.store')->middleware('can:add_stock');
        Route::put('stock/{id}', 'update')->name('stock.update')->middleware('can:edit_stock');
        Route::delete('stock/{id}/delete', 'destroy')->name('stock.destroy')->middleware('can:delete_stock');

        Route::get('stock/create/book', 'createBook')->name('stock.create.book')->middleware('can:add_stock');
    });


    //PurchaseOrder
    Route::controller(PurchaseOrderController::class)->group(function(){
        Route::match(['get','patch'],'purchase-order', 'index')->name('purchase-order.index')->middleware('can:browse_purchase_order');
        Route::get('purchase-order/create', 'create')->name('purchase-order.create')->middleware('can:add_purchase_order');
        Route::get('purchase-order/show/{id}', 'show')->name('purchase-order.show')->middleware('can:read_purchase_order');
        Route::get('purchase-order/{id}/edit', 'edit')->name('purchase-order.edit')->middleware('can:edit_purchase_order');
        Route::post('purchase-order/store', 'store')->name('purchase-order.store')->middleware('can:add_purchase_order');
        Route::put('purchase-order/{id}', 'update')->name('purchase-order.update')->middleware('can:edit_purchase_order');
        Route::delete('purchase-order/{id}/delete', 'destroy')->name('purchase-order.destroy')->middleware('can:delete_purchase_order');

        Route::get('purchase-order/download/excel/{id}', 'downloadExcel')->name('purchase-order.download.excel');
    });


    //JobCard
    Route::controller(JobCardController::class)->group(function(){
        Route::match(['get','patch'],'job-card', 'index')->name('job-card.index')->middleware('can:browse_job_card');
        Route::get('job-card/create', 'create')->name('job-card.create')->middleware('can:add_job_card');
        Route::get('job-card/show/{id}', 'show')->name('job-card.show')->middleware('can:read_job_card');
        Route::get('job-card/{id}/edit', 'edit')->name('job-card.edit')->middleware('can:edit_job_card');
        Route::post('job-card/store', 'store')->name('job-card.store')->middleware('can:add_job_card');
        Route::put('job-card/update/{id}', 'update')->name('job-card.update')->middleware('can:edit_job_card');
        Route::delete('job-card/{id}/delete', 'destroy')->name('job-card.destroy')->middleware('can:delete_job_card');

        Route::get('job-card/add/reel', 'addReel')->name('job-card.add.reel')->middleware('can:add_stock');
        Route::post('job-card/session-remove', 'removeFromSession')->name('job-card.session.remove');
    });


    Route::fallback(function () {
        return response()->view('admin.errors.404', [], 404);
    });

});
