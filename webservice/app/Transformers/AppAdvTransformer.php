<?php

namespace App\Transformers;

use App\Models\AppAdvertisement;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Storage;

class AppAdvTransformer extends TransformerAbstract
{
    /**
     * Transform a operating company.
     *
     * @param  AppAdv $adv
     *
     * @return array
     */
    public function transform(AppAdvertisement $adv)
    {
        if ($adv->image) {
            return [
                'id' => $adv->id,
                'title' => $adv->title,
                'image_title' => $adv->image_title,
                'file_entry_id' => $adv->file_entry_id,
                'image_url' => Storage::url($adv->image->filename),
                'image' => $adv->image,
                'created_at' => $adv->created_at->toIso8601String(),
                'updated_at' => $adv->updated_at->toIso8601String(),
            ];
        } else {
            return [
                'id' => $adv->id,
                'title' => $adv->title,
                'image_title' => $adv->image_title,
                'file_entry_id' => $adv->file_entry_id,
                'image_url' => null,
                'image' => $adv->image,
                'created_at' => $adv->created_at->toIso8601String(),
                'updated_at' => $adv->updated_at->toIso8601String(),
            ];
        }
    }
}
