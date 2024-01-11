<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\FieldTypesResource;
use App\Http\Resources\Admin\GeneralSettingResource;
use App\Http\Requests\Admin\CreateGeneralSettingRequest;
use App\Http\Requests\Admin\UpdateGeneralSettingRequest;

class GeneralSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = GeneralSetting::all();
        return new GeneralSettingResource($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CreateGeneralSettingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateGeneralSettingRequest $request)
    {
        if ($request->file_type == config('constants.fieldTypes.file')) {
            $input = $request->validated();
            $resource = $request->file('value');
            $uploadFolder = 'settings';
            $resourceUploadedPath = $resource->store($uploadFolder, 'public');
            $input['value'] = $uploadFolder."/". basename($resourceUploadedPath);
            $data = GeneralSetting::create($input);
        } else {
            $data = GeneralSetting::create($request->validated());
        }
        if ($data) {
            return response()->json(['message' => __('apiMessage.createSetting')]);
        } else {
            return response()->json(['error' => __('apiMessage.errorMsg')], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(GeneralSetting $generalSetting)
    {
        return $generalSetting;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdateGeneralSettingRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGeneralSettingRequest $request, GeneralSetting $generalSetting)
    {
        $result = $generalSetting->update($request->validated());
        if ($result) {
            return new GeneralSettingResource($generalSetting);
        } else {
            return response()->json(['error' => __('apiMessage.errorMsg')], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GeneralSetting $generalSetting)
    {
        $generalSetting->delete();
        return response()->json(['message' => __('apiMessage.deleteSetting')]);
    }

    public function getFieldTypes() {
        $data =  config('constants.fieldTypes');
        return new FieldTypesResource($data);
    }

    public function bulkUpdate(Request $request) {
        $inputs = $request->all();
        foreach ($inputs as $k=> $input) {
            $gettype = GeneralSetting::where('title', $k)->value('field_type');
            $settingId = GeneralSetting::where('title', $k)->value('id');
            if ($gettype == config('constants.fieldTypes.file')) {
                $resource = $request->file($k);
                $uploadFolder = 'settings';
                $resourceUploadedPath = $resource->store($uploadFolder, 'public');
                $value = $uploadFolder."/". basename($resourceUploadedPath);
                GeneralSetting::where('id', $settingId)->update([
                    'value' => $value
                ]);
            } else {
                GeneralSetting::where('id', $settingId)->update([
                    'value' => $input
                ]);
            }
        }
        return response()->json(['message' => __('apiMessage.updateSetting')]);
    }

    public function emptyTables(Request $request)
    {
        $tables = $request->tables;
        foreach ($tables as $table) {
            DB::table($table)->delete();
        }
        return response()->json(['message' => __('apiMessage.success')]);
    }

}
