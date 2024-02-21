<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackActionRequest;
use App\Http\Requests\StoreFeedbackRequest;
use App\Http\Requests\UpdateFeedbackRequest;
use App\Models\Feedback;
use App\Services\FeedbackService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class FeedbackController extends Controller
{
    private FeedbackService $feedbackService;

    public function __construct(FeedbackService $feedbackService)
    {
        $this->feedbackService = $feedbackService;
    }

    public function index(Request $request)
    {
        return $this->feedbackService->all($request);
    }

    public function store(StoreFeedbackRequest $request)
    {
        return $this->feedbackService->create($request->validated());
    }

    public function update(UpdateFeedbackRequest $request, Feedback $feedback)
    {
        return $this->feedbackService->update($request->validated(), $feedback);
    }

    public function action(FeedbackActionRequest $request, Feedback $feedback)
    {
        return $this->feedbackService->action($feedback, $request);
    }

    public function callAction($method, $parameters): Response
    {
        if (Arr::hasAny(['action'], $method)) {
            $this->authorize($method, Feedback::class);
        }
        return parent::callAction($method, $parameters);
    }
}
