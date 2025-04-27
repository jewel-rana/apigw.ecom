<?php

namespace Modules\Newsletter\App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Newsletter\App\Http\Requests\StoreNewsletterSubscriberRequest;
use Modules\Newsletter\App\Services\SubscriberService;

class NewsletterSubscriptionController extends Controller
{
    private SubscriberService $subscriberService;

    public function __construct(SubscriberService $subscriberService)
    {
        $this->subscriberService = $subscriberService;
    }

    public function subscribe(StoreNewsletterSubscriberRequest $request)
    {
        return $this->subscriberService->subscribe($request);
    }

    public function unsubscribe(StoreNewsletterSubscriberRequest $request)
    {
        return $this->subscriberService->unsubscribe($request);
    }
}
