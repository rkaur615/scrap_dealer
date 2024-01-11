<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Admin;
use App\Models\AdminSection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddAdminSectionRequest;
use App\Http\Resources\Admin\GetAdminSectionResource;
use App\Http\Requests\Admin\UpdateAdminSectionRequest;

class AdminSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = AdminSection::orderBy('id', 'desc')->paginate(10);
        return GetAdminSectionResource::collection($sections);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddAdminSectionRequest $request)
    {
        $section = AdminSection::create($request->validated());
        if ($section) {
            return response()->json(['message' => __('apiMessage.createSection')]);
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
    public function show(AdminSection $section)
    {
        return new GetAdminSectionResource($section);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdminSectionRequest $request,AdminSection $section)
    {
        $section->update($request->validated());
        return response()->json(['message' => __('apiMessage.updateSection')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdminSection $section)
    {
        $section->delete();
        return response()->json(['message' => __('apiMessage.deleteSection')]);
    }
}
