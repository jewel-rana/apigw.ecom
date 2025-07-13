<?php

namespace Modules\CMS\App\Http\Controllers\Api;

use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CMS\App\Models\HomeCard;
use Modules\CMS\Http\Requests\StoreHomeCardRequest;
use Modules\CMS\Http\Requests\UpdateHomeCardRequest;
use Modules\Media\MediaService;

class HomeCardController extends Controller
{
    public function index(Request $request)
    {
        $cards = HomeCard::filter($request)->paginate($request->input('per_page', 10));
        return response()->success(
            CommonHelper::parsePaginator($cards)
        );
    }

    public function store(StoreHomeCardRequest $request)
    {
        try {
            $card = HomeCard::create($request->validated());
            if($request->hasFile('attachment')) {
                $media = app(MediaService::class)->upload($request->file('attachment'));
                $card->update(['icon' => $media->getFullUrl()]);
            }
            return response()->success();
        } catch (\Exception $e) {
            return response()->error($e->getMessage());
        }
    }

    public function show(HomeCard $homeCard)
    {
        return response()->success(
            $homeCard->format()
        );
    }

    public function update(UpdateHomeCardRequest $request, HomeCard $homeCard)
    {
        try {
            $homeCard->update($request->validated());
            return response()->success();
        } catch (\Exception $e) {
            return response()->error($e->getMessage());
        }
    }

    public function destroy(HomeCard $homeCard)
    {
        try {
            $homeCard->delete();
            return response()->success();
        } catch (\Exception $e) {
            return response()->error($e->getMessage());
        }
    }
}
