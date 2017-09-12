<?php

namespace App\Models;

use App\Models\FileEntry;

use Illuminate\Database\Eloquent\Model;

class AppAdvertisement extends Model
{
    protected $fillable = [
        'operating_company_id',
        'title',
        'file_entry_id',
        'image_title',
    ];

    public function image() {
        return $this->belongsTo(FileEntry::class);
    }
}
