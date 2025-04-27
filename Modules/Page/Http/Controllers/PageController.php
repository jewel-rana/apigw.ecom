<?php

namespace Modules\Page\Http\Controllers;

use App\Helpers\CommonHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;
use Modules\Operator\Entities\Operator;
use Modules\Page\Entities\Page;
use Modules\Page\Http\Requests\PageCreateRequest;
use Modules\Page\PageService;
use Modules\Page\Http\Requests\PageUpdateRequest;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    const templates = ["Default", "About Us"];

    private PageService $pages;

    public function __construct(PageService $pages)
    {
        $this->pages = $pages;
    }

    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $pages = Page::select(['id', 'title', 'slug', 'template'])->get();

            return Datatables::of($pages)
                ->addColumn('action', function ($page) {
                    if(CommonHelper::hasPermission(['page-update'])){
                        return "<a href='" . route('page.edit', $page->id) . "' class='btn btn-default'><i class='fa fa-edit'></i></a>
";
                    }
                    return '';
                })
                ->rawColumns(['description', 'action'])->addIndexColumn()->make(true);
        }
        return view('page::index')->withTitle('Pages');
    }

    public function create()
    {
        $templates = PageController::templates;
        return view('page::create', compact('templates'))->withTitle('Add new page');
    }

    public function store(PageCreateRequest $request)
    {
        try {
            $this->pages->create($request->validated());
        } catch (\Throwable $exception) {
            session()->flash('error', $exception->getMessage());
            return redirect()->back();
        }

        return redirect()->route('page.index');
    }

    public function show($slug)
    {
        return response()->success(
            $this->pages->get($slug)
        );
    }

    public function edit($id)
    {
        $templates = PageController::templates;
        $page = Page::findOrFail($id);
        return view('page::edit', compact('templates', 'page'))->withTitle('Add new page');
    }

    public function update(PageUpdateRequest $request, $id): RedirectResponse
    {
        try {
            $this->pages->update($request->validated(), $id);
        } catch (\Throwable $exception) {
            session()->flash('error', $exception->getMessage());
        }

        return redirect()->route('page.index');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();
        return redirect()->back();
    }

    public function callAction($method, $parameters)
    {
        if (!in_array($method, ['suggestions', 'show'])) {
            $this->authorize($method, Page::class);
        }
        return parent::callAction($method, $parameters);
    }
}
