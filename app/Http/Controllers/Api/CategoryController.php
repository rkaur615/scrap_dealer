<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Http\Requests\CreateCategoryRequest;
use App\Models\Category;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryDynamicFormCollection;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CatTypesResource;
use App\Models\CategoryDynamicForm;
use App\Models\DynamicForm;
use App\Models\UserCategory;
use phpDocumentor\Reflection\Types\Null_;

class CategoryController extends Controller
{
    public function getCategoryByType(Request $request)
    {
        $categoryType = $request->get("type");

        $categories = Category::select(['id', 'title','parent_id', 'image_path'])
        ->where('category_type', $categoryType)
        ->whereNull('parent_id')
        ->withCount('childrenCat')
        ->get()
        ->toArray();

        //return new CategoryCollection($categories);
        $categoriesList = $this->getAllCategoriesForDropDown(
            $categories,
            0,
            $categoryType
        );
        return new CategoryCollection($categoriesList);
    }
    public function categoriesForProduct(Request $request)
    {
        $categoryType = $request->get("type");

        $categories = Category::select(['id', 'title','parent_id'])
        ->where('category_type', 1)
        ->whereNull('parent_id')
        ->withCount('childrenCat')
        ->get()
        ->toArray();

        //return new CategoryCollection($categories);
        $categoriesList = $this->getFilteredCategoriesForDropDown(
            $categories,
            0,
            $categoryType
        );
        return new CategoryCollection($categoriesList);
    }
    public function getScrapCategoryList()
    {
        $categoryType = config('constants.category.ScrapCategory');
        $categories = Category::select(['id', 'title','parent_id', 'image_path'])
        //->where('category_type', $categoryType)
        ->where('category_type', $categoryType)
        ->whereNull('parent_id')->withCount('childrenCat')->get()->toArray();

        $categoriesList = $this->getFilteredCategoriesForDropDown(
            $categories,
            0,
            $categoryType
        );
        return new CategoryCollection($categoriesList);
    }
    public function getCharitableCategoryList()
    {
        $categoryType = config('constants.category.CharitableCategory');
        $categories = Category::select(['id', 'title','parent_id', 'image_path'])
        ->where('category_type', $categoryType)
        ->whereNull('parent_id')->withCount('childrenCat')->get()->toArray();
        $categoriesList = $this->getAllCategoriesForDropDown(
            $categories,
            0,
            $categoryType
        );
        return new CategoryCollection($categoriesList);
    }
    public function getServiceCategoryList(Request $request)
    {
        $service_name = isset($request->service_name) ? $request->service_name : '';
        $categoryType = config('constants.category.ServiceCategory');
        if (empty($service_name) || $service_name == 'null') {
            $categories = Category::select(['id', 'title','parent_id', 'image_path'])
            //->where('category_type', $categoryType)
            ->where('category_type', $categoryType)
            ->whereNull('parent_id')->withCount('childrenCat')->get()->toArray();
            $categoriesList = $this->getAllCategoriesForDropDown(
                $categories,
                0,
                $categoryType
            );
            return new CategoryCollection($categoriesList);
        } else {
            $categories = Category::select(['id', 'title','parent_id'])
            //->where('category_type', $categoryType)
            ->where('category_type', $categoryType)
            ->where('title', $service_name)
            ->get()->toArray();
            $categoriesList = $this->getAllCategoriesForDropDown(
                $categories,
                0,
                $categoryType
            );
            if (!empty($categoriesList)) {
                return new CategoryCollection($categoriesList);
            } else {
                return response()->json([
                    'message'=>__('apiMessage.notExist'),
                ]);
            }
        }
    }
    public function getRecyclerCategoryList()
    {
        $categoryType = config('constants.category.RecyclerCategory');
        $categories = Category::select(['id', 'title','parent_id', 'image_path'])
        //->where('category_type', $categoryType)
        ->where('category_type', $categoryType)
        ->whereNull('parent_id')->withCount('childrenCat')->get()->toArray();
        $categoriesList = $this->getAllCategoriesForDropDown(
            $categories,
            0,
            $categoryType
        );
        return new CategoryCollection($categoriesList);
    }
    public function getProductCategoryList(Request $request)
    {
        $product_name = isset($request->product_name) ? $request->product_name : '';
        $categoryType = config('constants.category.ProductCategory');
        if (empty($product_name) || $product_name == 'null') {
            $categories = Category::select(['id', 'title','parent_id', 'image_path'])
            //->where('category_type', $categoryType)
            ->where('category_type', $categoryType)
            ->whereNull('parent_id')->withCount('childrenCat')->get()->toArray();
            $categoriesList = $this->getAllCategoriesForDropDown(
                $categories,
                0,
                $categoryType
            );
            return new CategoryCollection($categoriesList);
        } else {
            $categories = Category::select(['id', 'title','parent_id'])
            //->where('category_type', $categoryType)
            ->where('category_type', $categoryType)
            ->where('title', $product_name)
            ->get()->toArray();
            $categoriesList = $this->getAllCategoriesForDropDown(
                $categories,
                0,
                $categoryType
            );
            if (!empty($categoriesList)) {
                return new CategoryCollection($categoriesList);
            } else {
                return response()->json([
                    'message'=>__('apiMessage.notExist'),
                ]);
            }
        }
    }
    public function getAllCategoriesForDropDown($allCategories, $margin_left, $categoryType) {
        $resultCategories = [];
        foreach ($allCategories as $cat) {
            $dash = '';
            if ($margin_left > 0) {
                for ($ds = $margin_left; $ds > 0; $ds--) {
                    //$dash .= '-';
                }
            }
            $cat['title'] = $dash .' ' . strip_tags(stripslashes($cat['title']));
            $resultCategories[] = $cat;
            $subCategories = Category::select(['id', 'title','parent_id', 'image_path'])
            //->where('category_type', $categoryType)
            // ->whereIn('id', UserCategory::where('user_id',auth()->user()->id)->get())
            ->where('category_type', $categoryType)
            ->where('parent_id', $cat['id'])
            ->withCount('childrenCat')
            ->get()->toArray();
            if (!empty($subCategories)) {
                $margin_left = $margin_left + 1;
                $resultData = $this->getAllCategoriesForDropDown(
                    $subCategories,
                    $margin_left,
                    $categoryType
                );
                $resultCategories = array_merge($resultCategories, $resultData);
                $margin_left = $margin_left - 1;
            }
        }
        return $resultCategories;
    }
    public function getFilteredCategoriesForDropDown($allCategories, $margin_left, $categoryType) {
        $resultCategories = [];
        foreach ($allCategories as $cat) {
            $dash = '';
            if ($margin_left > 0) {
                for ($ds = $margin_left; $ds > 0; $ds--) {
                    $dash .= '-';
                }
            }
            $cat['title'] = $dash .' ' . strip_tags(stripslashes($cat['title']));
            $resultCategories[] = $cat;
            $subCategories = Category::select(['id', 'title','parent_id'])
            //->where('category_type', $categoryType)
            ->whereIn('id', UserCategory::select('category_id')->where('user_id',auth()->user()->id)->get())
            ->where('category_type', $categoryType)
            ->where('parent_id', $cat['id'])
            ->withCount('childrenCat')
            ->get()?->toArray();
            if (!empty($subCategories)) {
                $margin_left = $margin_left + 1;
                $resultData = $this->getAllCategoriesForDropDown(
                    $subCategories,
                    $margin_left,
                    $categoryType
                );
                $resultCategories = array_merge($resultCategories, $resultData);
                $margin_left = $margin_left - 1;
            }
        }
        return $resultCategories;
    }
    public function editCategory(UpdateCategoryRequest $request)
    {
        $category_id = $request->category_id;
        $title = $request->title;
        $category_type = array_map('intval', explode(',', $request->category_type));
        $categoryExist = Category::where('id', $category_id)->first();
        $allSubCat = Category::where('parent_id', $request->category_id)->get();
        // if($allSubCat){
        //     foreach ($allSubCat as $key => $cat) {

        //         /**
        //          * Delete Subcat if cat type removed from parent
        //          */
        //         // $parentCatTypesArr = $category_type;
        //         // //return response()->json([$parentCatTypesArr]);
        //         // $childCatTypesArr = is_array($cat->getRawOriginal('category_type'))?$cat->getRawOriginal('category_type'):json_decode($cat->getRawOriginal('category_type'));
        //         // $toKeepArr = array_intersect($parentCatTypesArr,$childCatTypesArr);
        //         // //return response()->json([$parentCatTypesArr,$childCatTypesArr, $toKeepArr,array_values($toKeepArr),count($toKeepArr), array_diff($childCatTypesArr,$parentCatTypesArr)]);
        //         // if(!count($toKeepArr)){
        //         //     $cat->delete();
        //         // }
        //         // else{
        //         //     $typesTobeRemoved = array_diff($childCatTypesArr,$parentCatTypesArr);
        //         //     $cat->update(['category_type'=>$request->category_type]);
        //         // }


        //     }
        // }
        $categoryExist->update(['category_type'=>$request->category_type]);
        $data_to_post = [];
        if($request->image && $request->image!='' && $request->image!='null'){
            $imageName = time().'.'.$request->image->extension();
            $request->image->storeAs('public/catPhotos', $imageName);
            //$ititle = $request->image->getClientOriginalName();
            $path = 'catPhotos/' . $imageName;
            $validated = $request->validated();

            $data_to_post = ['image_path'=>$path];
        }
        $data_to_post['title'] = $title;
        // $data_to_post['category_type'] = $category_type;


        if (empty($categoryExist)) {
            response()->json(['message' => __('apiMessage.categoryNotExist')]);
        } else {
            Category::where('id', $category_id)->update($data_to_post);
            return response()->json(['message' => __('apiMessage.categoryUpdateSuccess')]);
        }

    }
    public function deleteCategory(Request $request)
    {
        $id = $request->id;
        $categoryExist = Category::where('id', $id)->first();
        if (empty($categoryExist)) {
            response()->json(['message' => __('apiMessage.categoryNotExist')]);
        } else {
            //deleting all  child categories
            Category::where('parent_id', $id)->delete();
            //deleting the category
            Category::where('id', $id)->delete();
            return response()->json(['message' => __('apiMessage.categoryDeleteSuccess')]);
        }

    }

