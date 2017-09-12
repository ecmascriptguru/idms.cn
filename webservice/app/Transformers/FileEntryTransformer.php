<?php

namespace App\Transformers;

use App\Models\FileEntry;
use League\Fractal\TransformerAbstract;

class FileEntryTransformer extends TransformerAbstract
{
    /**
     * Transform a category.
     *
     * @param  FileEntry $category
     *
     * @return array
     */
    public function transform(FileEntry $file)
    {
        return [
            'id' => $file->id,
            'filename' => $file->filename,
            'mime' => $file->mime,
            'original_filename' => $file->original_filename,
            'url' => '/storage/' . $file->filename,
            'created_at' => $file->created_at->toIso8601String(),
            'updated_at' => $file->updated_at->toIso8601String(),
        ];
    }
}
