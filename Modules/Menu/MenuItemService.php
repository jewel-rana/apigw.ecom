<?php


namespace Modules\Menu;


use App\Helpers\CommonHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Category\App\Models\Category;
use Modules\Menu\App\Models\MenuAttribute;
use Modules\Menu\Entities\MenuItem;
use Modules\Menu\Repository\MenuItemRepositoryInterface;
use Modules\Page\Entities\Page;
use Modules\ServiceType\Entities\ServiceType;

class MenuItemService
{
    private MenuItemRepositoryInterface $repository;

    public function __construct(MenuItemRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function all($menuID)
    {
        return $this->repository->all()->where('menu_id', $menuID);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->repository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    public function page($menu, $request): void
    {
        $page = Page::find($request->input('id'));
        $menu->items()->save(new MenuItem([
            'name' => $page->title,
            'menu_url' => Str::start($page->slug, 'pg/'),
            'description' => $page->short_description
        ]));
    }

    public function category($menu, $request): void
    {
        $category = Category::find($request->input('id'));
        $menu->items()->save(new MenuItem([
            'name' => $category->name,
            'menu_url' => Str::start(strtolower($category->code), 'category/'),
            'description' => $category->short_description
        ]));
    }

    public function service($menu, $request): void
    {
        $service = ServiceType::find($request->input('id'));
        $menu->items()->save(new MenuItem([
            'name' => $service->label,
            'menu_url' => Str::slug(strtolower($service->name)),
            'description' => $service->label
        ]));
    }

    public function getDataTable($request): JsonResponse
    {
        return datatables()->eloquent(
            $this->repository->getModel()
                ->filter($request)
        )
            ->toJson();
    }

    public function getAttributeDataTable($request): JsonResponse
    {
        return datatables()->eloquent(
            MenuAttribute::with(['menuItem'])->filter($request)
        )
            ->addColumn('actions', function ($item) {
                return "<a class='btn btn-default editLangAttribute'
                data-id='" . $item->id . "'
                data-payload='" . json_encode($item->format()) . "'>
                <i class='fa fa-edit'></i>
                </a>";
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function getSuggestions($menuId, $request): JsonResponse
    {
        try {
            $data = $this->all($menuId)->filter(function ($item) use ($request) {
                $matched = true;
                if ($request->has('term')) {
                    $matched = CommonHelper::matchText($item, $request->input('term'));
                }
                return $matched;
            })
                ->map(function ($item, $key) {
                    return [
                        'id' => $item->id,
                        'text' => $item->name
                    ];
                })->values();
            return response()->json(['results' => $data]);
        } catch (\Exception $exception) {
            return response()->json(['message' => __('No data!'), 'results' => []]);
        }
    }
}
