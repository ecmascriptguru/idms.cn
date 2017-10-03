<?php

namespace App\Transformers;

use App\Models\DeviceAdvertisement;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Storage;

class DeviceAdvTransformer extends TransformerAbstract
{
    /**
     * Transform a operating company.
     *
     * @param  AppAdv $adv
     *
     * @return array
     */
    public function transform(DeviceAdvertisement $adv)
    {
        if ($adv->file) {
            return [
                'id' => $adv->id,
                'name' => $adv->name,
                'file_entry_id' => $adv->file_entry_id,
                'from' => $adv->from,
                'to' => $adv->to,
                'title' => $adv->title,
                'url' => Storage::url($adv->file->filename),
                'file' => $adv->file,
                'created_at' => $adv->created_at->toIso8601String(),
                'updated_at' => $adv->updated_at->toIso8601String(),
            ];
        } else {
            return [
                'id' => $adv->id,
                'name' => $adv->name,
                'file_entry_id' => $adv->file_entry_id,
                'from' => $adv->from,
                'to' => $adv->to,
                'title' => $adv->title,
                'url' => null,
                'file' => $adv->file,
                'created_at' => $adv->created_at->toIso8601String(),
                'updated_at' => $adv->updated_at->toIso8601String(),
            ];
        }
    }
}
