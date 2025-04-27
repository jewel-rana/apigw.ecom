<?php

namespace Modules\CMS\App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\CMS\App\Events\BannerCacheRemoveEvent;
use Modules\CMS\App\Http\Requests\UpdateBannerItem;
use Modules\CMS\App\Jobs\BannerUploadJob;
use Modules\CMS\App\Models\Banner;
use Modules\Media\MediaService;

class BannerItemController extends Controller
{

    public function edit(Banner $banner, $itemId)
    {
        $item = $banner->medias()->find($itemId);
        return view('cms::banner.item.edit', compact('banner', 'item'))->with(['title' => "Update: {$item->pivot->title}"]);
    }

    public function update(UpdateBannerItem $request, Banner $banner, $itemId): RedirectResponse
    {
        try {
            BannerUploadJob::dispatch($request->all(), app(MediaService::class), $itemId);
            event(new BannerCacheRemoveEvent());
            session()->flash('success', 'Successfully updated');
            return redirect()->route('banner.show', $banner->id);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'BANNER_ITEM_UPDATE_EXCEPTION'
            ]);
            session()->flash('error', $exception->getMessage());
            return redirect()->back();
        }
    }
}
