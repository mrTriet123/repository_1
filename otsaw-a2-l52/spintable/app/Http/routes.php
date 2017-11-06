<?php
// CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');

use Illuminate\Support\Facades\Redis;
use App\Events\UpdateBookingList;

// App\Category::saving(function($model){
//   return false;
// });

Route::get('/redis',function(){
   // event(new UpdateBookingList(Request::query('namesss')));
    return view('welcome');
});

Route::get('/test',function(){
    \Event::fire(new \App\Events\UpdateWalkinList(array("this is a", "test")));
});

Route::post('oauth/access_token', function() {
    return Response::json(Authorizer::issueAccessToken());
});
/*
Route::get('billing',function(){
    return view('testbilling');
});
*/
Route::get('billing', ['as'=>'home','uses'=>'HomeController@index']);
Route::get('connect_merchant', ['as'=>'order-post','uses'=>'HomeController@createMerchant']);

/*
|--------------------------------------------------------------------------
| OAuth2
|--------------------------------------------------------------------------

*/

Route::group(['prefix' => 'api/v1'],function(){

        Route::get('restaurants/get-type','API\RestaurantController@getResType');
        Route::resource('restaurants','API\RestaurantController');
        Route::get('restaurants/{keyword}/search', 'API\RestaurantController@search');
        Route::get('restaurants/{restaurant_id}/menu', 'API\RestaurantController@menu');
        Route::get('restaurants/{restaurant_id}/dishes/{dish_id}', 'API\DishController@show');
        


        Route::post('reserve', 'API\ReservableController@reserve');
        Route::post('walkin', 'API\ReservableController@walkin');
        Route::post('order', 'API\OrderController@checkout');

        Route::get('customers/{customer_id}/orders', 'API\CustomerController@orders');

        Route::post('signup', 'API\UserController@signup');
        Route::post('admin-side/login','API\UserController@login');
        Route::get('admin-side/logout','API\Admin\AdminController@logout');
        Route::get('admin-side/list-merchant','API\Admin\AdminMerchantController@listMerchant');
        Route::get('admin-side/detail-merchant/{id}','API\Admin\AdminMerchantController@detailMerchant');
        Route::post('admin-side/generated-password-merchant','API\Admin\AdminMerchantController@generatedPassword');
        Route::post('admin-side/create-merchant','API\Admin\AdminMerchantController@createMerchant');
        Route::post('admin-side/update-merchant/{id}','API\Admin\AdminMerchantController@updateMerchant');
        Route::get('admin-side/delete-merchant/{id}','API\Admin\AdminMerchantController@deleteMerchant');
        // Route::post('users/merchant-change-password', 'API\Admin\AdminMerchantController@updateMerPassword');

        //Route::get('admin-side/list-merchant/token/{token}','API\Admin\AdminMerchantController@listMerchant');

        Route::resource('forgot_password', 'API\ForgotPasswordController');//user forget password

        /*******************
        *      WEB API - Move this to oauth
        ********************/

        Route::get('merchants/{merchant_id}/orders', 'API\MerchantController@orders');

        Route::get('merchants/{merchant_id}/bookings', 'API\MerchantController@bookings');

        Route::get('merchants/{merchant_id}/walkins', 'API\MerchantController@walkins');

        Route::get('merchants/{merchant_id}/recent_notifications', 'API\ActivityLogController@recentNotifications');

        Route::get('merchants/{merchant_id}/reservables/{reservable_id}', 'API\MerchantController@getReservableInfo');

        Route::get('merchants/{merchant_id}/notifications/{notification_id}/mark_as_read', 'API\ActivityLogController@markAsRead');

        Route::get('merchants/{merchant_id}/history', 'API\MerchantController@history');

        Route::post('merchants/{merchant_id}/save_stripe_account_id', 'API\MerchantController@stripe');

        Route::get('merchants/{merchant_id}/reports', 'API\MerchantController@reports');

        Route::post('mersignup', 'API\MerchantController@merSignup');
        Route::post('merchant-login', 'API\MerchantController@merLogin');

});

