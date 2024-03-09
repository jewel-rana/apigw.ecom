<?php

namespace App\Services;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use App\Models\Complain;
use App\Repositories\Interfaces\ComplainRepositoryInterface;
use Illuminate\Http\Request;

class ComplainService
{
    private ComplainRepositoryInterface $complainRepository;

    public function __construct(ComplainRepositoryInterface $complainRepository)
    {
        $this->complainRepository = $complainRepository;
    }

    public function all(Request $request)
    {
        $feedbacks = $this->complainRepository->getModel()->filter($request)
            ->latest()
            ->paginate(CommonHelper::perPage($request));
        return response()->success(CommonHelper::parsePaginator($feedbacks));
    }

    public function create(array $data)
    {
        try {
            $this->complainRepository->create($data);
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'FEEDBACK_CREATE_EXCEPTION'
            ]);
            return response()->error(__('Internal server error!'));
        }
    }

    public function update(array $data, Complain $complain)
    {
        try {
            $this->complainRepository->update($data, $complain->id);
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'FEEDBACK_CREATE_EXCEPTION'
            ]);
            return response()->error(__('Internal server error!'));
        }
    }

    public function action(Complain $complain, Request $request)
    {
        try {
            $complain->update($request->validated());
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'FEEDBACK_ACTION_EXCEPTION'
            ]);
            return response()->error();
        }
    }
}
