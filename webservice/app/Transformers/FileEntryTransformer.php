<?php

namespace App\Transformers;

use App\Models\FileEntry;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Storage;

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
        $url = Storage::url($file->filename);
        return [
            'id' => $file->id,
            'filename' => $file->filename,
            'mime' => $file->mime,
            'original_filename' => $file->original_filename,
            'url' => $url,
            'created_at' => $file->created_at->toIso8601String(),
            'updated_at' => $file->updated_at->toIso8601String(),
        ];
    }
}