Route::group(['prefix' => 'api/v1', 'middleware' => 'merchant'],function(){
    Route::resource('merchant-side/merchant', 'API\Merchant\MerchantTestController');
    Route::get('merchant-side/list-merchant','API\Merchant\MerchantTestController@index');
    Route::post('users/merchant-change-password', 'API\Merchant\MerchantController@updateMerPassword');
    Route::post('setup-page', 'API\Merchant\MerchantController@setupPage');
    Route::get('settings/order/get-data', 'API\Merchant\MerchantController@getData');
    Route::post('settings/order', 'API\Merchant\MerchantController@settingOrder');
    Route::post('settings/order/edit', 'API\Merchant\MerchantController@editSettingOrder');
    Route::get('category/list-category-dishes','API\Merchant\MerchantCategoryController@listCategoryDishes');
    Route::get('category/list','API\Merchant\MerchantCategoryController@listCategory');
    Route::post('category/create','API\Merchant\MerchantCategoryController@createCategory');
    Route::post('category/edit','API\Merchant\MerchantCategoryController@editCategory');
    Route::get('category/list-all','API\Merchant\MerchantCategoryController@listAllCategory');
    Route::post('category/delete','API\Merchant\MerchantCategoryController@deleteCategory');
    Route::get('category/list-items','API\Merchant\MerchantCategoryController@listItems');

    // add-ons
    Route::get('add-ons/list','API\Merchant\AddonController@index');
    Route::post('add-ons/create','API\Merchant\AddonController@store');
    Route::post('add-ons/edit','API\Merchant\AddonController@update');
    Route::post('add-ons/delete','API\Merchant\AddonController@destroy');

    // dish/detail
    Route::get('dish/detail','API\Merchant\DishController@show');
    Route::post('dish/create','API\Merchant\DishController@store');
    Route::post('dish/edit','API\Merchant\DishController@update');
    Route::get('dish/search','API\Merchant\DishController@search');
    Route::post('dish/delete','API\Merchant\DishController@destroy');
});

Route::group(['prefix' => 'api/v1', 'middleware' => 'oauth', 'before' => 'oauth'],function(){
    
        // user
        Route::resource('users','API\UserController');
        //end


        Route::post('users/logout','API\UserController@logout');

        Route::post('users/{user_id}/update_profile', 'API\UserController@updateUserInformation');

        Route::post('users/{user_id}/change_password', 'API\UserController@updateUserPassword');

        Route::post('customers/{customer_id}/cards', 'API\CustomerController@addCard');

        // Route::post('customers/{customer_id}/cards/{card_id}', 'API\CustomerController@updateCard');

        Route::delete('customers/{customer_id}/cards/{card_id}', 'API\CustomerController@destroyCard');
});
/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


//Route::get('/', function () {
//    return view('pages.viewPage');
//});




// Route::get('/register', function () {
//     return view('pages.registerForm');
// });

//Route::get('/paymentToken','API\PaymentController@getPaymentToken');

Route::get('/list', function () {
    return view('pages.listPage');
});
// Route::get('hi', 'RoleController@index');

Route::get('/template',function() {
    return view('layouts.email.customer_invoice');
});
Route::get('/template2',function() {
    return view('layouts.email.driver_payslip');
});




Route::get('/register2' ,function(){
    $userData = array(
        'firstname' => "Admin",
        'lastname' => "OTSAW",
        'email' => "kenneth@otsaw.com",
        'password' => \Illuminate\Support\Facades\Hash::make("password")
    );
    $user = \App\User::create($userData);

    return $user;
});


Route::get('/data' ,function(){
    $merchant = array(
        'user_id' => 1,
        'mobile_no' => "93725123"
    );
    $result = \App\Merchant::create($merchant);

    $menu = array(
        'merchant_id' => 1
    );
    $result = \App\Menu::create($menu);

    $category = array(
        'name' => 'Drinks',
        'drink_category' => 1,
        'menu_id' => 1
    );
    $result = \App\Category::create($category);

    $dish = array(
        'name' => "Milo",
        'category_id' => 1
    );
    $result = \App\Dish::create($dish);

    $dish = array(
        'name' => "Iced Lemon Tea",
        'category_id' => 1
    );
    $result = \App\Dish::create($dish);

    $dish = array(
        'dish_id' => 1,
        'size' => 's',
        'price' => 10
    );
    $result = \App\DishSize::create($dish);

    $dish = array(
        'dish_id' => 1,
        'size' => 'm',
        'price' => 15
    );
    $result = \App\DishSize::create($dish);

    $dish = array(
        'merchant_id' => 1,
        'reservation_start_time' => '07:00:00',
        'reservation_end_time' => '17:00:00',
        'eating_hours' => 2
    );
    $result = \App\OrderSetting::create($dish);

    $dish = array(
        'merchant_id' => 1,
        'name' => 'Hillman',
        'mobile_no' => '12345',
        'type' => 'Chinese'
    );
    $result = \App\Restaurant::create($dish);    

    $dish = array(
        'restaurant_id' => 1,
        'name' => 'res1',
        'path' => 'http://www.hillmanrestaurant.com/wp-content/themes/hillman/img/hillman-logo.png',
        'name_origin' => 'res1.jpg'
    );
    $result = \App\RestaurantImage::create($dish); 

    $dish = array(
        'restaurant_id' => 1,
        'name' => 'res2',
        'path' => 'http://3.bp.blogspot.com/-pM63K75IjjI/USzEB9nn4OI/AAAAAAAATX4/dKL6pFTMlxs/s1600/2013-02-08+14.08.42.jpg',
        'name_origin' => 'res2.jpg'
    );
    $result = \App\RestaurantImage::create($dish); 

    return $result;
});

