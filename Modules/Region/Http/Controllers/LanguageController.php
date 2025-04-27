<?php

namespace Modules\Region\Http\Controllers;

use App\Helpers\LogHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Operator\Entities\Operator;
use Modules\Region\Entities\Language;
use Modules\Region\Http\Requests\LanguageCreateRequest;
use Modules\Region\Http\Requests\LanguageUpdateRequest;
use Modules\Region\Services\LanguageService;

class LanguageController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    private LanguageService $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    public function index(Request $request)
    {
        if($request->acceptsJson() && $request->wantsJson()) {
            return $this->languageService->getDataTables($request);
        }
        return view('region::language.index')->with(['title' => 'Languages']);
    }

    public function create()
    {
        return view('region::language.create')->with(['title' => __('Add new language')]);
    }

    public function store(LanguageCreateRequest $request): RedirectResponse
    {
        try {
            Language::create($request->validated());
            return redirect()->route('language.index')->with(['status' => true, 'message' => __('Language created successfully')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'BUNDLE_ATTRIBUTE_CREATE_EXCEPTION'
            ]);
            return redirect()->back()->withInput($request->all())->with(['status' => false, 'message' => __('Failed to create language')]);
        }
    }

    public function edit(Language $language)
    {
        return view('region::language.edit', compact('language'))->with(['title' => __('Update language')]);
    }

    public function update(LanguageUpdateRequest $request, Language $language): RedirectResponse
    {
        try {
            $language->update($request->validated());
            return redirect()->route('language.index')->with(['status' => true, 'message' => __('Language updated successfully')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'BUNDLE_ATTRIBUTE_UPDATE_EXCEPTION'
            ]);
            return redirect()->back()->withInput($request->all())->with(['status' => false, 'message' => __('Failed to update language')]);
        }
    }

    public function destroy(Language $language): RedirectResponse
    {
        try {
            $language->delete();
            return redirect()->route('language.index')->with(['status' => true, 'message' => __('Language deleted successfully')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'BUNDLE_ATTRIBUTE_DELETE_EXCEPTION'
            ]);
            return redirect()->back()->with(['status' => false, 'message' => __('Failed to delete language')]);
        }
    }

    public function callAction($method, $parameters)
    {
        if (!in_array($method, ['suggestions', 'delete'])) {
            $this->authorize($method, Language::class);
        }
        return parent::callAction($method, $parameters);
    }
}
