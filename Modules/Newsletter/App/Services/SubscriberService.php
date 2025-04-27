<?php

namespace Modules\Newsletter\App\Services;

use App\Helpers\LogHelper;
use Modules\Newsletter\App\Models\NewsletterSubscriber;

class SubscriberService
{
    public function subscribe($request)
    {
        try {
            NewsletterSubscriber::updateOrCreate(
                $request->validated(),
                [
                    'is_subscribed' => true
                ]
            );
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'NEWSLETTER_SUBSCRIPTION_EXCEPTION'
            ]);
            return response()->failed();
        }
    }

    public function unsubscribe($request)
    {
        try {
            NewsletterSubscriber::updateOrCreate(
                $request->validated(),
                [
                    'is_subscribed' => false
                ]
            );
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'NEWSLETTER_UNSUBSCRIPTION_EXCEPTION'
            ]);
            return response()->failed();
        }
    }
}
