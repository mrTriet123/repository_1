<?php
namespace App\Http\Controllers\API\Merchant;

use Mail;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\ApiController;
use Illuminate\Support\Facades\Input as Input;
use Validator;
use Illuminate\Support\MessageBag;
use App\User;
use App\Role;
use App\Merchant;
use App\MerchantTable;
use App\Restaurant;
use App\Customer;
use App\OrderSetting;
use App\Installation;
use App\Category;
use App\Dish;
use App\Menu;
use App\Addon;
use App\CategoryImage;
use App\DishCategory;
use Illuminate\Http\Response;
use Hash;
use Auth;
use LucaDegasperi\OAuth2Server\Authorizer as Authorizer;
use DB;
use App\Repositories\Stripe\StripeRepository;

class MerchantCategoryController extends ApiController
{
    
    public function listCategoryDishes(){

        $token =  $_GET['token'];
        $user = User::where('token',$token)->first();
        $merchant = Merchant::where('user_id',$user->id)->first();
        if(count($merchant) > 0){
            $menu = Menu::where('merchant_id',$merchant->id)->first();
            $category = Category::where('menu_id',$menu->id)->get();
            $data = [];
            foreach ($category as $cate) {
                # code...
                $dish_category = DishCategory::where('category_id',$cate->id)->get();
                // $dish_category = DishCategory::where('category_id',$cate->id)->get();
                $dishes = [];
                foreach ($dish_category as $di_ca) {
                    # code...
                    $dishes[] = Dish::select('id','name')->where('id',$di_ca->dish_id)->first();
                }

                $data[] = [
                    'category_id' => $cate->id,
                    'category_name' => $cate->name,
                    'dishes' => $dishes,
                ];           
            }

            return response()->json([
                'result' => 1,
                'data' => $data
            ],200);
        }
        return response()->json([
            'result' => 0,
        ],200);
            
    }

    public function listCategory(Request $request)
    {

        $token =  $_GET['token'];
        $drink_category =  $request->get("drink_category");
        $user = User::where('token',$token)->first();
        $merchant = Merchant::where('user_id',$user->id)->first();

        if(count($merchant) > 0) {
            if ($drink_category === '0' || $drink_category === '1')
                $category = Category::where([['drink_category',$drink_category],['menu_id', $merchant->menu->id]])->get();
            else $category = Category::where('menu_id', $merchant->menu->id)->get();

            $data = [];
            foreach ($category as $key => $cate) {
                $cate_img = CategoryImage::where('category_id',$cate->id)->get();
                if($cate_img){
                    $image = [];
                    foreach ($cate_img as $cat_img) {
                        # code...
                        $image[] = $cat_img->path;
                    }
                    $data[] = [
                        'id' => $cate->id,
                        'name' => $cate->name,
                        'description' => $cate->description,
                        'image' => $image,
                    ];
                }else{
                    $data[] = [
                        'id' => $cate->id,
                        'name' => $cate->name,
                        'description' => $cate->description,
                    ];
                }
                
            }

            return response()->json([
                'result' => 1,
                'data' => $data
            ],200);
        }

        return response()->json([
            'result' => 0,
        ],200);
    }

    public function createCategory(Request $request)
    {
        $token =  $_GET['token'];
        $user = User::where('token',$token)->first();
        $merchant = Merchant::where('user_id',$user->id)->first();
        $menu = Menu::where('merchant_id',$merchant->id)->first();
        if(count($merchant) > 0){
            
            $categories = new Category;
            $categories->name = strtoupper($request['name']);
            $categories->description = ucwords($request['description']);
            $categories->menu_id = $menu->id;
            $categories->drink_category =$request->drink_category;
            $categories->save();
            // echo "Start <br/>"; echo '<pre>'; print_r($categories);echo '</pre>';exit("End Data");
            if($request->hasFile('image')){
                $uploaddir = config('app.uploadfolder');
                foreach($_FILES as $file)
                {
                    if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name'])))
                    {
                        $files[] = $uploaddir .$file['name'];
                    }
                    else
                    {
                        $error = true;
                    }

                    $path = implode($files);
                    $categoryImages = new CategoryImage;
                    $categoryImages->name = rtrim($file['name'], '.jpg');
                    $categoryImages->path = $path;
                    $categoryImages->name_origin = $file['name'];
                    $categoryImages->category_id = $categories->id;
                    $categoryImages->save();
                }
            }

            return response()->json([
                'result' => 1,
            ],200);

        }

        return response()->json([
            'result' => 0,
        ],200);
    }

