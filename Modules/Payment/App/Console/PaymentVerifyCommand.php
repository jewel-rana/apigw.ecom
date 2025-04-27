<?php

namespace Modules\Payment\App\Console;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Console\Command;
use Modules\Order\App\Constant\OrderConstant;
use Modules\Payment\App\Constants\PaymentConstant;
use Modules\Payment\App\Constants\PaymentRefundConstant;
use Modules\Payment\App\Repositories\Interfaces\PaymentRepositoryInterface;

class PaymentVerifyCommand extends Command
{
    protected $signature = 'payment:verify';

    protected $description = 'Verify payment from gateway';

    private PaymentRepositoryInterface $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        parent::__construct();
        $this->paymentRepository = $paymentRepository;
    }

    public function handle(): void
    {
        try {
            $this->paymentRepository->with(['order', 'gateway'])
                ->whereIn('status', [PaymentConstant::STATUS_PROCESSING, PaymentConstant::STATUS_PENDING])
//                ->where('created_at', '<=', now()->subMinutes(PaymentConstant::PROCESSING_PERIOD)->format('Y-m-d H:i:s'))
                ->cursor()
                ->each(function($payment, $_k) {
                    $gateway = CommonHelper::purseGateway($payment->gateway);
                    $data = ['status' => false, 'payment_status' => PaymentConstant::STATUS_PENDING];
                    $data = (new $gateway($payment->gateway))->verify($payment, $data);
                    if(
                        array_key_exists('payment_status', $data)
                        && !in_array($data['payment_status'], [PaymentConstant::STATUS_SUCCESS, PaymentConstant::STATUS_PENDING])
                    ) {
                        if($payment->refund && $payment->refund->status == PaymentRefundConstant::REFUND_STATUS_PENDING) {
                            $payment->refund->update([
                                'status' => PaymentRefundConstant::REFUND_STATUS_FAILED,
                                'remarks' => 'Payment failed'
                            ]);
                        }
                    } elseif($data['payment_status'] == PaymentConstant::STATUS_SUCCESS) {
                        if($payment->refund && $payment->refund->status == PaymentRefundConstant::REFUND_STATUS_PENDING) {
                            $payment->refund->update(['status' => PaymentRefundConstant::REFUND_STATUS_PROCESSING]);
                        }
                    }
                });
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'PAYMENT_VERIFY_COMMAND_EXCEPTION'
            ]);
        }
    }
}
