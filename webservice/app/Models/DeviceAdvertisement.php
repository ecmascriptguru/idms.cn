<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FileEntry;
use App\Models\OperatingCompany;

class DeviceAdvertisement extends Model
{
    protected $fillable = [
        'operating_company_id',
        'name',
        'from',
        'to',
        'file_entry_id',
        'title',
        'status',
    ];

    public function file() {
        return $this->belongsTo(FileEntry::class, 'file_entry_id');
    }

    public function operatngCompany() {
        return $this->belongsTo(OperatingCompany::class);
    }
}
