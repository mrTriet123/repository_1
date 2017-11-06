<?php

namespace App\Repositories\ActivityLog;


interface ActivityLogRepository
{
    public function recentNotifications($merchant_id);
    public function markAsRead($notification_id);
}