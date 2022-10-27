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
| Middleware options can be located in `app/Http/Kernel.php`
|
*/

// Homepage Route
Route::group(['middleware' => ['web', 'checkblocked']], function () {
    Route::get('/', 'App\Http\Controllers\Frontend\HomeController@index')->name('home');
    Route::get('/prices', 'App\Http\Controllers\Frontend\HomeController@prices')->name('prices');
    Route::get('/sorry', 'App\Http\Controllers\Frontend\HomeController@sorry')->name('sorry');
    Route::get('/terms', 'App\Http\Controllers\TermsController@terms')->name('terms');
    Route::get('/waterpdf/{filename}', 'App\Http\Controllers\DocumentController@watermark')->name('pdf');
});

// Authentication Routes
Auth::routes();

// Public Routes
Route::group(['middleware' => ['web', 'activity', 'checkblocked']], function () {

    // Activation Routes
    Route::get('/activate', ['as' => 'activate', 'uses' => 'App\Http\Controllers\Auth\ActivateController@initial']);

    Route::get('/activate/{token}', ['as' => 'authenticated.activate', 'uses' => 'App\Http\Controllers\Auth\ActivateController@activate']);
    Route::get('/activation', ['as' => 'authenticated.activation-resend', 'uses' => 'App\Http\Controllers\Auth\ActivateController@resend']);
    Route::get('/exceeded', ['as' => 'exceeded', 'uses' => 'App\Http\Controllers\Auth\ActivateController@exceeded']);

    // Socialite Register Routes
    Route::get('/social/redirect/{provider}', ['as' => 'social.redirect', 'uses' => 'App\Http\Controllers\Auth\SocialController@getSocialRedirect']);
    Route::get('/social/handle/{provider}', ['as' => 'social.handle', 'uses' => 'App\Http\Controllers\Auth\SocialController@getSocialHandle']);

    // Route to for user to reactivate their user deleted account.
    Route::get('/re-activate/{token}', ['as' => 'user.reactivate', 'uses' => 'App\Http\Controllers\RestoreUserController@userReActivate']);
});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'activated', 'activity', 'checkblocked']], function () {

    // Activation Routes
    Route::get('/activation-required', ['uses' => 'App\Http\Controllers\Auth\ActivateController@activationRequired'])->name('activation-required');
    Route::get('/logout', ['uses' => 'App\Http\Controllers\Auth\LoginController@logout'])->name('logout');
});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'activated', 'activity', 'twostep', 'checkblocked']], function () {

    //  Homepage Route - Redirect based on user role is in controller.
    Route::get('/home', ['as' => 'public.home',   'uses' => 'App\Http\Controllers\BundleController@index']);
    Route::get('/choosePlan', ['as' => 'public.choosePlan',   'uses' => 'App\Http\Controllers\PlanController@choosePlan']);
    Route::get('/choosePackage/{id}', ['as' => 'choosePackage',   'uses' => 'App\Http\Controllers\PlanController@enrolPackage']);
    Route::get('/paymentPage/{id}/{price}', ['as' => 'payment.index',   'uses' => 'App\Http\Controllers\PaymentController@index']);
    Route::post('/payment/paypal', ['as' => 'payment.paypal',   'uses' => 'App\Http\Controllers\PaypalController@payment']);
    Route::get('/payment/paypal/success/{package_id}', ['as' => 'payment.success',   'uses' => 'App\Http\Controllers\PaypalController@success']);
    Route::post('/payment/paypal/cancel', ['as' => 'payment.cancel',   'uses' => 'App\Http\Controllers\PaypalController@paymentCancel']);
    Route::get('/payment/strippe/index', ['as' => 'payment.strippe.index',   'uses' => 'App\Http\Controllers\StripeController@index']);
    Route::post('/payment/strippe/{package_id}', ['as' => 'payment.strippe',   'uses' => 'App\Http\Controllers\StripeController@charge']);
    Route::post('/payment/stripe/success/{package_id}', ['as' => 'payment.strippe.success',   'uses' => 'App\Http\Controllers\StripeController@confirm']);
    Route::post('/payment/stripe/cancel', ['as' => 'payment.strippe.cancel',   'uses' => 'App\Http\Controllers\StripeController@paymentCancel']);
    Route::get('/bundle/files/create/number={bundle_id}/section={section_id}', ['as' => 'public.bundle.files.create',   'uses' => 'App\Http\Controllers\DocumentController@create']);
    Route::post('/bundle/files/store', ['as' => 'public.bundle.files.store',   'uses' => 'App\Http\Controllers\DocumentController@uploadDocuments']);
    Route::get('/bundle/files/number={bundle_id}/section={section_id}/file={id}', ['as' => 'public.bundle.files.show',   'uses' => 'App\Http\Controllers\DocumentController@show']);
    Route::post('/bundle/files/update', ['as' => 'public.bundle.files.update',   'uses' => 'App\Http\Controllers\DocumentController@update']);
    Route::post('/bundle/files/rename', ['as' => 'public.bundle.files.rename',   'uses' => 'App\Http\Controllers\DocumentController@rename']);

    Route::post('/bundle/files/update-order',['as' => 'public.bundle.files.updateOrder',   'uses' => 'App\Http\Controllers\DocumentController@updateOrder']);
    Route::get('/bundle/files/delete/file={id}', ['as' => 'public.bundle.files.delete',   'uses' => 'App\Http\Controllers\DocumentController@delete']);
    Route::get('/bundle/generate/number={bundle_id}', ['as' => 'public.bundle.generate',   'uses' => 'App\Http\Controllers\DocumentController@generate']);
    Route::resource(
        'bundle',
        \App\Http\Controllers\BundleController::class,

    );
    Route::get('/bundle/{bundle_name}/number={}', ['as' => 'bundle.show_single',   'uses' => 'App\Http\Controllers\BundleController@show']);
    Route::get('/bundle/generated-bundle/bundle-list/number={bundle_id}', ['as' => 'public.bundle.generated_bundle',   'uses' => 'App\Http\Controllers\BundleController@generated_bundle']);
    Route::delete('/bundle/generated_bundle/number={bundle_id}', ['as' => 'bundle.generated.destroy',   'uses' => 'App\Http\Controllers\BundleController@generated_destroy']);
    Route::resource(
        'setting',
        \App\Http\Controllers\SettingController::class,

    );
    Route::get('payment/settings',['as' => 'settings.payement.index','uses'=>'\App\Http\Controllers\SettingController@paymentSettingPage']);
    Route::post('payment/settings',['as' => 'setting.store.payment','uses'=>'\App\Http\Controllers\SettingController@paymentSettingUpdate']);
    Route::get('plan/settings',['as' => 'settings.plan.index','uses'=>'\App\Http\Controllers\SettingController@planSettingPage']);
    Route::post('plan/settings',['as' => 'setting.store.plan','uses'=>'\App\Http\Controllers\SettingController@planSettingUpdate']);
    Route::resource(
        'section',
        \App\Http\Controllers\SectionController::class,

    );
    Route::get('/bundle/section/edit/number={bundle_id}/section={section_id}', ['as' => 'public.bundle.section.edit',   'uses' => 'App\Http\Controllers\SectionController@edit']);
    Route::get('/bundle/section/delete/section={section_id}', ['as' => 'public.bundle.section.destroy',   'uses' => 'App\Http\Controllers\SectionController@destroy']);
    Route::post('/bundle/section/update-order',['as' => 'public.bundle.section.updateOrder',   'uses' => 'App\Http\Controllers\SectionController@updateOrder']);
    // Show users profile - viewable by other users.
    Route::get('profile/{username}', [
        'as'   => '{username}',
        'uses' => 'App\Http\Controllers\ProfilesController@show',
    ]);
});

