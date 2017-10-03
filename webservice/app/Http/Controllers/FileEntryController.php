<?php

namespace App\Http\Controllers;

use Request;
use App\Models\FileEntry;

use App\Transformers\FileEntryTransformer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;

class FileEntryController extends ApiController
{
    public function index()
	{
		$entries = FileEntry::all();
        
        return $this->response(
            $this->transform->collection($entries, new FileEntryTransformer)
        );
	}
 
	public function add(Request $request) {
 
		$file = $request::file('file');
		$extension = $file->getClientOriginalExtension();
		Storage::disk('public')->put($file->getFilename().'.'.$extension,  File::get($file));
		$entry = new FileEntry();
		$entry->mime = $file->getClientMimeType();
		$entry->original_filename = $file->getClientOriginalName();
		$entry->filename = $file->getFilename().'.'.$extension;
 
		if ($entry->save()) {
            return $this->response(['result' => 'success', 'file' => $this->transform->item($entry, new FileEntryTransformer)]);
        } else {
            return $this->response(['result' => 'failure']);
        }
	}
}