    public function editCategory(Request $request)
    {
        $token =  $_GET['token'];
        $user = User::where('token',$token)->first();
        $merchant = Merchant::where('user_id',$user->id)->first();
        $menu = Menu::where('merchant_id',$merchant->id)->first();
        $category = Category::where('id',$request->id)->first();
        if(count($category) > 0){
            $category->name = strtoupper($request['name']);
            $category->description = ucwords($request['description']);
            $category->drink_category = $request->drink_category;
            $category->save();

            if($request->hasFile('image')){
                $uploaddir = config('app.uploadfolder');

                $dishImages = CategoryImage::where('category_id',$category->id);
                if($dishImages->count())
                    $dishImages->delete();

                foreach($_FILES as $file)
                {
                    if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name'])))
                    {
                        $files[] = $uploaddir .$file['name'];
                    }
                    else
                    {
                        $error = true;
                    }

                    $path = implode($files);
                    //$categoryImageData = CategoryImage::where('category_id',$category->id);
                    //if($categoryImageData->count()) {
                    //    $categoryImage = CategoryImage::where('category_id',$category->id)->first();
                    //} else {
                        $categoryImage = new CategoryImage;
                        $categoryImage->category_id = $category->id;
                    //}
                    $categoryImage->name = rtrim($file['name'], '.jpg');
                    $categoryImage->path = $path;
                    $categoryImage->name_origin = $file['name'];
                    $categoryImage->save();
                }
            }

            return response()->json([
                'result' => 1,
            ],200);

        }

        return response()->json([
            'result' => 0,
        ],200);
    }

    public function listAllCategory(Request $request)
    {
        $token =  $_GET['token'];
        $user = User::where('token',$token)->first();
        $merchant = Merchant::where('user_id',$user->id)->first();
        $menu = Menu::where('merchant_id',$merchant->id)->first();
        $categories = Category::where('menu_id',$menu->id)->get();
        $addon = Addon::where('merchant_id',$merchant->id)->get();
        if(count($categories) > 0){

            # code...
            $cate_dishes = Category::where([['drink_category',0],['menu_id',$menu->id]])->get();
            
            foreach ($cate_dishes as $ca_dis) {//5
                # code...
                $category_image = CategoryImage::where('category_id',$ca_dis->id)->get();

                if($category_image){
                    $image = [];
                    foreach ($category_image as $cat_img) {
                        # code...
                        $image[] = $cat_img->path;
                    }
                    $data_dish[] = [
                        'id' => $ca_dis->id,
                        'name' => $ca_dis->name,
                        'description' => $ca_dis->description,
                        'image' => $image,
                    ];
                }else{
                    $data_dish[] = [
                        'id' => $ca_dis->id,
                        'name' => $ca_dis->name,
                        'description' => $ca_dis->description,
                    ];
                }
                
            }
            $cate_drinks = Category::where([['drink_category',1],['menu_id',$menu->id]])->get();
            $data_drink = [];
            foreach ($cate_drinks as $ca_drin) {//5
                # code...
                $category_image = CategoryImage::where('category_id',$ca_drin->id)->get();

                if($category_image){
                    $image = [];
                    foreach ($category_image as $cat_img) {
                        # code...
                        $image[] = $cat_img->path;
                    }
                    $data_drink[] = [
                        'id' => $ca_drin->id,
                        'name' => $ca_drin->name,
                        'description' => $ca_drin->description,
                        'image' => $image,
                    ];
                }else{
                    $data_drink[] = [
                        'id' => $ca_drin->id,
                        'name' => $ca_drin->name,
                        'description' => $ca_drin->description,
                    ];
                }
                
            }
            return response()->json([
                'result' => 1,
                'data'=>[
                    '$addon' => $addon,
                    'data_dish' => $data_dish,
                    'data_drink' => $data_drink,
                ]
            ],200);

        }

        return response()->json([
            'result' => 0,
        ],200);
    }

    public function deleteCategory(Request $request)
    {
        $token =  $_GET['token'];
        $user = User::where('token',$token)->first();
        $merchant = Merchant::where('user_id',$user->id)->first();
        //$id = $request['id']
        $id = $request->input('id');

        // Need check permission match with token
        $category = Category::where('id',$id)->first();

        if(isset($category->menu->merchant->user->token) && $category->menu->merchant->user->token==$token) {
            if(count($category) > 0) {
                $categoryImages = CategoryImage::where('category_id',$category->id);
                try {
                    //if(!$categoryImages->get()->isEmpty) 
                    if($categoryImages->count())
                        $categoryImages->delete();
                    $category->delete();

                    return response()->json([
                        'result' => 1,
                    ],200);
                } catch(\Exception $e) {
                    return response()->json([
                        'result' => 0,
                        'messages' => $e->getMessage()
                    ],200);
                }
            }
        }
        return response()->json([
            'result' => 0,
            'message' => "Data error or you don't have permission to do this action",
        ],200);
    }

    public function listItems(Request $request)
    {
        //$token =  $_GET['token'];
        //$user = User::where('token',$token)->first();
        //$merchant = Merchant::where('user_id',$user->id)->first();
        $category_id = $request->input('category_id');

        $no_per_page = $request->get("item_per_page")? $request->get("item_per_page") : 10;

        $dishes = Category::find($category_id)->dishes()->with('addons', 'image', 'sizes')->paginate($no_per_page);
        
        return response()->json([
            'result' => 1,
            'data' => $dishes,
        ],200);
    }

}