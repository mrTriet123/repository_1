<?php 
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input as Input;
use App\Repositories\ActivityLog\ActivityLogRepository;
use Helpers;

class ActivityLogController extends ApiController{

    protected $statusCode = 200;
    protected $r_activitylog;

    function __construct(ActivityLogRepository $actRepo)
    {
        $this->r_activitylog = $actRepo;
    }

    public function recentNotifications($merchant_id)
    {
        $notifications = $this->r_activitylog->recentNotifications($merchant_id);
        return $this->respondWithPagination($notifications['total'], ['data' => $notifications['data']]);
    }

    public function markAsRead($merchant_id, $notification_id)
    {
        $result = $this->r_activitylog->markAsRead($notification_id);

        if ($result) {
            $notifications = $this->r_activitylog->recentNotifications($merchant_id);
            \Event::fire(new \App\Events\RefreshNotifications($this->addPagination($notifications['total'], ['data' => $notifications['data']])));
        }

        return $this->respond(['data' => $result]);
    }
} 