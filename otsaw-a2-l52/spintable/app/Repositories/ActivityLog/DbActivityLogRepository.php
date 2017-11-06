<?php

namespace App\Repositories\ActivityLog;

use App\ActivityLog;
use App\Repositories\DbRepository;
use Carbon\Carbon;

class DbActivityLogRepository extends DbRepository implements ActivityLogRepository
{
    public function __construct()
    {
    }

    public function recentNotifications($merchant_id)
    {
        $activity_logs = ActivityLog::where('merchant_id', $merchant_id)
        							->where('is_read', 0)
        							->orderBy('created_at', 'DESC')
        							->paginate(5);

        $i = 0;
        if ($activity_logs->all()){
            foreach ($activity_logs->all() as $log) {
                $tmpRes[$i++] = $this->formatLogInfo($log);
            }
        } else {
            $tmpRes = $activity_logs->all();
        }
        
        $data = array('total' => $activity_logs, 'data' => $tmpRes);
        return $data;
    }

    private function formatLogInfo($log)
    {
        $data = [];
        $data['notification_id'] = $log->id;
        $data['merchant_id'] = $log->merchant_id;
        $data['reservable_id'] = $log->reservable_id;
        $data['description'] = $log->description;
        $data['created_at'] = $log->created_at->diffForHumans();
        $data['updated_at'] = $log->updated_at->diffForHumans();
        return $data;
    }

    public function markAsRead($id)
    {
        $log = ActivityLog::find($id);
        $log->is_read = 1;
        $log->save();
        return $log;
    }

}