    public function getCategoryDetails(Request $request)
    {
        $id = $request->id;
        $categoryExist = Category::where('id', $id)->first();
        if (empty($categoryExist)) {
            return response()->json(['message' => __('apiMessage.categoryNotExist')]);
        } else {
            $categoryDetail = Category::select(['id', 'title', 'parent_id', 'category_type'])
            ->where('id', $id)->first();
            $categoryDetail->setRelation('subcategories', $categoryDetail->subcategories()->paginate(10));
            return new CategoryResource($categoryDetail);
        }
    }

    public function createCategory(CreateCategoryRequest $request)
    {
        if(!empty($request->parent_id)) {
            $categoryType = Category::where('id', $request->parent_id)->first();
            // $image = $request->file('image');
            // $imageName = time().'.'.$request->image->extension();
            // $request->image->storeAs('public/catPhotos', $imageName);
            // //$ititle = $request->image->getClientOriginalName();
            // $path = 'catPhotos/'.$imageName;
            $preselectedData = $request->validated();
            $preselectedData['category_type'] = $categoryType->category_type;
            //echo $categoryType->category_type;die;
            $data = Category::create( $preselectedData );
            // $validated = $request->validated();
            // $validated['image_path'] = $path;
            // $data = Category::create($validated);
            if ($data) {
                    return response()->json(['message' => __('apiMessage.createSubCategory')]);
            } else {
                return response()->json(['error' => __('apiMessage.unauthorized')], 400);
            }
        } else {

            // $imageName = time().'.'.$request->image->extension();
            // $request->image->storeAs('public/catPhotos', $imageName);
            // //$ititle = $request->image->getClientOriginalName();
            // $path = 'catPhotos/'.$imageName;
            $validated = $request->validated();
            //return response()->json($validated);
            // $validated['image_path'] = $path;
            $data = Category::create($validated);

            // $data = Category::create($request->validated());
            if ($data) {
                if (empty($data['parent_id'])) {
                    return response()->json(['message' => __('apiMessage.createCategory')]);
                } else {
                    return response()->json(['message' => __('apiMessage.createSubCategory')]);
                }
            } else {
                return response()->json(['error' => __('apiMessage.unauthorized')], 400);
            }
        }
    }

