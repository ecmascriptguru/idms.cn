<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AppAdvertisements;

class FileEntry extends Model
{
    public function appAdvertisements() {
        return $this->hasMany(AppAdvertisement::class);
    }
}
