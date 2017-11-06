<?php

namespace App\Http\Controllers\API\Merchant;

use Illuminate\Http\Request;

use App\Dish;
use App\DishCategory;
use App\DishAddon;
use App\DishImage;
use App\DishSize;
use App\User;
use App\Addon;
use App\Category;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DishController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $token = $request->get("token");
        $merchantId = $user = User::where('token',$token)->first()->merchant->id;

        $dish = new Dish;
        $dish->name = $request->input('name');
        $dish->description = $request->input('description');
        $dish->spicy_levels = $request->input('spicy_level');
        $dish->online_order_inventory = $request->input('online_order_inventory');

        try {
            if($dish->save()) {
                //$categories = json_decode($request->input('category_id'));
                $categories = explode(",",urldecode($request->input('category_id')));
                foreach($categories as $category) {
                    $dishCategory = new DishCategory;
                    $dishCategory->dish_id = $dish->id;
                    $dishCategory->category_id = $category;
                    $dishCategory->save();
                }

                //$addons = json_decode($request->input('addons'));
                $addons = explode(",",urldecode($request->input('addons')));
                foreach($addons as $addon) {
                    $dishAddon = new DishAddon;
                    $dishAddon->dish_id = $dish->id;
                    $dishAddon->addon_id = $addon;
                    $dishAddon->save();
                }

                $sizes = json_decode(urldecode($request->input('sizes')));
                foreach($sizes->records as $size) {
                    $dishSize = new DishSize;
                    $dishSize->dish_id = $dish->id;
                    $dishSize->size = $size->size;
                    $dishSize->price = $size->price;
                    $dishSize->save();
                }

                foreach($_FILES as $file)
                {
                    $uploaddir = config('app.uploadfolder'). $dish->dishFolder;
                    if (!is_dir($uploaddir)) {
                        //mkdir($uploaddir, 0777);
                        if (!mkdir($uploaddir, 0777, true)) {
                            return response()->json([
                                'result' => 0,
                                'messages' => 'Failed to create folders'
                            ],200);
                        }
                    }

                    if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name'])))
                    {
                        //$files[] = $uploaddir .$file['name'];
                        $path = $uploaddir .$file['name'];
                        $dishImage = new DishImage;
                        $dishImage->dish_id = $dish->id;
                        $dishImage->name = rtrim($file['name'], '.jpg');
                        $dishImage->path = $path;
                        $dishImage->name_origin = $file['name'];
                        $dishImage->save();
                    }
                    else
                    {
                        return response()->json([
                            'result' => 0,
                            'messages' => 'Failed to upload file'
                        ],200);
                    }
                }

                return response()->json([
                    'result' => 1,
                    'id'    =>  $dish->id
                ],200);
            } else {
                return response()->json([
                    'result' => 0
                ],200);
            }
        } catch(\Exception $e){
            return response()->json([
                'result' => 0,
                'messages' => $e->getMessage()
            ],200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {        
        $dishId = $request->get("dish_id");
        $dish = Dish::find($dishId);

        if(!(empty((array)$dish))) {
            $returnData = $dish->toArray();
            $returnData['spicy_levels'] = $dish->spicyLevelData[$dish->spicy_levels];
            $returnData['spicy_level_id'] = $dish->spicy_levels;
            $returnData = array_diff_key($returnData, ['created_at'=>0, 'updated_at'=>0]);

            $categories = [];
            //foreach($dish->dish_categories as $category) {
            foreach($dish->categories as $category) {
                $categories[] = collect($category->toArray())->only(['id', 'name']);
            }
            $returnData['categories'] = $categories;
            $returnData['add_ons']      = $dish->addons->toArray();

            if($dish->image) {
                if (strpos($dish->image->path, 'http://') !== false || strpos($dish->image->path, 'https://') !== false) {
                    $returnData['image'] = $dish->image->path;
                } else {
                    $relativeUrl = strpos($dish->image->path,'.')===0? ltrim($dish->image->path, '.'): $dish->image->path;
                    $returnData['image']    = 'http://'. $_SERVER['HTTP_HOST'].$relativeUrl;
                }
            } else {
                $returnData['image'] = "";
            }

            $returnData['sizes']        = $dish->sizes->toArray();

            return response()->json([
                'result' => 1,
                'data' => $returnData
            ],200);
        } else {
            return response()->json([
                'result' => 0
            ],200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $token = $request->get("token");
        $merchantId = $user = User::where('token',$token)->first()->merchant->id;

        $id = $request->input('id');
        $dish = Dish::find($id);
        // Need check this dish belong to this login merchent (via token)

        $dish->name = $request->input('name');
        $dish->description = $request->input('description');
        $dish->spicy_levels = $request->input('spicy_level');
        $dish->online_order_inventory = $request->input('online_order_inventory');

        try {
            if($dish->save()) {
                //$categories = json_decode($request->input('category_id'));
                $dishCategories = DishCategory::where('dish_id',$dish->id);
                if($dishCategories->count())
                    $dishCategories->delete();

                $categories = explode(",",urldecode($request->input('category_id')));
                foreach($categories as $category) {
                    $dishCategory = new DishCategory;
                    $dishCategory->dish_id = $dish->id;
                    $dishCategory->category_id = $category;
                    $dishCategory->save();
                }

                //$addons = json_decode($request->input('addons'));
                $dishAddons = DishAddon::where('dish_id',$dish->id);
                if($dishAddons->count())
                    $dishAddons->delete();

                $addons = explode(",",urldecode($request->input('addons')));
                foreach($addons as $addon) {
                    $dishAddon = new DishAddon;
                    $dishAddon->dish_id = $dish->id;
                    $dishAddon->addon_id = $addon;
                    $dishAddon->save();
                }

                $dishSizes = DishSize::where('dish_id',$dish->id);
                if($dishSizes->count())
                    $dishSizes->delete();

                $sizes = json_decode(urldecode($request->input('sizes')));

                foreach($sizes->records as $size) {
                    $dishSize = new DishSize;
                    $dishSize->dish_id = $dish->id;
                    $dishSize->size = $size->size;
                    $dishSize->price = $size->price;
                    $dishSize->save();
                }

                if(count($_FILES)) {
                //if($request->hasFile('image')){
                    $dishImages = DishImage::where('dish_id',$dish->id);
                    if($dishImages->count())
                        $dishImages->delete();
                    // Need remove old images first
                    foreach($_FILES as $file)
                    {
                        $uploaddir = config('app.uploadfolder'). $dish->dishFolder;
                        if (!is_dir($uploaddir)) {
                            //mkdir($uploaddir, 0777);
                            if (!mkdir($uploaddir, 0777, true)) {
                                return response()->json([
                                    'result' => 0,
                                    'messages' => 'Failed to create folders'
                                ],200);
                            }
                        }

                        if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name'])))
                        {
                            //$files[] = $uploaddir .$file['name'];
                            $path = $uploaddir .$file['name'];
                            $dishImage = new DishImage;
                            $dishImage->dish_id = $dish->id;
                            $dishImage->name = rtrim($file['name'], '.jpg');
                            $dishImage->path = $path;
                            $dishImage->name_origin = $file['name'];
                            $dishImage->save();
                        }
                        else
                        {
                            return response()->json([
                                'result' => 0,
                                'messages' => 'Failed to upload file'
                            ],200);
                        }
                    }
                }

                return response()->json([
                    'result' => 1,
                    'id'    =>  $dish->id
                ],200);
            } else {
                return response()->json([
                    'result' => 0
                ],200);
            }
        } catch(\Exception $e){
            return response()->json([
                'result' => 0,
                'messages' => $e->getMessage()
            ],200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id');
        try {
            $dishCategories = DishCategory::where('dish_id',$id);
            if($dishCategories->count())
                $dishCategories->delete();

            $dishSizes = DishSize::where('dish_id',$id);
            if($dishSizes->count())
                $dishSizes->delete();

            $dishAddons = DishAddon::where('dish_id',$id);
            if($dishAddons->count())
                $dishAddons->delete();

            $dishImages = DishImage::where('dish_id',$id);
            if($dishImages->count())
                $dishImages->delete();

            //if($addon>delete()) {
            if(Dish::destroy($id)) {
                return response()->json([
                    'result' => 1
                ],200);
            } else {
                return response()->json([
                    'result' => 0
                ],200);
            }
        } catch(\Exception $e){
            return response()->json([
                'result' => 0
            ],200);
        }
    }

    public function search(Request $request)
    {
        $token = $request->get("token");
        //$user       = User::where('token',$token)->first();
        $categories   = User::where('token',$token)->first()->merchant->menu->categories;
        
        $no_per_page = $request->get("item_per_page")? $request->get("item_per_page") : 10;
        $keyword = $request->get("keyword")? $request->get("keyword") : '';

        $dishIds = [];
        foreach($categories as $category) {
            foreach ($category->dishes as $dish) {
                if(!in_array($dish->id, $dishIds)) {
                    $dishIds[] = $dish->id;
                }
            }
        }

        $returnData = Dish::whereIn('id', $dishIds)
                        ->where('name', 'like', '%'.$keyword.'%')
                        ->with('image')->paginate($no_per_page);

        return response()->json([
            'result' => 1,
            'data' => $returnData,
        ],200);
    }
}