    public function getTypes() {
        $data =  config('constants.category');
        return new CatTypesResource($data);
    }
    /* category listing from admin end*/
    public function getScrapCategories()
    {
        $categories = Category::withCount('childrenCat')
        ->where('category_type', config('constants.category.ScrapCategory'))
        ->whereNull('parent_id')->orderBy('id', 'DESC')
        ->paginate();
        return new CategoryCollection($categories);
    }
    public function getCharitableCategories()
    {
        $categories = Category::withCount('childrenCat')
        ->where('category_type', config('constants.category.CharitableCategory'))
        ->whereNull('parent_id')->orderBy('id', 'DESC')
        ->paginate();
        return new CategoryCollection($categories);
    }
    public function getServiceCategories()
    {
        $categories = Category::withCount('childrenCat')
        ->where('category_type', config('constants.category.ServiceCategory'))
        ->whereNull('parent_id')->orderBy('id', 'DESC')
        ->paginate();
        return new CategoryCollection($categories);
    }

    public function getRecyclerCategories()
    {
        $categories = Category::withCount('childrenCat')
        ->where('category_type', config('constants.category.RecyclerCategory'))
        ->whereNull('parent_id')->orderBy('id', 'DESC')
        ->paginate();
        return new CategoryCollection($categories);
    }

