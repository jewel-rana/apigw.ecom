<?php

namespace Modules\CMS\App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\CMS\App\Events\BannerCacheRemoveEvent;
use Modules\CMS\App\Jobs\BannerUploadJob;
use Modules\CMS\App\Models\Banner;
use Modules\CMS\App\Repositories\Interfaces\BannerRepositoryInterface;
use Modules\Media\MediaService;
use Yajra\DataTables\Facades\DataTables;

class BannerService
{
    private BannerRepositoryInterface $repository;

    public function __construct(BannerRepositoryInterface $bannerRepository)
    {
        $this->repository = $bannerRepository;
    }

    public function all()
    {
        return Cache::rememberForever('banners', function() {
            return $this->repository->with('medias')
                ->where('status', true)
                ->get();
        });
    }

    public function cms()
    {
        return Cache::remember('api_banners', 3600, function() {
            return $this->all()->map(function(Banner $banner) {
                return $banner->format() +
                    [
                        'medias' => $banner->medias->map(function($item) {
                            return $item->only(['pivot']) +
                                [
                                    'attachment' => $item->attachment
                                ];
                        })
                    ];
            });
        });
    }

    public function getDataTable(Request $request): JsonResponse
    {
        $banners = Banner::with(['medias'])->select(['id', 'name', 'label']);

        return Datatables::of($banners)
            ->addColumn('items', function($banner) {
                $str = '<div class="avatar-group"><a href="' . route('banner.show', $banner->id) . '">';
                $banner->medias->each(function($item, $key) use(&$str, $banner) {
                    $str .= '<div data-toggle="tooltip" data-popup="tooltip-custom" data-placement="top" title="" class="avatar pull-up my-0" data-original-title="' . $banner->name . '">
                        <img src="' . asset($item->attachment) . '" alt="' . $banner->name . '" height="26" width="26" />
                        </div>';
                });
                $str .= '</a></div>';
                return $str;
            })
            ->addColumn('action', function($banner) {
                return "<a href='" . route('banner.show', $banner->id) . "' class='btn btn-success'><i class='fa fa-wrench'></i> manage</a>
                    <a href='" . route('banner.edit', $banner->id) . "' class='btn btn-default'><i class='fa fa-edit'></i></a>";
            })
            ->rawColumns(['action', 'items'])->addIndexColumn()
            ->removeColumn('medias')
            ->make(true);
    }

    public function addItem(array $data): void
    {
        BannerUploadJob::dispatch($data, app(MediaService::class));
        event(new BannerCacheRemoveEvent());
    }

    public function create(array $data): RedirectResponse
    {
        try {
            $this->repository->create($data);
            event(new BannerCacheRemoveEvent());
        } catch (\Throwable $exception) {
            session()->flash('error', $exception->getMessage());
            return redirect()->back()->withInput($data);
        }

        return redirect()->route('banner.index');
    }

    public function update(array $data, $id): RedirectResponse
    {
        try {
            $this->repository->update($data, $id);
            event(new BannerCacheRemoveEvent());
        } catch (\Throwable $exception) {
            session()->flash('error', $exception->getMessage());
            return redirect()->back()->withInput($data);
        }

        return redirect()->route('banner.index');
    }
}
