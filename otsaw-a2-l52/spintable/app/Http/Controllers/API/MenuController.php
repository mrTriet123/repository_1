<?php 
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input as Input;
use App\Repositories\Menu\MenuRepository;
use App\Http\Controllers\UploadFileController;

class MenuController extends ApiController{

    protected $statusCode = 200;
    protected $menuRepo;

    function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepo = $menuRepository;
        // $this->folder = "item_images";
    }

    public function getByMerchantID($id)
    {
        $result = $this->menuRepo->getByMerchantID($id);
        return $this->respond(['data' => $result]);
    }
} 