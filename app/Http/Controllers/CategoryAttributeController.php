<?php

namespace Modules\Category\App\Http\Controllers;

use App\Helpers\LogHelper;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Category\App\Http\Requests\StoreAttributeRequest;
use Modules\Category\App\Http\Requests\UpdateAttributeRequest;
use Modules\Category\App\Models\CategoryAttribute;
use Modules\Category\App\Services\CategoryService;
use Modules\Region\Services\LanguageService;

class CategoryAttributeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return datatables()->eloquent(
            CategoryAttribute::where('category_id', $request->input('category_id'))
        )
            ->addColumn('actions', function (CategoryAttribute $attribute) {
                $actions = '';
                $actions .= '<a href="' . route('category.attribute.edit', $attribute->id). '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>';
                $actions .= '<button data-action="' . route('category.attribute.destroy', $attribute->id). '" class="btn btn-sm btn-danger deleteBtn" data-type="attribute"><i class="fa fa-times"></i></button>';
                return $actions;
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function create()
    {
        return view('category::attribute.create', [
            'languages' => app(LanguageService::class)->all()
        ])->with(['title' => 'Add new attribute']);
    }

    public function store(StoreAttributeRequest $request): RedirectResponse
    {
        try {
            $attribute = CategoryAttribute::create($request->validated());
            return redirect()->route('category.show', [$attribute->category_id, 'tab' => 'attribute'])->with(['status' => true, 'message' => __('Attribute created successfully')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'CATEGORY_ATTRIBUTE_CREATE_EXCEPTION'
            ]);
            return redirect()->back()->withInput($request->all())->with(['status' => true, 'message' => __('Failed to create attribute')]);
        }
    }

    public function edit(CategoryService $attribute)
    {
        return view('category::attribute.edit', [
            'attribute' => $attribute,
            'languages' => app(LanguageService::class)->all()
        ])->with(['title' => 'Update attribute']);
    }

    public function update(UpdateAttributeRequest $request, CategoryAttribute $attribute): RedirectResponse
    {
        try {
            $attribute->update($request->validated(), $attribute->id);
            return redirect()->route('category.show', [$attribute->category_id, 'tab' => 'attribute'])->with(['status' => true, 'message' => __('Attribute updated successfully')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'CATEGORY_ATTRIBUTE_UPDATE_EXCEPTION'
            ]);
            return redirect()->back()->withInput($request->all())->with(['status' => true, 'message' => __('Failed to update attribute')]);
        }
    }

    public function destroy(CategoryAttribute $attribute): JsonResponse
    {
        try {
            $attribute->delete();
            return response()->json(['status' => true, 'content' => __('Success')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'CATEGORY_ATTRIBUTE_DELETE_EXCEPTION'
            ]);

            return response()->json(['status' => false, 'content' => __('Could not delete attribute')]);
        }
    }
}
