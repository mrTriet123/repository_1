<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {// datetime format validation
        Validator::extend('date_time_format', function($attribute, $value, $formats) {
          // iterate through all formats
          foreach($formats as $format) {

            // parse date with current format
            $parsed = date_parse_from_format($format, $value);

            // if value matches given format return true=validation succeeded 
            if ($parsed['error_count'] === 0 && $parsed['warning_count'] === 0) {
              return true;
            }
          }

            // value did not match any of the provided formats, so return false=validation failed
         return false;
        });

        Validator::extend('date_greater_than', function($attribute, $value, $parameters)
        {
            if (isset($parameters[1])) {
               $other = $parameters[1];

               // return intval($value) > intval($other);
               return date('Y-m-d', strtotime($value)) >= date('Y-m-d', strtotime($other));
            } else {
              return true;
           }
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Repositories\Menu\MenuRepository','App\Repositories\Menu\DbMenuRepository');
        $this->app->bind('App\Repositories\Merchant\MerchantRepository','App\Repositories\Merchant\DbMerchantRepository');
        $this->app->bind('App\Repositories\Customer\CustomerRepository','App\Repositories\Customer\DbCustomerRepository');
        $this->app->bind('App\Repositories\Restaurant\RestaurantRepository','App\Repositories\Restaurant\DbRestaurantRepository');
        $this->app->bind('App\Repositories\OrderSetting\OrderSettingRepository','App\Repositories\OrderSetting\DbOrderSettingRepository');
        $this->app->bind('App\Repositories\Order\OrderRepository','App\Repositories\Order\DbOrderRepository');
        $this->app->bind('App\Repositories\Category\CategoryRepository','App\Repositories\Category\DbCategoryRepository');
        $this->app->bind('App\Repositories\Dish\DishRepository','App\Repositories\Dish\DbDishRepository');
        $this->app->bind('App\Repositories\Reservable\ReservableRepository','App\Repositories\Reservable\DbReservableRepository');
        $this->app->bind('App\Repositories\ActivityLog\ActivityLogRepository','App\Repositories\ActivityLog\DbActivityLogRepository');
        $this->app->bind('App\Repositories\Stripe\StripeRepository','App\Repositories\Stripe\DbStripeRepository');
    }
}
