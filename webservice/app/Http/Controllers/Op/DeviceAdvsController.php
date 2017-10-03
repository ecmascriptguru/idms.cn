<?php

namespace App\Http\Controllers\Op;

use Illuminate\Support\Facades\Auth;
use App\Models\DeviceAdvertisement;
use App\Models\FileEntry;
use Illuminate\Http\Request;
use App\Http\Requests\DeviceAdvRequest as DeviceAdvertisementRequest;
use App\Transformers\DeviceAdvTransformer as DeviceAdvertisementTransformer;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;

class DeviceAdvsController extends ApiController
{
    public function isOperatingCompanyAdmin() {
        $user = Auth::guard('api')->user();
        // $role = $user->role;

        return ($user && $user->role) ? $user->role->id === 2 : false;
    }

    private function getOperatingCompanyId() {
        $user = Auth::guard('api')->user();
        
        if ($user && $user->organization) {
            return $user->organization->id;
        } else {
            return null;
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sort = $this->getSort();
        $order = $this->getOrder();
        $limit = $this->getLimit();

        $deviceAdvertisements = DeviceAdvertisement::orderBy($sort, $order)
            ->where(['operating_company_id' => $this->getOperatingCompanyId()])->paginate($limit);

        return $this->response(
            $this->transform->collection($deviceAdvertisements, new DeviceAdvertisementTransformer)
        );
    }

    public function fullList()
    {
        return $this->response(
            $this->transform->collection(DeviceAdvertisement::where(['operating_company_id' => $this->getOperatingCompanyId()])->get(), new DeviceAdvertisementTransformer)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeviceAdvertisementRequest $request)
    {
        if ($this->isOperatingCompanyAdmin()) {
            $param = $request->only('title', 'image_title', 'file_entry_id');
            $param['operating_company_id'] = $this->getOperatingCompanyId();
            DeviceAdvertisement::create($param);
            
            return $this->response(['result' => 'success']);
        } else { 
            return $this->response(['result' => 'failure']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $deviceAdvertisement = DeviceAdvertisement::find($id);

        if (! $deviceAdvertisement) {
            return $this->responseWithNotFound('DeviceAdvertisement not found');
        }

        return $this->response(
            $this->transform->item($deviceAdvertisement, new DeviceAdvertisementTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DeviceAdvertisementRequest $request, $id)
    {
        $adv = DeviceAdvertisement::find($id);

        if (! $adv) {
            return $this->responseWithNotFound('DeviceAdvertisement not found');
        }

        if ($this->isOperatingCompanyAdmin()) {
            $adv->title = $request->get('title');
            $adv->image_title = $request->get('image_title');
            if ($adv->image && $adv->file_entry_id != $request->get('file_entry_id')) {
                $adv->file_entry_id = $request->get('file_entry_id');
                Storage::disk('public')->delete($adv->image->filename);
                $adv->save();
                $adv->image->delete();
                return $this->response(['result' => 'success', 'msg' => 'Image File removed.']);
            } else {
                $adv->file_entry_id = $request->get('file_entry_id');
                $adv->save();
                return $this->response(['result' => 'success']);
            }
        } else {
            return $this->response(['result' => 'failure']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $adv = DeviceAdvertisement::find($id);

        if (! $adv) {
            return $this->responseWithNotFound('DeviceAdvertisement not found');
        }

        if ($this->isOperatingCompanyAdmin()) {
            if ($adv->file_entry_id) {
                $entry = $adv->image;
                $adv->delete();
                Storage::delete($entry->filename);
                $entry->delete();
            } else {
                $adv->delete();
            }
            
            return $this->response(['result' => 'success']);
        } else {
            return $this->response(['result' => 'failure']);
        }
    }
}
