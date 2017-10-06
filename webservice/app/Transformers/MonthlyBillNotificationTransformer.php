<?php

namespace App\Transformers;

use App\Models\MonthlyBillNotification;
use League\Fractal\TransformerAbstract;

class MonthlyBillNotificationTransformer extends TransformerAbstract
{
    /**
     * Transform a notification.
     *
     * @param  MonthlyBillNotification $notification
     *
     * @return array
     */
    public function transform(MonthlyBillNotification $notification)
    {
        return [
            'id' => $notification->id,
            'date' => $notification->date,
            'bills_count' => $notification->bills->count(),
            'is_released' => $notification->is_released,
            'created_at' => $notification->created_at->toIso8601String(),
            'updated_at' => $notification->updated_at->toIso8601String(),
        ];
    }
}
