<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OperatingCompany;

class Parameter extends Model
{
    protected $fillable = [
        'operating_company_id',
        'video_intercom_call_waiting_time',
        'adv_refresh_waiting_time',
        'device_connection_waiting_time',
        'direct_phone_call_waiting_time_limit',
        'password_input_waiting_time',
        'unit_number_length',
        'floor_length_number',
        'floor_length',
        'media_sound_volumn',
        'call_sound_volumn',
        'dial_ring_tones',
        'system_sound_volumn',
        'video_quality',
        'video_auto_adaption_network',
    ];

    public function operatingCompany() {
        return $this->belongsTo(OperatingCompany::class);
    }
}
