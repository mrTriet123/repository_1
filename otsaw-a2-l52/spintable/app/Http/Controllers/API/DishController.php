<?php 
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input as Input;
use App\Repositories\Dish\DishRepository;
use App\Http\Controllers\UploadFileController;

class DishController extends ApiController{

    protected $statusCode = 200;
    protected $r_dish;

    function __construct(DishRepository $dishRepo)
    {
        $this->r_dish = $dishRepo;
        // $this->folder = "item_images";
    }

    public function index()
    {
        $restaurants = $this->r_restaurant->getAll();
        return $this->respondWithPagination($restaurants['total'], ['data' => $restaurants['data']]);
    }

    public function search($keyword)
    {
        $restaurants = $this->r_restaurant->searchBy('name', $keyword);
        return $this->respondWithPagination($restaurants['total'], ['data' => $restaurants['data']]);
    }

    public function menu($id)
    {
        // $menu = $this->r_restaurant->getMenu($id);

        $menu = $this->dummy_menu();

        return $this->respond(['data' => $menu]);
    }

    public function shows($id)
    {
        $menu = $this->dummy_dish();

        return $this->respond(['data' => $menu]);
    }

    public function show($id, $dish_id)
    {
        $dish = $this->r_dish->getDishInfo($dish_id);
        if ($dish) {
            return $this->respond(['data' => $dish]);
        }
        return $this->respondPostError('Dish does not exist.');
    }

    private function dummy_dish()
    {
        $menu = array(
                        "dish_id" => 1,
                        "name" => "Chicken Rice",
                        "description" => "Chicken and Rice",
                        "images" => array(
                            "0" => array(
                                    "image_id" => 1,
                                    "url" => "https://www.google.com.sg/logos/doodles/2017/abdul-sattar-edhis-89th-birthday-5757526734798848-hp.jpg"
                                )
                        ),
                        "addons" => array(
                            "0" => array(
                                "addon_id" => 1,
                                "name" => "Extra cheese",
                                "price" => 20.00
                            )
                        ),
                        "sizes" => array(
                            "0" => array(
                                "size_id" => 1,
                                "name" => "Small",
                                "price" => 10.00
                            ),
                            "1" => array(
                                "size_id" => 2,
                                "name" => "Medium",
                                "price" => 20.00
                            ),
                            "2" => array(
                                "size_id" => 3,
                                "name" => "Large",
                                "price" => 30.00
                            )
                        )
        );

        return $menu;
    }
} 