// Registered, activated, and is current user routes.
Route::group(['middleware' => ['auth', 'activated', 'currentUser', 'activity', 'twostep', 'checkblocked']], function () {

    // User Profile and Account Routes
    Route::resource(
        'profile',
        \App\Http\Controllers\ProfilesController::class,
        [
            'only' => [
                'show',
                'edit',
                'update',
                'create',
            ],
        ]
    );
    Route::put('profile/{username}/updateUserAccount', [
        'as'   => '{username}',
        'uses' => 'App\Http\Controllers\ProfilesController@updateUserAccount',
    ]);
    Route::put('profile/{username}/updateUserPassword', [
        'as'   => '{username}',
        'uses' => 'App\Http\Controllers\ProfilesController@updateUserPassword',
    ]);
    Route::delete('profile/{username}/deleteUserAccount', [
        'as'   => '{username}',
        'uses' => 'App\Http\Controllers\ProfilesController@deleteUserAccount',
    ]);

    // Route to show user avatar
    Route::get('images/profile/{id}/avatar/{image}', [
        'uses' => 'App\Http\Controllers\ProfilesController@userProfileAvatar',
    ]);

    // Route to upload user avatar.
    Route::post('avatar/upload', ['as' => 'avatar.upload', 'uses' => 'App\Http\Controllers\ProfilesController@upload']);
});

// Registered, activated, and is admin routes.
Route::group(['middleware' => ['auth', 'activated', 'role:admin', 'activity', 'twostep', 'checkblocked']], function () {
    Route::resource('package', \App\Http\Controllers\PackageController::class);
    Route::resource('plan', \App\Http\Controllers\PlanController::class);
    Route::post('plan/store/{id}', ['as'=> "plan.storeById" ,'uses'=> '\App\Http\Controllers\PlanController@store']);
    Route::get('plan/delete/{id}', ['as'=> "plan.destroy" ,'uses'=> '\App\Http\Controllers\PlanController@destroy']);

    Route::resource('/users/deleted', \App\Http\Controllers\SoftDeletesController::class, [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ]);

    Route::resource('users', \App\Http\Controllers\UsersManagementController::class, [
        'names' => [
            'index'   => 'users',
            'destroy' => 'user.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::get("users/plan/change-plan/{user_id}",[App\Http\Controllers\UserController::class, 'changePlan'])->name('users.change_plan');
    Route::post("users/plan/change-plan/{user_id}",[App\Http\Controllers\UserController::class, 'updatePlan'])->name('users.change_plan.update');
    Route::post('search-users', 'App\Http\Controllers\UsersManagementController@search')->name('search-users');

    Route::resource('themes', \App\Http\Controllers\ThemesManagementController::class, [
        'names' => [
            'index'   => 'themes',
            'destroy' => 'themes.destroy',
        ],
    ]);

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::get('routes', 'App\Http\Controllers\AdminDetailsController@listRoutes');
    Route::get('active-users', 'App\Http\Controllers\AdminDetailsController@activeUsers');
});

Route::redirect('/php', '/phpinfo', 301);

// GUEST
