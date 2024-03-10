<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackActionRequest;
use App\Http\Requests\StoreComplainRequest;
use App\Http\Requests\StoreFeedbackRequest;
use App\Http\Requests\UpdateComplainRequest;
use App\Http\Requests\UpdateFeedbackRequest;
use App\Models\Complain;
use App\Services\ComplainService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ComplainController extends Controller
{
    private ComplainService $complainService;

    public function __construct(ComplainService $complainService)
    {
        $this->complainService = $complainService;
    }

    public function index(Request $request)
    {
        return $this->complainService->all($request);
    }

    public function store(StoreComplainRequest $request)
    {
        return $this->complainService->create($request->validated());
    }

    public function show(Complain $feedback)
    {
        return response()->success($feedback->format());
    }

    public function update(UpdateComplainRequest $request, Complain $complain)
    {
        return $this->complainService->update($request->validated(), $complain);
    }

    public function action(FeedbackActionRequest $request, Complain $complain)
    {
        return $this->complainService->action($complain, $request);
    }

    public function callAction($method, $parameters): Response
    {
        if (!in_array($method, ['action'])) {
            $this->authorize($method, Complain::class);
        }
        return parent::callAction($method, $parameters);
    }
}
