<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentCreateRequest;
use App\Http\Requests\PaymentVerifyRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        //
    }

    public function store(PaymentCreateRequest $request)
    {
        return $this->paymentService->create($request);
    }

    public function show(Payment $payment)
    {
        //
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        //
    }

    public function execute(PaymentVerifyRequest $request)
    {
        return $this->paymentService->execute($request);
    }

    public function verify(PaymentVerifyRequest $request)
    {
        return $this->paymentService->verify($request);
    }
}
