<?php

namespace App\Http\Controllers\API\Merchant;

use Illuminate\Http\Request;

use App\Addon;
use App\User;
use App\DishAddon;

use App\Http\Requests;
use App\Http\Controllers\API\ApiController;

class AddonController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$token =  ($request->get("token"))? $request->get("token") : false;
        $token = $request->get("token");
        $merchantId = $user = User::where('token',$token)->first()->merchant->id;

        $type =  ($request->get("type"))? $request->get("type") : false;

        if($type) {
            $type = urldecode($type);
            $addons = Addon::where([['type', $type], ['merchant_id',$merchantId]])
                   ->get();
        } else {
            $addons = Addon::where('merchant_id',$merchantId)
                   ->get();
        }
        
        return response()->json([
            'result' => 1,
            'data' => $addons
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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

        $addon = new Addon;
        $addon->name = $request->input('name');
        $addon->merchant_id = $merchantId;
        $addon->type = urldecode($request->input('type'));
        $addon->price = ($addon->type=="Pay Ones")? (float)$request->input('price'): 0;

        try {
            if($addon->save()) {
                return response()->json([
                    'result' => 1,
                    'id'    =>  $addon->id
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $id = $request->input('id');
        $addon = Addon::find($id);
        if(!(empty((array)$addon))) {
            $addon->name = $request->input('name');
            $addon->type = urldecode($request->input('type'));
            $addon->price = ($addon->type=="Pay Ones")? (float)$request->input('price'): 0;

            try {
                if($addon->save()) {
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
        } else {
            return response()->json([
                'result' => 0
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
        //$addon = Addon::find($id);
        //if(!(empty((array)$addon))) {
            try {
                $dishAddons = DishAddon::where('addon_id',$id);
                if($dishAddons->count())
                    $dishAddons->delete();

                //if($addon>delete()) {
                if(Addon::destroy($id)) {
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
        /*
        } else {
            return response()->json([
                'result' => 0
            ],200);
        }
        */
    }
}