//Route::get('/api/test-push',function(){
//    $driverID = Illuminate\Support\Facades\Input::get('driver_id');
//    $customerID = Illuminate\Support\Facades\Input::get('customer_id');
//    $type = Illuminate\Support\Facades\Input::get('type');
//    $job_id = Illuminate\Support\Facades\Input::get('job_id');
//    $intType = (int)$type;
//    return App\Http\Controllers\API\NotificationController::sendNotification($customerID,$driverID,$intType, $job_id);
//});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
//Route::group(['middleware' => 'web', 'before' => 'session'], function () {
    Route::auth();

    Route::get('/', 'Auth\AuthController@getLogin');
    Route::group(['middleware' => 'auth'], function(){
        Route::get('/home', 'HomeController@index');

        //user
        Route::group(['middleware' => ['permission:user-list']], function() {
            Route::get('user/user-list', 'UserController@index');
        });

        Route::group(['middleware' => ['permission:create-user']], function() {
            Route::get('user/create', 'UserController@getCreate');
            Route::post('user/create', 'UserController@postCreate');
        });

        Route::group(['middleware' => ['permission:view-user']], function() {
            Route::get('user/view/{id}','UserController@getView');
        });

        Route::group(['middleware' => ['permission:edit-user']], function() {
            Route::get('user/edit/{id}','UserController@getEdit');
            Route::post('user/update','UserController@postUpdate');
        });

        Route::group(['middleware' => ['permission:delete-user']], function() {
            Route::get('user/delete/{id}','UserController@doDelete');
        });
        //end user

        //waiter
        Route::group(['middleware' => ['permission:waiter-list']], function() {
            Route::get('waiter', 'WaiterController@index');
        });

        Route::group(['middleware' => ['permission:create-waiter']], function() {
            Route::get('waiter/create', 'WaiterController@getCreate');
            Route::post('waiter/create', 'WaiterController@postCreate');
        });

        Route::group(['middleware' => ['permission:view-waiter']], function() {
            Route::get('waiter/view/{id}','WaiterController@getView');
        });

        Route::group(['middleware' => ['permission:edit-waiter']], function() {
            Route::get('waiter/edit/{id}','WaiterController@getEdit');
            Route::post('waiter/update','WaiterController@postUpdate');
        });

        Route::group(['middleware' => ['permission:delete-waiter']], function() {
            Route::get('waiter/delete/{id}','WaiterController@doDelete');
        });
        //end waiter

        //role
        Route::group(['middleware' => ['permission:role-list']], function() {
            Route::get('role/role-list', 'RoleController@index');
        });

        Route::group(['middleware' => ['permission:create-role']], function() {
            Route::get('role/create', 'RoleController@getCreate');
            Route::post('role/create', 'RoleController@postCreate');
        });

        Route::group(['middleware' => ['permission:view-role']], function() {
            Route::get('role/view/{id}','RoleController@getView');
        });

        Route::group(['middleware' => ['permission:edit-role']], function() {
            Route::get('role/edit/{id}','RoleController@getEdit');
            Route::post('role/update','RoleController@postUpdate');
        });

        Route::group(['middleware' => ['permission:delete-role']], function() {
            Route::get('role/delete/{id}','RoleController@doDelete');
        });
        //end role

        //customer
//        Route::group(['middleware' => ['permission:customer-list']], function() {
//            Route::get('customers','CustomerController@getCustomerList');
//        });
//
//        Route::group(['middleware' => ['permission:view-customer']], function() {
//            Route::get('customers/view/{id}','CustomerController@getView');
//        });
//
//        Route::group(['middleware' => ['permission:edit-customer']], function() {
//            Route::get('customers/edit/{id}','CustomerController@getEdit');
//            Route::post('customers/update','CustomerController@postUpdate');
//        });
//
//        Route::group(['middleware' => ['permission:delete-customer']], function() {
//            Route::get('customers/delete/{id}','CustomerController@doDelete');
//        });
        //end customer

        //driver
//        Route::group(['middleware' => ['permission:driver-list']], function() {
//            Route::get('driver','DriverController@getDriverUser');
//        });
//
//        Route::group(['middleware' => ['permission:create-driver']], function() {
//            Route::get('driver/create','DriverController@getCreate');
//            Route::post('driver/create','DriverController@postCreateDriver');
//        });
//
//        Route::group(['middleware' => ['permission:view-driver']], function() {
//            Route::get('driver/view/{id}','DriverController@getView');
//        });
//
//        Route::group(['middleware' => ['permission:edit-driver']], function() {
//            Route::get('driver/edit/{id}','DriverController@getEdit');
//            Route::post('driver/update','DriverController@postUpdate');;
//        });
//
//        Route::group(['middleware' => ['permission:delete-driver']], function() {
//            Route::get('driver/delete/{id}','DriverController@doDelete');
//        });
        //end driver

        //job
//        Route::group(['middleware' => ['permission:view-job']], function() {
//            Route::get('jobs', 'JobController@index');
//        });
//
//        Route::group(['middleware' => ['permission:view-job-detail']], function() {
//            Route::get('jobs/view/{id}', 'JobController@viewJobDetail');
//        });
        //end job

        //customer transaction
//        Route::group(['middleware' => ['permission:driver-transaction']], function() {
//            Route::get('driver-transactions', 'DriverTransactionController@index');
//            Route::post('driver-transactions/export', 'DriverTransactionController@exportTransactionList');
//            Route::get('transaction_payment', 'DriverTransactionController@makePayment');
//            Route::get('transaction_payment/view', 'DriverTransactionController@viewPaymentHistory');
//        });
        //end customer transaction

        //driver transaction
//        Route::group(['middleware' => ['permission:customer-transaction']], function() {
//            Route::get('customer-transactions', 'CustomerTransactionController@index');
//            Route::get('customer-transactions/export', 'CustomerTransactionController@exportTransactionList');
//        });
    //create job remarks
//    Route::group(['middleware' => ['permission:create-job-remarks']], function() {
//        Route::post('jobs/remarks', 'JobController@jobRemark');
//    });
    // create driver remarks
//    Route::group(['middleware' => ['permission:create-driver-remarks']], function() {
//        Route::post('driver/remarks', 'DriverController@driverRemark');
//    });
    //create customer remark
//    Route::group(['middleware' => ['permission:create-customer-remarks']], function() {
//        Route::post('customers/remarks', 'CustomerController@customerRemark');
//    });
    //send welcome and active email to all driver
//    Route::group(['middleware' => ['permission:send-email-to-all-driver']], function() {
//        Route::post('send_email_to_drivers', 'DriverController@sendDriverEmail');
//    });

    // send download app and welcome message one by one
//    Route::group(['middleware' => ['permission:send-driver-individual-email']], function() {
//        Route::get('send_driver_email/{id}', 'DriverController@sendSingleDriverEmail');
//    });
    // end send
    //view driver transaction details
//    Route::group(['middleware' => ['permission:view-driver-transaction-detail']], function() {
//        Route::post('driver-transactions/view_driver_transaction_details', 'DriverTransactionController@viewDriverTransaction');
//    });
    // end view transaction
    // make payment to driver
//    Route::group(['middleware' => ['permission:make-payment-to-driver']], function() {
//        Route::post('driver-transactions/pay_driver_weekly', 'DriverTransactionController@payDriverWeekly');
//    });
    //end payment

//    Route::get('driver_profile', 'UserController@getProfile');
//    Route::post('driver/update_profile', 'UserController@updateProfile');

        Route::get('user_profile', 'UserController@getProfile');
        Route::post('users/update_profile', 'UserController@updateUserProfile');

    //promotion
//    Route::group(['middleware' => ['permission:promotion-list']], function() {
//        Route::get('promotions', 'PromotionController@getPromotions');
//    });

//    Route::group(['middleware' => ['permission:create-promotion']], function() {
//        Route::get('promotions/create', 'PromotionController@createPromotions');
//        Route::post('promotions/create', 'PromotionController@postCreatePromotions');
//     });
//
//    Route::group(['middleware' => ['permission:view-promotion']], function() {
//        Route::get('promotions/view/{id}', 'PromotionController@viewPromotions');
//    });
//
//    Route::group(['middleware' => ['permission:edit-promotion']], function() {
//        Route::get('promotions/edit/{id}', 'PromotionController@editPromotions');
//        Route::post('promotions/updates', 'PromotionController@updatePromotions');
//    });
//
//    Route::group(['middleware' => ['permission:delete-promotion']], function() {
//        Route::get('promotions/delete/{id}','PromotionController@deletePromotion');
//    });
    //end promotion

    //cancel job
//    Route::group(['middleware' => ['permission:driver-cancel-list']], function() {
//        Route::get('driver/cancel_jobs', 'CancelJobListController@index');
//    });
    //end cancel job

    //permission
    // Route::group(['middleware' => ['permission:driver-cancel-list']], function() {
        Route::get('permissions/permissions-list', 'PermissionController@index');
        Route::get('permissions/view/{id}', 'PermissionController@getView');
        Route::get('permissions/create', 'PermissionController@createPermission');
        Route::post('permissions/create', 'PermissionController@postCreate');
        Route::get('permissions/edit/{id}', 'PermissionController@editPermission');
        Route::post('permissions/updates', 'PermissionController@updatePermission');
        Route::get('permissions/delete/{id}', 'PermissionController@doDelete');
    // });
    //end permission

        //category
        Route::group(['middleware' => ['permission:category-list']], function() {
            Route::get('category', 'CategoryController@index');
        });

        Route::group(['middleware' => ['permission:create-category']], function() {
            Route::get('category/create', 'CategoryController@create');
            Route::post('category/create', 'CategoryController@postCreate');
        });

        Route::group(['middleware' => ['permission:view-category']], function() {
            Route::get('category/view/{id}','CategoryController@show');
        });

        Route::group(['middleware' => ['permission:edit-category']], function() {
            Route::get('category/edit/{id}','CategoryController@edit');
            Route::post('category/update','CategoryController@postEdit');
        });

        Route::group(['middleware' => ['permission:delete-category']], function() {
            Route::get('category/delete/{id}','CategoryController@delete');
        });

        Route::group(['middleware' => ['permission:category-dishes']], function() {
            Route::get('category/dishes/{id}','DishController@index');
        });
        //end category

        //dish
        Route::group(['middleware' => ['permission:create-dish']], function() {
            Route::get('dish/create/{id}', 'DishController@create');
            Route::post('dish/create/{id}', 'DishController@postCreate');
        });

        Route::group(['middleware' => ['permission:view-dish']], function() {
            Route::get('dish/view/{id}','DishController@show');
        });

        Route::group(['middleware' => ['permission:edit-dish']], function() {
            Route::get('dish/edit/{id}','DishController@edit');
            Route::post('dish/update','DishController@postEdit');
        });

        Route::group(['middleware' => ['permission:delete-dish']], function() {
            Route::get('dish/delete/{category_id}/{id}','DishController@delete');
        });
        //end dish

        //floor
        Route::group(['middleware' => ['permission:floor-list']], function() {
            Route::get('floor', 'FloorController@index');
        });

        Route::group(['middleware' => ['permission:create-floor']], function() {
            Route::get('floor/create', 'FloorController@create');
            Route::post('floor/create', 'FloorController@postCreate');
        });

        Route::group(['middleware' => ['permission:view-floor']], function() {
            Route::get('floor/view/{id}','FloorController@show');
        });

        Route::group(['middleware' => ['permission:edit-floor']], function() {
            Route::get('floor/edit/{id}','FloorController@edit');
            Route::post('floor/update','FloorController@postEdit');
        });

        Route::group(['middleware' => ['permission:delete-floor']], function() {
            Route::get('floor/delete/{id}','FloorController@delete');
        });

        Route::group(['middleware' => ['permission:floor-tables']], function() {
            Route::get('floor/tables/{id}','TableController@index');
        });
        //end floor

        //table
        Route::group(['middleware' => ['permission:create-table']], function() {
            Route::get('table/create/{id}', 'TableController@create');
            Route::post('table/create/{id}', 'TableController@postCreate');
        });

        Route::group(['middleware' => ['permission:view-table']], function() {
            Route::get('table/view/{floor_id}/{id}','TableController@show');
        });

        Route::group(['middleware' => ['permission:edit-table']], function() {
            Route::get('table/edit/{floor_id}/{id}','TableController@edit');
            Route::post('table/update','TableController@postEdit');
        });

        Route::group(['middleware' => ['permission:delete-table']], function() {
            Route::get('table/delete/{floor_id}/{id}','TableController@delete');
        });
        //end table

        //company
        Route::group(['middleware' => ['permission:company-list']], function() {
            Route::get('company', 'CompanyController@index');
        });

        Route::group(['middleware' => ['permission:create-company']], function(){
            Route::get('company/create', 'CompanyController@create');
            Route::post('company/create', 'CompanyController@postCreate');
        });

        Route::group(['middleware' => ['permission:view-company']], function() {
            Route::get('company/view/{id}','CompanyController@show');
        });

        Route::group(['middleware' => ['permission:edit-company']], function() {
            Route::get('company/edit/{id}','CompanyController@edit');
            Route::post('company/update','CompanyController@postEdit');
        });

        Route::group(['middleware' => ['permission:delete-company']], function() {
            Route::get('company/delete/{id}','CompanyController@delete');
        });

        Route::group(['middleware' => ['permission:company-outlets']], function(){
            Route::get('company/outlets/{id}', 'OutletController@index');
        });
        //end company

        //outlet
        Route::group(['middleware' => ['permission:create-outlet']], function() {
            Route::get('outlet/create/{id}', 'OutletController@create');
            Route::post('outlet/create/{id}', 'OutletController@postCreate');
        });

        Route::group(['middleware' => ['permission:view-outlet']], function() {
            Route::get('outlet/view/{id}','OutletController@show');
        });

        Route::group(['middleware' => ['permission:edit-outlet']], function() {
            Route::get('outlet/edit/{id}','OutletController@edit');
            Route::post('outlet/update','OutletController@postEdit');
        });

        Route::group(['middleware' => ['permission:delete-outlet']], function() {
            Route::get('outlet/delete/{id}','OutletController@delete');
        });
        //end outlet

        //rating
        Route::group(['middleware' => ['permission:rating']], function() {
            Route::get('rating', 'ReportController@rating');
        });
        //end rating

        //sales
        Route::group(['middleware' => ['permission:report-sales']], function() {
            Route::get('report/sales', 'ReportController@sales');
        });

        Route::group(['middleware' => ['permission:report-transactions']], function() {
            Route::get('report/transactions', 'ReportController@transactions');
        });
        //end sales

        //beverages
        Route::group(['middleware' => ['permission:beverage-list']], function() {
            Route::get('beverages', 'DishController@beveragesList');
        });

        Route::group(['middleware' => ['permission:create-beverage']], function() {
            Route::get('beverages/create/{id}', 'DishController@createBeverages');
            Route::post('beverages/create/{id}', 'DishController@postCreateBeverages');
        });

        Route::group(['middleware' => ['permission:view-beverage']], function() {
            Route::get('beverages/view/{id}', 'DishController@viewBeverages');
        });

        Route::group(['middleware' => ['permission:edit-beverage']], function() {
            Route::get('beverages/edit/{id}', 'DishController@editBeverages');
            Route::post('beverages/edit/{id}', 'DishController@postEditBeverages');
        });

        Route::group(['middleware' => ['permission:delete-beverage']], function() {
            Route::get('beverages/delete/{category_id}/{id}','DishController@delete');
        });
        //end beverages

        //search
        Route::group(['middleware' => ['permission:search']], function() {
            Route::get('search', 'SearchController@index');
        });
        //end search

        //export
        Route::group(['middleware' => ['permission:export-sales']], function() {
            Route::get('export/sales', 'ReportController@exportSaleView');
            Route::post('export/sales', 'ReportController@exportSaleToExcel');
        });

        Route::group(['middleware' => ['permission:export-transactions']], function() {
            Route::get('export/transactions', 'ReportController@exportTransactionView');
            Route::post('export/transactions', 'ReportController@exportTransactionExcel');
        });
        //end export


        Route::post('auth/login', 'Auth\AuthController@postLogin');
        Route::get('auth/logout', 'Auth\AuthController@getLogout');

        // Registration routes...
        Route::get('auth/register', 'Auth\AuthController@getRegister');
        Route::post('auth/register', 'Auth\AuthController@postRegister');

        Route::get('driver/register', 'DriverController@register');
        Route::post('driver/register', 'DriverController@postDriverRegister');

        Route::controllers([
        'password' => 'Auth\PasswordController',
        ]);
    });
});
