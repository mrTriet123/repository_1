<?php 
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input as Input;
use App\Repositories\Restaurant\RestaurantRepository;
use App\Http\Controllers\UploadFileController;
use App\RestaurantType;

class RestaurantController extends ApiController{

    protected $statusCode = 200;
    protected $r_restaurant;

    function __construct(RestaurantRepository $resRepository)
    {
        $this->r_restaurant = $resRepository;
        // $this->folder = "item_images";
    }

    public function index()
    {
        $restaurants = $this->r_restaurant->getAll();
        return $this->respondWithPagination($restaurants['total'], ['data' => $restaurants['data']]);
    }

    public function search($keyword)
    {
        $open_now = Input::get('open_now');

        $open_now_only = ($open_now == 1) ? TRUE : FALSE;

        $restaurants = $this->r_restaurant->searchBy('name', $keyword, $open_now_only);
        return $this->respondWithPagination($restaurants['total'], ['data' => $restaurants['data']]);
    }

    public function menu($id)
    {
        $menu = $this->r_restaurant->getMenu($id);

        return $this->respond(['data' => $menu]);
    }

    public function show($id)
    {
        $restaurant = $this->r_restaurant->getByRestaurantID($id);
        if ($restaurant) {
            return $this->respond(['data' => $restaurant]);
        }
        return $this->respondPostError('Restaurant does not exist.');
    }

    public function getResType(){

        $restaurant_type = RestaurantType::select('id','type')->get();

        return response()->json([
            'result' => 1,
            'data' => $restaurant_type
        ],200);
    }
} 