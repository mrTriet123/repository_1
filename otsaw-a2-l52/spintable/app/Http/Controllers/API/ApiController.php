<?php namespace App\Http\Controllers\API;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Firebase\Firebase;


class ApiController extends BaseController {
	protected $statusCode;
    private $fireBase;


//    public function __construct()
//
//    {
//        $this->fireBase = Firebase::initialize(env('FIREBASE_URL'), env('FIREBASE_SECRET'));
//        $this->bufferToNextBooking = 50;
//        $this->late = 15;
//    }

	public function getStatusCode()
	{
		return $this->statusCode;
	}

	public function setStatusCode($statusCode)
	{
		$this->statusCode = $statusCode;
		return $this;
	}

	public function respond($data, $headers = [])
	{
		return response()->json($data, $this->getStatusCode(), $headers);
	}

	public function respondWithPagination($items, $data)
	{
		$data = array_merge($data, [
			'paginator' => [
                'total_count' => $items->total(),
                'total_page'  => ceil($items->total() / $items->perPage()),
                'current_page'=> $items->currentPage(),
                'limit'       => $items->perPage()
            ]
		]);
		return $this->respond($data);
	}
	
	public function respondWithError($message, $dev_message = "")
	{
		return $this->respond([
			'error' => [
				'message' => $message,
				'dev_message' => $dev_message,
				'status_code' => $this->getStatusCode()
			]
		]);
	}

	public function respondWithValidationError($message, $vadateMessage)
	{
		return $this->respond([
			'error' => [
				'message' => $message,
				'status_code' => $this->getStatusCode(),
				'validateError' => $vadateMessage
			]
		]);
	}

	/**
	 * @param $message
	 * @return mixed
	 */

	public function respondCreated($message, $data)
	{
		return $this->setStatusCode(201)->respond([
			'message' => $message,
			'data' => $data
		]);
	}

	public function respondUpdated($message, $data)
	{
		return $this->setStatusCode(201)->respond([
			'message' => $message,
			'data' => $data
		]);
	}

	public function respondDeleted($message, $data)
	{
		return $this->setStatusCode(201)->respond([
			'message' => $message,
			'data' => $data
		]);
	}

	public function respondNoRecord($message)
	{
		return $this->setStatusCode(200)->respond([
			'message' => $message,
			'data' => ""
		]);
	}


	/**
	 * @param $message
	 * @return mixed
	 */
	public function respondNotFound($message = 'Not found!')
	{
		return $this->setStatusCode(404)->respondWithError($message);
	}
	
	/**
	 * @param $message
	 * @return mixed
	 */
	public function respondPostError($message = 'Validation Error!')
	{
		return $this->setStatusCode(422)->respondWithError($message);
	}

	/**
	 * @param $message
	 * @return mixed
	 */
	public function respondPostValidationError($vadateMessage)
	{
		return $this->setStatusCode(422)->respondWithValidationError('Validation Error!', $vadateMessage);
	}

	/**
	 * @param $message
	 * @return mixed
	 */

	public function respondInternalError($message = 'Internal Error!')
	{
		return $this->setStatusCode(500)->respondWithError($message);
	}

	/**
	 * @param $message
	 * @return mixed
	 */

	public function respondInvalid($message = 'Invalid!')
	{
		return $this->setStatusCode(400)->respondWithError($message);
	}


	public function addPagination($items, $data)
	{
		$data = array_merge($data, [
			'paginator' => [
                'total_count' => $items->total(),
                'total_page'  => ceil($items->total() / $items->perPage()),
                'current_page'=> $items->currentPage(),
                'limit'       => $items->perPage()
            ]
		]);
		return $data;
	}


    /**
     * FireBase
     */
//    private function fbMethods($method,$node,$data = null)
//    {
//
//        switch($method)
//        {
//            case 'GET':
//                $getData = $this->fireBase->get($node);
//                return isset($getData)? $getData : $this->respondNotFound('Node not found!');
//                break;
//
//            case 'SET':
//                $setData = $this->fireBase->set($node,$data);
//                return isset($setData)? $setData : $this->respondPostError();
//                break;
//
//            case 'PUSH':
//                $pushData = $this->fireBase->push($node,$data);
//                return isset($pushData)? $pushData : $this->respondPostError();
//                break;
//
//            case 'EDIT':
//                $updateData = $this->fireBase->update($node,$data);
//                return isset($updateData)? $updateData : $this->respondPostError();
//                break;
//
//            case 'DELETED':
//                $deleteData = $this->fireBase->delete($node);
//                return isset($deleteData)? $deleteData : $this->respondPostError();
//                break;
//
//        }
//
//    }
//    public function fbGet($node)
//    {
//       return (is_null($node) ? $this->respondInvalid() : $this->fbMethods('GET',$node));
//    }
//
//    public function fbSet($node, $data = [])
//    {
//        return (is_null($node) || is_null($data) ? $this->respondInvalid() : $this->fbMethods('SET',$node,$data));
//    }
//
//    public function fbPush($node = null, $data = [])
//    {
//        return (is_null($node) || is_null($data) ? $this->respondInvalid() : $this->fbMethods('PUSH',$node,$data));
//    }
//
//    public function fbUpdate($node = null, $data = [])
//    {
//    	$updateData = $this->fireBase->update($node,$data);
//        return (is_null($node) || is_null($data) ? $this->respondInvalid() : $this->fbMethods('EDIT',$node,$data));
//    }
//
//    public function fbDelete($node = null)
//    {
//        return (is_null($node) ? $this->respondInvalid() : $this->fbMethods('DELETED',$node));
//    }
//
//    public static function fbUpdate2($node = null, $data = [])
//    {
//    	$_this = new self;
//    	$updateData = $_this->fireBase->update($node,$data);
//        return (is_null($node) || is_null($data) ? $_this->respondInvalid() : $_this->fbMethods('EDIT',$node,$data));
//    }
//
//    public static function fbSet2($node, $data = [])
//    {
//    	$_this = new self;
//        return (is_null($node) || is_null($data) ? $_this->respondInvalid() : $_this->fbMethods('SET',$node,$data));
//    }





}