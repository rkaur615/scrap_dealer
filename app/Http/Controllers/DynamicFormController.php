<?php

namespace App\Http\Controllers;

use App\Http\Resources\DynamicFormCollection;
use App\Http\Resources\DynamicFormResource;
use App\Http\Resources\SuccessResource;
use App\Models\DynamicForm;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class DynamicFormController extends Controller
{
    //z

    public function create(Request $request): JsonResponse
    {
        $fields = json_decode($request->fields, true);
        $title = $request->title;

        DynamicForm::create(['title'=>$title, 'form_fields'=>json_encode($request->form_fields)]);
        return response()->json([
            'message'=>__('apiFormMessages.formCreated'),
            'status'=> 'success'
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $id = $request->editId;
        $form = DynamicForm::find($id);
        $form->form_fields = json_encode($request->form_fields);
        $form->save();
        return response()->json([
            'message'=>__('apiFormMessages.formUpdated'),
            'status'=> 'success'
        ]);
    }

    public function list(): JsonResource
    {
        $forms = DynamicForm::select(['id','title'])->orderBy('id', 'desc')->paginate();

        return new DynamicFormCollection($forms);
    }

    public function listLikedToCat($catid): JsonResource
    {
        $forms = DynamicForm::select(['id','title'])->with('categories')->paginate();

        return new DynamicFormCollection($forms);
    }

    public function getFormFields(DynamicForm $form): JsonResource
    {
        return new DynamicFormResource($form);
    }

    public function deleteForm(DynamicForm $form): JsonResponse
    {
        $form->delete();
        return response()->json([
            'message'=>__('apiFormMessages.formDeleted'),
            'status'=> 'success'
        ]);
    }
}
