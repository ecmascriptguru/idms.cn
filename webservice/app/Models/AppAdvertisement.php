<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FileEntry;
use App\Models\OperatingCompany;

class AppAdvertisement extends Model
{
    protected $fillable = [
        'operating_company_id',
        'title',
        'file_entry_id',
        'image_title',
    ];

    public function image() {
        return $this->belongsTo(FileEntry::class, 'file_entry_id');
    }

    public function operatngCompany() {
        return $this->belongsTo(OperatingCompany::class);
    }
}