    public function getProductCategories()
    {
        $categories = Category::withCount('childrenCat')
        ->where('category_type', config('constants.category.ProductCategory'))
        ->whereNull('parent_id')->orderBy('id', 'DESC')
        ->paginate();
        return new CategoryCollection($categories);
    }

    public function getAllParentCat()
    {
        $categories = Category::whereNull('parent_id')->withCount(['subcategories'])->with('form')->orderBy('id', 'DESC')->paginate();
        return new CategoryCollection($categories);
    }

    public function attachForm(Request $request, Category $category, DynamicForm $form){
        CategoryDynamicForm::where('category_id',$category->id)?->delete();
        $catForm = CategoryDynamicForm::create([
            'category_id'   => $category->id,
            'form_id'   => $form->id,
        ]);
        if($catForm){
            return response()->json([
                'message'=>__('apiFormMessages.formLinked'),
                'status'=> 'success'
            ]);
        }
    }

    public function saveSearchFields(Request $request){
        CategoryDynamicForm::where([
            'form_id'=>$request->form_id,
            'category_id'=>$request->category_id
        ])->update(['searchable_fields'=>$request->data]);
        return response()->json($request->all());
    }

    public function getCustomFormsData($catid)
    {
        $forms = CategoryDynamicForm::select(['id','category_id','form_id'])
        ->where('category_id', $catid)
        ->with('forms')->get();
        return new CategoryDynamicFormCollection($forms);
        //return response()->json($forms);
    }

    public function getCatByType(Request $request, $catType)
    {
        if ($catType == config('constants.category.ScrapCategory')) {
            return $this->getScrapCategoryList();
        } elseif ($catType == config('constants.category.CharitableCategory')) {
            return $this->getCharitableCategoryList();
        } elseif ($catType == config('constants.category.ServiceCategory')) {
            return $this->getServiceCategoryList($request);
        } elseif ($catType == config('constants.category.RecyclerCategory')) {
            return $this->getRecyclerCategoryList();
        } elseif ($catType == config('constants.category.ProductCategory')) {
            return $this->getProductCategoryList($request);
        } elseif ($catType == config('constants.category.ShareCategory')) {
            return $this->getShareCategoryList($request);
        }
    }

    public function getShareCategoryList()
    {
        $categoryType = config('constants.category.ShareCategory');
        $categories = Category::select(['id', 'title','parent_id', 'image_path'])
        //->where('category_type', $categoryType)
        ->where('category_type', $categoryType)
        ->whereNull('parent_id')->withCount('childrenCat')->get()->toArray();
        $categoriesList = $this->getAllCategoriesForDropDown(
            $categories,
            0,
            $categoryType
        );
        return new CategoryCollection($categoriesList);
    }
}
