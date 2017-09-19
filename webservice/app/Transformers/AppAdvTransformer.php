<?php

namespace App\Transformers;

use App\Models\AppAdvertisement;
use League\Fractal\TransformerAbstract;

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
        return [
            'id' => $adv->id,
            'title' => $adv->title,
            'image_title' => $adv->image_title,
            'file_entry_id' => $adv->file_entry_id,
            'image_url' => "",
            'image' => $adv->image,
            'created_at' => $adv->created_at->toIso8601String(),
            'updated_at' => $adv->updated_at->toIso8601String(),
        ];
    }
}
