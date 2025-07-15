<?php

namespace Modules\Banner\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Banner\Jobs\BannerUploadJob;
use Modules\Banner\Entities\Banner;
use Modules\Banner\Repositories\Interfaces\BannerRepositoryInterface;
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
                return $banner->format();
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

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->repository->update($data, $id);
    }
}
