<?php

namespace App\Services;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use App\Models\Customer;
use App\Models\Feedback;
use App\Repositories\Interfaces\FeedbackRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FeedbackService
{
    private FeedbackRepositoryInterface $feedbackRepository;

    public function __construct(FeedbackRepositoryInterface $feedbackRepository)
    {
        $this->feedbackRepository = $feedbackRepository;
    }

    public function all(Request $request)
    {
        $feedbacks = $this->feedbackRepository->getModel()->filter($request)
            ->latest()
            ->paginate($request->input('per_page', 10));
        return response()->success(CommonHelper::parsePaginator($feedbacks));
    }

    public function create(array $data)
    {
        try {
            $this->feedbackRepository->create($data);
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'FEEDBACK_CREATE_EXCEPTION'
            ]);
            return response()->error(__('Internal server error!'));
        }
    }

    public function update(array $data, Feedback $feedback)
    {
        try {
            $this->feedbackRepository->update($data, $feedback->id);
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'FEEDBACK_CREATE_EXCEPTION'
            ]);
            return response()->error(__('Internal server error!'));
        }
    }

    public function action(Feedback $feedback, Request $request)
    {
        try {
            $feedback->update($request->validated());
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'FEEDBACK_ACTION_EXCEPTION'
            ]);
            return response()->error();
        }
    }
}
