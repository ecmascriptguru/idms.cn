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
 
		$file = $request::file('filefield');
		$extension = $file->getClientOriginalExtension();
		Storage::disk('local')->put($file->getFilename().'.'.$extension,  File::get($file));
		$entry = new Fileentry();
		$entry->mime = $file->getClientMimeType();
		$entry->original_filename = $file->getClientOriginalName();
		$entry->filename = $file->getFilename().'.'.$extension;
 
		if ($entry->save()) {
            return $this->response(['result' => 'success', 'file' => $entry]);
        } else {
            return $this->response(['result' => 'failure']);
        }
	}
}
